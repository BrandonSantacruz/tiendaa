# Módulo Marketplace - Documentación Completa

## 📋 Descripción

El módulo Marketplace implementa un sistema completo de gestión de vendedores/proveedores con:
- Registro y aprobación de vendedores
- Gestión de comisiones por ventas
- Seguimiento de pagos
- Panel de control para vendedores
- Interfaz de administración para aprobaciones

## 🏗️ Arquitectura

### Modelos
- **User** - Extendido con campos marketplace (seller_status, commission_rate, etc.)
- **Commission** - Registra cada comisión por venta
- **SellerPayout** - Agrupa comisiones en pagos procesables

### Estados del Vendedor
```
pending  → Solicitud pendiente de aprobación
approved → Vendedor activo y autorizado
rejected → Solicitud rechazada
suspended → Vendedor suspendido temporalmente
```

### Estados de Comisiones
```
pending   → Pendiente de aprobación
approved  → Aprobada, lista para pago
paid      → Ya pagada al vendedor
refunded  → Reembolsada
```

## 📦 Instalación

### 1. Migraciones
```bash
php artisan migrate
```

Crea 3 nuevas tablas:
- `add_marketplace_fields_to_users` - Campos adicionales en usuarios
- `commissions` - Registro de comisiones
- `seller_payouts` - Registro de pagos

### 2. Seeders
```bash
php artisan db:seed --class=UserSeeder
```

Crea vendedores de prueba:
- Juan Vendedor (approved)
- María García (approved)
- Carlos López (pending)
- Ana Rodríguez (rejected)

## 🚀 Rutas

### Rutas Públicas
```
GET    /seller/register                    - Formulario de registro
POST   /seller/register                    - Procesar registro
GET    /seller/registration-pending        - Página de solicitud pendiente
GET    /seller/registration/status         - API: verificar estado (AJAX)
```

### Rutas de Administrador (Super Admin)
```
GET    /admin/sellers                      - Listar vendedores
GET    /admin/sellers/{id}                 - Ver detalles del vendedor
PATCH  /admin/sellers/{id}/approve         - Aprobar vendedor
PATCH  /admin/sellers/{id}/reject          - Rechazar vendedor
PATCH  /admin/sellers/{id}/suspend         - Suspender vendedor
PATCH  /admin/sellers/{id}/reactivate      - Reactivar vendedor suspendido
PATCH  /admin/sellers/{id}/commission      - Actualizar tasa de comisión
```

### Rutas de Vendedor (Aprobados)
```
GET    /seller/dashboard                   - Panel principal
GET    /seller/commissions                 - Listar comisiones
GET    /seller/commissions/{id}            - Detalles de comisión
GET    /seller/payouts                     - Listar pagos
GET    /seller/payouts/{id}                - Detalles de pago
GET    /seller/profile                     - Perfil del vendedor
PATCH  /seller/profile                     - Actualizar perfil
GET    /seller/products                    - Gestionar productos
POST   /seller/products                    - Crear producto
```

## 🎯 Controladores

### SellerRegistrationController
Maneja el registro de nuevos vendedores:
- `showRegistrationForm()` - Mostrar formulario
- `register()` - Procesar registro y validación
- `showPending()` - Página de espera
- `checkStatus()` - Verificar estado (AJAX)

### SellerApprovalController
Aprobación y gestión de vendedores por admin:
- `index()` - Listar vendedores por estado
- `show()` - Ver detalles del vendedor
- `approve()` - Aprobar vendedor
- `reject()` - Rechazar con motivo
- `suspend()` - Suspender vendedor
- `reactivate()` - Reactivar suspendido
- `updateCommission()` - Cambiar % comisión

### SellerDashboardController
Panel de vendedor:
- `index()` - Panel principal con estadísticas
- `commissions()` - Listar comisiones con filtros
- `payouts()` - Listar pagos con filtros
- `profile()` - Ver perfil
- `updateProfile()` - Actualizar información
- `getMonthlyEarningsData()` - Datos para gráficos

## 📊 Vistas

### Públicas
- `seller/register.blade.php` - Formulario de registro
- `seller/registration-pending.blade.php` - Página de espera

### Administrador
- `admin/sellers/index.blade.php` - Gestión de vendedores
- `admin/sellers/show.blade.php` - Detalles del vendedor

### Vendedor
- `seller/dashboard.blade.php` - Panel principal
- `seller/commissions.blade.php` - Listado de comisiones
- `seller/payouts.blade.php` - Listado de pagos

## 🔒 Permisos y Roles

### Super Admin
- Acceso a gestión de vendedores
- Aprobar/rechazar/suspender
- Modificar tasas de comisión

### Vendedor (Aprobado)
- Acceso a panel personal
- Ver comisiones y pagos
- Gestionar productos
- Editar perfil

### Cliente
- Sin acceso al módulo marketplace

## 💰 Cálculo de Comisiones

La comisión se calcula automáticamente:

```
Comisión = Venta × (Tasa de Comisión / 100)

Ejemplo:
- Venta: $100
- Tasa: 10%
- Comisión: $10
```

### Proceso de Aprobación
1. Venta se registra con estado `pending`
2. Admin aprueba → estado `approved`
3. Sistema procesa pago → estado `paid`

## 📈 Flujo de Registro de Vendedor

```
1. Usuario accede a /seller/register
2. Completa formulario con datos personales y empresa
3. Sistema valida información
4. Se crea usuario con estado seller_status = 'pending'
5. Usuario ve página de espera
6. Admin recibe solicitud en /admin/sellers
7. Admin aprueba o rechaza
8. Usuario recibe notificación (por email)
9. Si aprobado → Acceso a /seller/dashboard
10. Si rechazado → Puede reintentarlo
```

## 🔧 Configuración

### Campos Agregados a User
```php
'seller_status'           => pending|approved|rejected|suspended
'seller_rejection_reason' => string|null
'seller_approved_at'      => timestamp|null
'commission_rate'         => decimal(5,2) - default 10%
'phone'                   => string
'company_name'            => string
'company_description'     => text
'company_logo'            => string|null (path)
'tax_id'                  => string (unique)
'address'                 => string
'city'                    => string
'country'                 => string
'postal_code'             => string
```

## 📱 Endpoints JSON (AJAX)

### Verificar Estado de Solicitud
```
GET /seller/registration/status

Response:
{
  "seller_status": "pending|approved|rejected",
  "is_approved": boolean,
  "is_rejected": boolean,
  "rejection_reason": string|null,
  "approved_at": datetime|null
}
```

## 🧪 Datos de Prueba

### Usuario Vendedor Aprobado
- Email: `juan@vendedor.com`
- Password: `password`
- Estado: Aprobado
- Comisión: 10%

### Usuario Admin
- Email: `admin@tienda.com`
- Password: `password`

## 🐛 Troubleshooting

### El vendedor no puede acceder a su panel
- Verificar que `seller_status = 'approved'`
- Verificar que tiene el rol `vendedor`
- Verificar middleware `can:access-seller-dashboard`

### Las comisiones no aparecen
- Verificar que existan registros en tabla `commissions`
- Verificar relación en modelo User y Commission

### Los pagos no se procesan
- Verificar estado de comisiones aprobadas
- Implementar jobsmensaje para procesamiento automático

## 🚀 Próximas Características

- [ ] Notificaciones por email para vendedores
- [ ] Generación automática de pagos
- [ ] Reportes y análisis avanzados
- [ ] Sistema de calificaciones de vendedores
- [ ] Validación de documentos de impuestos
- [ ] Integración con pasarelas de pago

## 📝 Notas Importantes

1. **Tasa de Comisión por Defecto**: 10%
2. **Validación de Tax ID**: Debe ser único y alfanumérico
3. **Logo Mínimo**: 200x200px
4. **Estados Mutables**: pending → approved/rejected, approved → suspended, suspended → approved
5. **Historial**: Todas las acciones quedan registradas con timestamps

## 📞 Soporte

Para preguntas o problemas, revisa:
- Migrations en `/database/migrations/`
- Models en `/app/Models/`
- Controllers en `/app/Http/Controllers/`
- Views en `/resources/views/seller/` y `/resources/views/admin/sellers/`
