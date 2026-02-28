# 🛍️ Sistema Completo de Tienda Online y Marketplace

## 📋 Descripción General

Este es un sistema completo de Tienda Online y Marketplace construido con **Laravel 11**, **MySQL**, **Tailwind CSS** y arquitectura modular. El sistema incluye gestión de roles, autenticación segura, dashboards especializados para cada tipo de usuario y una estructura escalable lista para adicionar más características.

---

## ✨ Características Implementadas

### 1. **Sistema de Roles y Permisos**
- ✅ **Super Admin**: Acceso total al sistema, gestión de usuarios, roles y permisos
- ✅ **Vendedor**: Puede crear y gestionar productos, ver órdenes
- ✅ **Cliente**: Puede explorar catálogo y realizar compras

### 2. **Autenticación Completa**
- ✅ Sistema de login seguro
- ✅ Registro de nuevos usuarios
- ✅ Recuperación de contraseña por email
- ✅ Sesiones seguras con CSRF protection
- ✅ Remember me functionality

### 3. **Control de Acceso**
- ✅ Middleware `CheckRole` para verificar roles
- ✅ Middleware `CheckPermission` para verificar permisos
- ✅ Protección de rutas según rol
- ✅ Redirección automática según rol al iniciar sesión

### 4. **Dashboards Especializados**
- ✅ **Dashboard Super Admin**: Estadísticas de usuarios, roles, tabla de roles
- ✅ **Dashboard Vendedor**: Mis productos, órdenes, ingresos, calificación
- ✅ **Dashboard Cliente**: Mis órdenes, gastos totales, carrito

### 5. **Interfaz de Usuario**
- ✅ Layout principal reutilizable con Tailwind CSS
- ✅ Header dinámico con menú de usuario
- ✅ Sidebar dinámico según rol del usuario
- ✅ Página de inicio pública responsiva
- ✅ Alertas de notificación (success/error)

### 6. **Base de Datos**
- ✅ Tablas: `users`, `roles`, `permissions`, `role_user`, `permission_role`
- ✅ Relaciones Many-to-Many configuradas
- ✅ Migraciones limpias y versionadas
- ✅ Seeders con datos de prueba

---

## 🚀 Instalación y Configuración

### Requisitos Previos
- PHP 8.2+
- Composer
- Node.js y npm
- SQLite o MySQL

### Pasos de Instalación

1. **Navegar al directorio del proyecto**
```bash
cd /Users/brandonsantacruz/Desktop/tienda
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de JavaScript**
```bash
npm install
```

4. **Copiar archivo .env**
```bash
cp .env.example .env
```

5. **Generar clave de aplicación**
```bash
php artisan key:generate
```

6. **Ejecutar migraciones y seeders**
```bash
php artisan migrate:refresh --seed
```

7. **Compilar assets**
```bash
npm run dev
```

8. **Iniciar servidor de desarrollo**
```bash
php artisan serve
```

El servidor estará disponible en `http://localhost:8000`

---

## 👤 Usuarios de Prueba

### Super Admin
- **Email**: `admin@tienda.com`
- **Contraseña**: `password`
- **Acceso**: Dashboard de administración completa

### Vendedor
- **Email**: `vendedor@tienda.com`
- **Contraseña**: `password`
- **Acceso**: Dashboard de vendedor

### Cliente
- **Email**: `cliente@tienda.com`
- **Contraseña**: `password`
- **Acceso**: Dashboard de cliente

---

## 📁 Estructura de Directorios

```
tienda/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php          # Controlador de autenticación
│   │   │   ├── Admin/
│   │   │   │   └── DashboardController.php     # Dashboard Super Admin
│   │   │   ├── Seller/
│   │   │   │   └── DashboardController.php     # Dashboard Vendedor
│   │   │   ├── Client/
│   │   │   │   └── DashboardController.php     # Dashboard Cliente
│   │   │   └── HomeController.php              # Controlador de inicio
│   │   └── Middleware/
│   │       ├── CheckRole.php                   # Middleware de verificación de roles
│   │       └── CheckPermission.php             # Middleware de verificación de permisos
│   └── Models/
│       ├── User.php                            # Modelo de Usuario con relaciones
│       ├── Role.php                            # Modelo de Rol
│       └── Permission.php                      # Modelo de Permiso
├── database/
│   ├── migrations/
│   │   ├── *_create_roles_table.php
│   │   ├── *_create_permissions_table.php
│   │   ├── *_create_role_user_table.php
│   │   └── *_create_permission_role_table.php
│   └── seeders/
│       ├── RolePermissionSeeder.php            # Seeder de roles y permisos
│       ├── UserSeeder.php                      # Seeder de usuarios
│       └── DatabaseSeeder.php                  # Seeder principal
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php                   # Layout principal
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   ├── register.blade.php
│   │   │   ├── forgot-password.blade.php
│   │   │   └── reset-password.blade.php
│   │   ├── admin/
│   │   │   └── dashboard.blade.php
│   │   ├── seller/
│   │   │   └── dashboard.blade.php
│   │   ├── client/
│   │   │   └── dashboard.blade.php
│   │   ├── components/
│   │   │   ├── header.blade.php
│   │   │   └── sidebar.blade.php
│   │   └── home.blade.php                      # Página de inicio pública
│   ├── css/
│   │   └── app.css                             # Estilos con Tailwind
│   └── js/
│       └── app.js                              # JavaScript principal
├── routes/
│   ├── web.php                                 # Rutas de la aplicación
│   └── console.php
├── bootstrap/
│   └── app.php                                 # Configuración de middleware
├── .env                                        # Configuración de entorno
└── composer.json                               # Dependencias de PHP

```

---

## 🔐 Relaciones de Base de Datos

### Tabla: `users`
```sql
id (PK), name, email, password, email_verified_at, remember_token, created_at, updated_at
```

### Tabla: `roles`
```sql
id (PK), name, slug, description, created_at, updated_at
```

### Tabla: `permissions`
```sql
id (PK), name, slug, description, created_at, updated_at
```

### Tabla: `role_user` (Pivote)
```sql
id (PK), user_id (FK), role_id (FK), created_at, updated_at
Unique: (user_id, role_id)
```

### Tabla: `permission_role` (Pivote)
```sql
id (PK), role_id (FK), permission_id (FK), created_at, updated_at
Unique: (role_id, permission_id)
```

---

## 🛣️ Rutas de la Aplicación

### Rutas Públicas
```
GET  /                          # Página de inicio pública
GET  /login                     # Formulario de login
POST /login                     # Procesar login
GET  /register                  # Formulario de registro
POST /register                  # Procesar registro
GET  /forgot-password           # Formulario de recuperación
POST /forgot-password           # Enviar link de recuperación
GET  /reset-password/{token}    # Formulario de reset
POST /reset-password            # Procesar reset
```

### Rutas Protegidas (Autenticadas)
```
POST /logout                    # Cerrar sesión

# Super Admin (role:super-admin)
GET  /admin/dashboard           # Dashboard del administrador

# Vendedor (role:vendedor)
GET  /seller/dashboard          # Dashboard del vendedor

# Cliente (role:cliente)
GET  /client/dashboard          # Dashboard del cliente

# Genérico
GET  /dashboard                 # Redirige según rol
```

---

## 🔑 Métodos del Modelo User

### Relaciones
```php
$user->roles()                  # Obtiene todos los roles
$user->permissions()            # Obtiene todos los permisos
$user->getPrimaryRole()         # Obtiene el rol principal
```

### Verificaciones
```php
$user->hasRole('super-admin')           # ¿Tiene este rol?
$user->hasAnyRole(['admin', 'seller'])  # ¿Tiene alguno de estos?
$user->hasAllRoles(['admin', 'seller']) # ¿Tiene todos estos?
$user->hasPermission('delete-user')     # ¿Tiene este permiso?
```

---

## 🔐 Middleware Disponible

### CheckRole
```php
// En rutas
Route::middleware('role:super-admin')->group(function () {
    // Rutas protegidas por rol
});

// En controladores
public function __construct()
{
    $this->middleware('role:vendedor,cliente');
}
```

### CheckPermission
```php
// En rutas
Route::middleware('permission:delete-user,edit-user')->group(function () {
    // Rutas protegidas por permisos
});

// En controladores
public function __construct()
{
    $this->middleware('permission:create-product');
}
```

---

## 🎨 Personalización de Vistas

### Layout Principal (`layouts/app.blade.php`)
- Estructura base con sidebar y header
- Zona de alertas flash
- Área principal de contenido
- Responsive design

### Componentes
- **Header**: Información del usuario, dropdown de perfil, logout
- **Sidebar**: Navegación dinámica según rol, enlaces contextuales

---

## 📊 Roles y Permisos Predefinidos

### Super Admin (20 permisos)
- Gestión completa de usuarios, roles, permisos
- Visualización de todas las órdenes
- Acceso a reportes del sistema

### Vendedor (6 permisos)
- Ver, crear, editar y eliminar sus productos
- Ver sus órdenes
- Acceso a sus reportes de ventas

### Cliente (4 permisos)
- Ver catálogo de productos
- Crear órdenes
- Ver sus compras
- Calificar productos

---

## 🚀 Próximas Características Recomendadas

1. **Gestión de Productos**
   - Modelo Product
   - CRUD de productos
   - Categorías y subcategorías
   - Galería de imágenes

2. **Órdenes y Carrito**
   - Carrito de compras
   - Sistema de órdenes
   - Estados de órdenes
   - Historial de compras

3. **Pagos**
   - Integración con Stripe/PayPal
   - Gestión de transacciones
   - Facturación

4. **Notificaciones**
   - Email notifications
   - Sistema de notificaciones en tiempo real
   - Alertas de órdenes

5. **Reportes**
   - Reportes de ventas
   - Análisis de productos
   - Estadísticas de usuarios

6. **Búsqueda y Filtros**
   - Búsqueda de productos
   - Filtros avanzados
   - Paginación

---

## 🐛 Troubleshooting

### Error: "Tabla no existe"
```bash
php artisan migrate:refresh --seed
```

### Error: "CSRF Token Mismatch"
Asegúrate de que `@csrf` está en todos los formularios POST.

### Error: "Whoops! No query results"
Ejecuta nuevamente los seeders:
```bash
php artisan db:seed
```

### La aplicación es lenta
Ejecuta:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📝 Notas Importantes

1. **Base de Datos**: El proyecto usa SQLite por defecto. Para MySQL, actualiza el archivo `.env`

2. **Seguridad**: 
   - Las contraseñas se hashean automáticamente
   - CSRF protection habilitado
   - Validación de entrada en todos los formularios

3. **Desarrollo**:
   - Para desarrollo, ejecuta `npm run dev`
   - Para producción, ejecuta `npm run build`

4. **Email**: Las funciones de email están comentadas. Configura `.env` con tu proveedor SMTP

---

## 👨‍💻 Soporte y Contribuciones

Para reportar bugs o sugerir mejoras, contacta al equipo de desarrollo.

---

## 📄 Licencia

Este proyecto está bajo licencia MIT.

---

**Versión**: 1.0.0  
**Última actualización**: 28 de Febrero, 2026  
**Estado**: ✅ Listo para Desarrollo

