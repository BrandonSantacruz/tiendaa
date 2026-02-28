<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SellerApprovalController extends Controller
{
    public function __construct()
    {
        // Solo super-admin puede acceder
        $this->middleware('can:approve-sellers');
    }

    /**
     * Listar vendedores pendientes de aprobación
     */
    public function index()
    {
        $pendingSellers = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendedor');
        })
            ->where('seller_status', 'pending')
            ->paginate(15);

        $approvedSellers = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendedor');
        })
            ->where('seller_status', 'approved')
            ->paginate(15);

        $rejectedSellers = User::whereHas('roles', function ($query) {
            $query->where('name', 'vendedor');
        })
            ->where('seller_status', 'rejected')
            ->paginate(15);

        return view('admin.sellers.index', compact(
            'pendingSellers',
            'approvedSellers',
            'rejectedSellers'
        ));
    }

    /**
     * Mostrar detalles del vendedor
     */
    public function show(User $user)
    {
        // Verificar que es vendedor
        if (!$user->hasRole('vendedor')) {
            abort(404);
        }

        $stats = [
            'total_products' => $user->products()->count(),
            'total_commissions' => $user->commissions()->sum('commission_amount'),
            'pending_commissions' => $user->getPendingCommissionTotal(),
            'monthly_earnings' => $user->getMonthlyEarnings(),
            'total_earnings' => $user->getTotalCommissions(),
            'recent_commissions' => $user->commissions()->latest()->take(5)->get(),
        ];

        return view('admin.sellers.show', compact('user', 'stats'));
    }

    /**
     * Aprobar vendedor
     */
    public function approve(User $user)
    {
        // Verificar que es vendedor en estado pending
        if (!$user->hasRole('vendedor') || !$user->isPendingSeller()) {
            return back()->with('error', 'El vendedor no puede ser aprobado en este estado.');
        }

        try {
            $user->update([
                'seller_status' => 'approved',
                'seller_approved_at' => now(),
                'seller_rejection_reason' => null,
            ]);

            // Aquí puedes agregar notificación por email
            // Notification::send($user, new SellerApprovedNotification());

            return back()->with('success', 'Vendedor aprobado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al aprobar vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar vendedor
     */
    public function reject(User $user, Request $request)
    {
        // Verificar que es vendedor
        if (!$user->hasRole('vendedor')) {
            return back()->with('error', 'El usuario no es vendedor.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        try {
            $user->update([
                'seller_status' => 'rejected',
                'seller_rejection_reason' => $validated['reason'],
            ]);

            // Aquí puedes agregar notificación por email
            // Notification::send($user, new SellerRejectedNotification($validated['reason']));

            return back()->with('success', 'Vendedor rechazado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al rechazar vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Suspender vendedor
     */
    public function suspend(User $user, Request $request)
    {
        // Verificar que es vendedor aprobado
        if (!$user->hasRole('vendedor') || !$user->isApprovedSeller()) {
            return back()->with('error', 'Solo se pueden suspender vendedores aprobados.');
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:10', 'max:500'],
        ]);

        try {
            $user->update([
                'seller_status' => 'suspended',
                'seller_rejection_reason' => $validated['reason'],
            ]);

            // Aquí puedes agregar notificación por email
            // Notification::send($user, new SellerSuspendedNotification($validated['reason']));

            return back()->with('success', 'Vendedor suspendido correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al suspender vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Reacticar vendedor suspendido
     */
    public function reactivate(User $user)
    {
        // Verificar que es vendedor suspendido
        if (!$user->hasRole('vendedor') || !$user->isSuspendedSeller()) {
            return back()->with('error', 'Solo se pueden reactivar vendedores suspendidos.');
        }

        try {
            $user->update([
                'seller_status' => 'approved',
                'seller_rejection_reason' => null,
            ]);

            // Aquí puedes agregar notificación por email
            // Notification::send($user, new SellerReactivatedNotification());

            return back()->with('success', 'Vendedor reactivado correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al reactivar vendedor: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar comisión de vendedor
     */
    public function updateCommission(User $user, Request $request)
    {
        // Verificar que es vendedor
        if (!$user->hasRole('vendedor')) {
            return back()->with('error', 'El usuario no es vendedor.');
        }

        $validated = $request->validate([
            'commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        try {
            $user->update([
                'commission_rate' => $validated['commission_rate'],
            ]);

            return back()->with('success', 'Comisión actualizada correctamente.');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar comisión: ' . $e->getMessage());
        }
    }
}
