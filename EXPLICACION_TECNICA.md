# 🎯 EXPLICACIÓN DETALLADA DE CADA COMPONENTE

## 1️⃣ MODELOS Y SUS RELACIONES

### Modelo: User (`app/Models/User.php`)

```php
class User extends Authenticatable
{
    // Relaciones
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }
    
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role')
            ->through('roles');
    }
    
    // Métodos de verificación
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }
    
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }
    
    public function hasAllRoles(array $roles): bool
    {
        return count($roles) === $this->roles()
            ->whereIn('slug', $roles)
            ->count();
    }
    
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }
    
    public function getPrimaryRole(): ?Role
    {
        return $this->roles()->first();
    }
}
```

**¿Cómo funciona?**

1. Un usuario puede tener **varios roles** (relación Many-to-Many)
2. A través de sus roles, obtiene **todos los permisos**
3. `hasRole()`: Verifica si tiene un rol específico
4. `hasAnyRole()`: Verifica si tiene alguno de los roles listados
5. `hasAllRoles()`: Verifica si tiene todos los roles listados
6. `hasPermission()`: Verifica si tiene un permiso
7. `getPrimaryRole()`: Obtiene el primer rol (rol principal)

**Ejemplo de uso:**

```php
$user = User::find(1);

// Verificar rol
if ($user->hasRole('super-admin')) {
    // Es super admin
}

// Verificar múltiples roles
if ($user->hasAnyRole(['vendedor', 'cliente'])) {
    // Es vendedor o cliente
}

// Verificar permiso
if ($user->hasPermission('create-product')) {
    // Puede crear productos
}

// Obtener rol principal
$role = $user->getPrimaryRole();
echo $role->name; // "Super Admin"

// Obtener todos los roles
$roles = $user->roles; // Collection de roles

// Obtener todos los permisos
$permissions = $user->permissions; // Collection de permisos
```

---

### Modelo: Role (`app/Models/Role.php`)

```php
class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description'];
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
    
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
    
    public function assignPermission(Permission|string $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }
        
        if ($permission && !$this->permissions->contains($permission)) {
            $this->permissions()->attach($permission);
        }
    }
    
    public function revokePermission(Permission|string $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }
        
        if ($permission) {
            $this->permissions()->detach($permission);
        }
    }
    
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }
}
```

**¿Cómo funciona?**

1. Un rol puede ser asignado a **múltiples usuarios**
2. Un rol puede tener **múltiples permisos**
3. `assignPermission()`: Asigna un permiso al rol
4. `revokePermission()`: Revoca un permiso del rol
5. `hasPermission()`: Verifica si el rol tiene un permiso

**Ejemplo de uso:**

```php
// Obtener un rol
$role = Role::where('slug', 'vendedor')->first();

// Obtener todos los usuarios con este rol
$users = $role->users;

// Obtener todos los permisos del rol
$permissions = $role->permissions;

// Asignar un permiso
$role->assignPermission('create-product');
$role->assignPermission('view-own-products');

// Revocar un permiso
$role->revokePermission('delete-product');

// Verificar permiso
if ($role->hasPermission('create-product')) {
    echo "Este rol puede crear productos";
}
```

---

### Modelo: Permission (`app/Models/Permission.php`)

```php
class Permission extends Model
{
    protected $fillable = ['name', 'slug', 'description'];
    
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
```

**¿Cómo funciona?**

Simple y extensible:
1. Un permiso puede ser asignado a **múltiples roles**
2. Identificado por `slug` (ej: 'create-product')
3. Tiene nombre legible (ej: 'Crear Producto')
4. Puede tener descripción

**Ejemplo de uso:**

```php
// Obtener un permiso
$permission = Permission::where('slug', 'create-product')->first();

// Obtener todos los roles con este permiso
$roles = $permission->roles;
```

---

## 2️⃣ AUTENTICACIÓN: AuthController

### Flujo de Login

```
Usuario ingresa email/contraseña
         ↓
   Auth::attempt($credentials)
         ↓
  ¿Credenciales válidas?
    ↙ SÍ          ↘ NO
Regenerar    ValidationException
  sesión            ↓
    ↓          Mostrar error
 Obtener rol
principal
    ↓
Redirigir según
  el rol
```

**Código:**

```php
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();
        $role = $user->getPrimaryRole();

        if ($role) {
            return match ($role->slug) {
                'super-admin' => redirect()->route('admin.dashboard'),
                'vendedor' => redirect()->route('seller.dashboard'),
                'cliente' => redirect()->route('client.dashboard'),
                default => redirect()->route('dashboard'),
            };
        }

        return redirect()->route('dashboard');
    }

    throw ValidationException::withMessages([
        'email' => 'Las credenciales no son válidas',
    ]);
}
```

**¿Qué sucede?**

1. Valida email y contraseña
2. Intenta autenticar con `Auth::attempt()`
3. Si es exitoso, obtiene el rol principal del usuario
4. Redirige según el rol:
   - Super Admin → `/admin/dashboard`
   - Vendedor → `/seller/dashboard`
   - Cliente → `/client/dashboard`

### Flujo de Registro

```
Usuario ingresa datos
         ↓
    Validación
         ↓
 ¿Es válido?
  ↙ SÍ    ↘ NO
Crear    Mostrar
usuario  errores
  ↓
Asignar
rol cliente
  ↓
Login automático
  ↓
Redirigir a
dashboard cliente
```

**Código:**

```php
public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    // Asignar rol de cliente por defecto
    $clientRole = Role::where('slug', 'cliente')->first();
    if ($clientRole) {
        $user->roles()->attach($clientRole);
    }

    Auth::login($user);
    return redirect()->route('client.dashboard');
}
```

**¿Qué sucede?**

1. Valida datos del usuario
2. Crea el usuario con contraseña hasheada
3. Asigna automáticamente rol "cliente"
4. Inicia sesión automáticamente
5. Redirige al dashboard del cliente

---

## 3️⃣ MIDDLEWARE: CheckRole y CheckPermission

### CheckRole Middleware

```php
public function handle(Request $request, Closure $next, string ...$roles): Response
{
    // Si el usuario no está autenticado, redirigir al login
    if (!$request->user()) {
        return redirect()->route('login');
    }

    // Verificar si el usuario tiene uno de los roles requeridos
    if (!$request->user()->hasAnyRole($roles)) {
        abort(403, 'No autorizado para acceder a este recurso');
    }

    return $next($request);
}
```

**¿Cómo funciona?**

```
Solicitud HTTP
    ↓
Middleware ejecutado
    ↓
¿Usuario autenticado?
  ↙ NO    ↘ SÍ
Redirigir Verificar
  a login  roles
    ↓
¿Tiene permiso?
 ↙ SÍ    ↘ NO
Continuar  Abortar 403
```

**Uso en rutas:**

```php
// Una ruta protegida por rol
Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->middleware('role:super-admin')
    ->name('admin.dashboard');

// Múltiples roles permitidos
Route::middleware('role:vendedor,cliente')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
});

// En grupo
Route::middleware('role:super-admin')->prefix('admin')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
});
```

**Ejemplo:**

```
GET /admin/dashboard (usuario con rol 'cliente')
                ↓
        CheckRole middleware
                ↓
   hasAnyRole(['super-admin'])
                ↓
           ¿Tiene el rol?
            SÍ → continuar
            NO → abort(403)
```

---

## 4️⃣ CONTROLADORES: DashboardControllers

### Admin Dashboard

```php
public function index()
{
    $totalUsers = User::count();
    
    $totalVendors = User::whereHas('roles', function ($query) {
        $query->where('slug', 'vendedor');
    })->count();
    
    $totalClients = User::whereHas('roles', function ($query) {
        $query->where('slug', 'cliente');
    })->count();
    
    $roles = Role::all();

    return view('admin.dashboard', compact(
        'totalUsers',
        'totalVendors',
        'totalClients',
        'roles'
    ));
}
```

**¿Qué hace?**

1. Cuenta todos los usuarios
2. Cuenta solo vendedores (usando `whereHas`)
3. Cuenta solo clientes
4. Obtiene todos los roles
5. Envía datos a la vista

**Vista (`admin/dashboard.blade.php`):**

```blade
<div class="grid grid-cols-4 gap-6">
    <!-- Tarjeta de usuarios -->
    <div class="card">
        <p class="text-gray-600">Total de Usuarios</p>
        <p class="text-2xl font-bold">{{ $totalUsers }}</p>
    </div>
    
    <!-- Tarjeta de vendedores -->
    <div class="card">
        <p class="text-gray-600">Vendedores</p>
        <p class="text-2xl font-bold">{{ $totalVendors }}</p>
    </div>
    
    <!-- Tarjeta de clientes -->
    <div class="card">
        <p class="text-gray-600">Clientes</p>
        <p class="text-2xl font-bold">{{ $totalClients }}</p>
    </div>
    
    <!-- Tarjeta de roles -->
    <div class="card">
        <p class="text-gray-600">Roles Disponibles</p>
        <p class="text-2xl font-bold">{{ $roles->count() }}</p>
    </div>
</div>

<!-- Tabla de roles -->
<table>
    @foreach ($roles as $role)
        <tr>
            <td>{{ $role->name }}</td>
            <td>{{ $role->slug }}</td>
            <td>{{ $role->users()->count() }}</td>
        </tr>
    @endforeach
</table>
```

---

## 5️⃣ RUTAS Y SU FLUJO

### Rutas Públicas

```php
Route::get('/', [HomeController::class, 'index'])
    ->name('home');                        // Página inicio
    
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');                       // Formulario login
    
Route::post('/login', [AuthController::class, 'login']);
                                           // Procesar login
Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register');                    // Formulario registro
    
Route::post('/register', [AuthController::class, 'register']);
                                           // Procesar registro
```

**Flujo de Usuario No Autenticado:**

```
Usuario accede a http://localhost:8000/
        ↓
HomeController@index
        ↓
¿Está autenticado?
  NO → mostrar home.blade.php
  SÍ → redirigir al dashboard según rol
```

### Rutas Protegidas

```php
Route::middleware('auth')->group(function () {
    // Super Admin
    Route::middleware('role:super-admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');
        });
    
    // Vendedor
    Route::middleware('role:vendedor')
        ->prefix('seller')
        ->name('seller.')
        ->group(function () {
            Route::get('/dashboard', [SellerDashboardController::class, 'index'])
                ->name('dashboard');
        });
    
    // Cliente
    Route::middleware('role:cliente')
        ->prefix('client')
        ->name('client.')
        ->group(function () {
            Route::get('/dashboard', [ClientDashboardController::class, 'index'])
                ->name('dashboard');
        });
});
```

**Flujo de Acceso a Ruta Protegida:**

```
Solicitud GET /admin/dashboard
        ↓
Middleware: auth
        ↓
¿Está autenticado?
  NO → redirigir a /login
  SÍ → siguiente middleware
        ↓
Middleware: role:super-admin
        ↓
¿Tiene rol super-admin?
  NO → abort(403)
  SÍ → ejecutar controlador
        ↓
AdminDashboardController@index
        ↓
Retornar vista con datos
```

---

## 6️⃣ SIDEBAR DINÁMICO

**Componente:** `components/sidebar.blade.php`

```blade
@php
    $user = Auth::user();
    $role = $user->getPrimaryRole();
    $roleName = $role ? $role->slug : null;
@endphp

<aside class="sidebar">
    <!-- Si es super-admin -->
    @if($roleName === 'super-admin')
        <a href="{{ route('admin.dashboard') }}" 
           class="{{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : '' }}">
            Dashboard
        </a>
        <a href="#">Usuarios</a>
        <a href="#">Roles</a>
        <a href="#">Permisos</a>
    @endif

    <!-- Si es vendedor -->
    @if($roleName === 'vendedor')
        <a href="{{ route('seller.dashboard') }}" 
           class="{{ request()->routeIs('seller.dashboard') ? 'bg-blue-600' : '' }}">
            Dashboard
        </a>
        <a href="#">Mis Productos</a>
        <a href="#">Mis Órdenes</a>
    @endif

    <!-- Si es cliente -->
    @if($roleName === 'cliente')
        <a href="{{ route('client.dashboard') }}" 
           class="{{ request()->routeIs('client.dashboard') ? 'bg-blue-600' : '' }}">
            Dashboard
        </a>
        <a href="#">Catálogo</a>
        <a href="#">Mis Compras</a>
    @endif
</aside>
```

**¿Cómo funciona?**

1. Obtiene el usuario autenticado
2. Obtiene su rol principal
3. Según el rol, muestra diferentes opciones
4. El enlace activo se destaca (clase `bg-blue-600`)

---

## 7️⃣ VALIDACIÓN DE DATOS

### En Formularios

```blade
<!-- Mostrar errores -->
@error('email')
    <p class="text-red-600">{{ $message }}</p>
@enderror

<!-- Persistir valor -->
<input name="email" value="{{ old('email') }}" />
```

### En Controladores

```php
$validated = $request->validate([
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:8|confirmed',
    'name' => 'required|string|max:255',
], [
    'email.required' => 'El email es requerido',
    'email.unique' => 'Este email ya está registrado',
    'password.required' => 'La contraseña es requerida',
    'password.confirmed' => 'Las contraseñas no coinciden',
]);
```

**Validaciones Disponibles:**

```
required       - Campo obligatorio
email          - Debe ser email válido
unique:table   - Debe ser único en tabla
min:8          - Mínimo 8 caracteres
max:255        - Máximo 255 caracteres
confirmed      - Debe coincider con field_confirmation
exists:table   - Debe existir en tabla
```

---

## 8️⃣ SEEDERS Y DATOS DE PRUEBA

### RolePermissionSeeder

```php
// Crear rol
$superAdmin = Role::create([
    'name' => 'Super Admin',
    'slug' => 'super-admin',
    'description' => 'Administrador del sistema',
]);

// Crear permiso
Permission::create([
    'name' => 'Ver Usuarios',
    'slug' => 'view-users',
    'description' => 'Puede ver lista de usuarios',
]);

// Asignar todos los permisos al rol
$allPermissions = Permission::all();
$superAdmin->permissions()->sync($allPermissions->pluck('id'));
```

### UserSeeder

```php
$superAdminRole = Role::where('slug', 'super-admin')->first();

$user = User::create([
    'name' => 'Administrador',
    'email' => 'admin@tienda.com',
    'password' => Hash::make('password'),
    'email_verified_at' => now(),
]);

$user->roles()->attach($superAdminRole);
```

**Ejecutar Seeders:**

```bash
php artisan db:seed                    # Todos
php artisan db:seed --class=RolePermissionSeeder  # Solo roles
php artisan migrate:refresh --seed     # Reiniciar + seed
```

---

## 🎯 FLUJO COMPLETO: UN USUARIO DESDE CERO

```
1. Usuario anónimo en http://localhost:8000
                ↓
   Ve la página pública (home.blade.php)
                ↓
2. Hace click en "Registrarse"
                ↓
   Va a /register
                ↓
3. Completa formulario y envía
                ↓
   POST /register → AuthController@register
                ↓
4. Se validan datos
   - Email único: ✓
   - Contraseña 8+ caracteres: ✓
   - Contraseñas coinciden: ✓
                ↓
5. Se crea usuario en BD
   - name: "Juan González"
   - email: "juan@email.com"
   - password: hash(password)
                ↓
6. Se obtiene rol "cliente" de la BD
                ↓
7. Se asigna rol al usuario
   INSERT role_user(user_id, role_id)
                ↓
8. Se inicia sesión automáticamente
   Auth::login($user)
                ↓
9. Se redirige a /client/dashboard
                ↓
10. ClientDashboardController@index se ejecuta
                ↓
11. Se retorna la vista client/dashboard.blade.php
                ↓
12. Layout app.blade.php se renderiza con:
    - Header dinámico (con nombre del usuario)
    - Sidebar dinámico (menú de cliente)
    - Dashboard con estadísticas
                ↓
13. Usuario ve su dashboard
```

---

## ✅ VERIFICACIÓN FINAL

Para verificar que todo funciona:

```bash
# 1. Iniciar servidor
php artisan serve

# 2. Abrir en navegador
http://localhost:8000/login

# 3. Intentar login con:
Email: admin@tienda.com
Pass: password

# 4. Debería:
✓ Redirigir a /admin/dashboard
✓ Mostrar nombre del usuario en header
✓ Mostrar sidebar con opciones de admin
✓ Mostrar estadísticas en tarjetas
✓ Mostrar tabla de roles
```

---

Este documento proporciona una explicación técnica completa de cómo funciona cada componente del sistema. 🚀

