<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:access-seller-dashboard');
    }

    /**
     * Panel principal del vendedor
     */
    public function index()
    {
        $user = auth()->user();

        // Verificar que es vendedor aprobado
        if (!$user->isApprovedSeller()) {
            abort(403, 'Debes ser un vendedor aprobado para acceder al panel.');
        }

        // Estadísticas principales
        $stats = [
            'total_products' => $user->products()->count(),
            'total_sales' => $user->commissions()->sum('sale_amount'),
            'pending_commissions' => $user->getPendingCommissionTotal(),
            'approved_commissions' => $user->commissions()->where('status', 'approved')->sum('commission_amount'),
            'paid_commissions' => $user->commissions()->where('status', 'paid')->sum('commission_amount'),
            'monthly_earnings' => $user->getMonthlyEarnings(),
            'total_commissions' => $user->getTotalCommissions(),
            'monthly_sales' => $user->commissions()
                ->whereMonth('sale_date', now()->month)
                ->whereYear('sale_date', now()->year)
                ->count(),
            'commission_rate' => $user->commission_rate,
        ];

        // Comisiones recientes
        $recentCommissions = $user->commissions()
            ->with('product')
            ->latest()
            ->paginate(10);

        // Pagos recientes
        $recentPayouts = $user->payouts()
            ->latest()
            ->paginate(10);

        // Gráfico: Ingresos por mes (últimos 12 meses)
        $monthlyEarnings = $this->getMonthlyEarningsData($user);

        // Gráfico: Distribución de comisiones por estado
        $commissionStats = [
            'pending' => $user->commissions()->where('status', 'pending')->count(),
            'approved' => $user->commissions()->where('status', 'approved')->count(),
            'paid' => $user->commissions()->where('status', 'paid')->count(),
            'refunded' => $user->commissions()->where('status', 'refunded')->count(),
        ];

        return view('seller.dashboard', compact(
            'stats',
            'recentCommissions',
            'recentPayouts',
            'monthlyEarnings',
            'commissionStats'
        ));
    }

    /**
     * Listado de comisiones
     */
    public function commissions(Request $request)
    {
        $user = auth()->user();

        if (!$user->isApprovedSeller()) {
            abort(403, 'Debes ser un vendedor aprobado para acceder.');
        }

        $query = $user->commissions()->with(['product', 'seller']);

        // Filtros
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('sale_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('sale_date', '<=', $request->date_to);
        }

        $commissions = $query->latest('sale_date')->paginate(20);

        return view('seller.commissions', compact('commissions'));
    }

    /**
     * Listado de pagos
     */
    public function payouts(Request $request)
    {
        $user = auth()->user();

        if (!$user->isApprovedSeller()) {
            abort(403, 'Debes ser un vendedor aprobado para acceder.');
        }

        $query = $user->payouts();

        // Filtros
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('period_start', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('period_end', '<=', $request->date_to);
        }

        $payouts = $query->latest('period_start')->paginate(20);

        // Estadísticas de pagos
        $payoutStats = [
            'total_paid' => $user->payouts()->where('status', 'completed')->sum('amount'),
            'pending_amount' => $user->payouts()->where('status', 'pending')->sum('amount'),
            'processing_amount' => $user->payouts()->where('status', 'processing')->sum('amount'),
        ];

        return view('seller.payouts', compact('payouts', 'payoutStats'));
    }

    /**
     * Detalles de una comisión
     */
    public function commissionDetail(Request $request)
    {
        $user = auth()->user();
        $commissionId = $request->route('commission');

        $commission = $user->commissions()
            ->with(['product', 'seller'])
            ->findOrFail($commissionId);

        return view('seller.commission-detail', compact('commission'));
    }

    /**
     * Detalles de un pago
     */
    public function payoutDetail(Request $request)
    {
        $user = auth()->user();
        $payoutId = $request->route('payout');

        $payout = $user->payouts()
            ->findOrFail($payoutId);

        // Comisiones asociadas al pago
        $commissions = $user->commissions()
            ->whereBetween('sale_date', [$payout->period_start, $payout->period_end])
            ->where('status', 'approved')
            ->orWhere('status', 'paid')
            ->get();

        return view('seller.payout-detail', compact('payout', 'commissions'));
    }

    /**
     * Perfil del vendedor
     */
    public function profile()
    {
        $user = auth()->user();

        if (!$user->isApprovedSeller()) {
            abort(403, 'Debes ser un vendedor aprobado para acceder.');
        }

        return view('seller.profile', compact('user'));
    }

    /**
     * Actualizar perfil del vendedor
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        if (!$user->isApprovedSeller()) {
            abort(403, 'Debes ser un vendedor aprobado para acceder.');
        }

        $validated = $request->validate([
            'phone' => ['required', 'string', 'min:10'],
            'company_description' => ['required', 'string', 'min:10', 'max:1000'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'company_logo' => ['nullable', 'image', 'max:2048'],
        ]);

        try {
            // Procesar logo si existe
            if ($request->hasFile('company_logo')) {
                // Eliminar logo anterior si existe
                if ($user->company_logo) {
                    \Storage::disk('public')->delete($user->company_logo);
                }
                $validated['company_logo'] = $request->file('company_logo')->store('company-logos', 'public');
            }

            $user->update($validated);

            return back()->with('success', 'Perfil actualizado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    /**
     * Obtener datos de ingresos mensuales (últimos 12 meses)
     */
    private function getMonthlyEarningsData($user)
    {
        $data = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $earnings = $user->commissions()
                ->whereMonth('sale_date', $date->month)
                ->whereYear('sale_date', $date->year)
                ->sum('commission_amount');

            $data[] = [
                'month' => $date->format('M Y'),
                'earnings' => (float) $earnings ?? 0,
            ];
        }

        return $data;
    }
}
