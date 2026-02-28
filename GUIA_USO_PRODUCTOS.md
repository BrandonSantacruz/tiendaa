# 📖 GUÍA DE USO - MÓDULO DE PRODUCTOS

## 🎯 Objetivo

Esta guía te mostrará cómo usar el módulo de productos paso a paso, tanto para vendedores como para administradores.

---

## 👥 USUARIOS DE PRUEBA

### 1. **Super Admin** (Gestiona categorías)
```
Email: admin@tienda.com
Contraseña: password
Rol: super-admin
```

### 2. **Vendedor** (Gestiona sus productos)
```
Email: vendedor@tienda.com
Contraseña: password
Rol: vendedor
```

### 3. **Cliente** (Navega productos)
```
Email: cliente@tienda.com
Contraseña: password
Rol: cliente
```

---

## 🔐 CÓMO INICIAR SESIÓN

1. Accede a `http://localhost:8000`
2. Haz clic en "Iniciar Sesión"
3. Ingresa email y contraseña
4. Se redirigirá automáticamente a tu dashboard

---

## 📦 PARA VENDEDORES - GESTIÓN DE PRODUCTOS

### CREAR UN PRODUCTO

#### Paso 1: Acceder al listado

1. Inicia sesión como vendedor
2. Verás automáticamente tu dashboard
3. En el menú lateral, ve a **"Mis Productos"** o accede a `/seller/products`

#### Paso 2: Haz clic en "Nuevo Producto"

![Botón azul en la esquina superior derecha]

#### Paso 3: Completa el formulario

**Sección 1: Información Básica**

```
Nombre del Producto *
  └─ Ej: "iPhone 15 Pro Max"

SKU *
  └─ Código único (Ej: "IPHONE-15-PRO")

Categoría *
  └─ Selecciona de la lista

Tipo *
  └─ Simple: 1 producto, 1 precio
  └─ Variable: Múltiples colores/tallas/etc.

Descripción *
  └─ Detalles del producto
```

**Sección 2: Precios e Inventario**

```
Precio Público * ($)
  └─ Precio que ven los clientes
  └─ Ej: 1299.99

Precio Mayorista ($)
  └─ Opcional - Para compras en mayor cantidad
  └─ Ej: 1050.00

Stock *
  └─ Cantidad disponible
  └─ Ej: 15
```

**Sección 3: Imágenes**

```
1. Arrastra imágenes al área punteada
   O haz clic para seleccionar archivos

2. Soporta: JPG, PNG, GIF, WebP
3. Máximo 2MB por imagen
4. Se guardarán en orden (primera = principal)

Verás preview de las imágenes antes de guardar
```

**Sección 4: Estado**

```
Estado del Producto *
  └─ Activo: Visible en la tienda
  └─ Inactivo: Oculto (no aparece)
```

#### Paso 4: Guardar

Haz clic en el botón azul **"Crear Producto"**

✅ Listo! El producto aparecerá en tu listado

---

### EDITAR UN PRODUCTO

1. Ve a tu listado de productos (`/seller/products`)
2. Busca el producto en la tabla
3. Haz clic en el **ícono de lápiz** (Editar)
4. Modifica los campos que desees
5. Para cambiar imágenes:
   - Verás las imágenes actuales
   - Para eliminar una: pasa el mouse y haz clic en la X
   - Para agregar nuevas: arrastra en el área inferior
6. Haz clic en **"Guardar Cambios"**

---

### VER DETALLES DE UN PRODUCTO

1. En el listado, haz clic en el **ícono de ojo** (Ver)
2. Verás:
   - Galería de imágenes completa
   - Nombre y estado
   - SKU y categoría
   - Precios (público y mayorista)
   - Stock disponible
   - Descripción completa
   - **Listado de variantes** (si es variable)

---

### ELIMINAR UN PRODUCTO

⚠️ **Acción irreversible**

1. En el listado, haz clic en el **ícono de papelera** (Eliminar)
2. Confirma en el diálogo
3. Se eliminará junto con:
   - Todas sus imágenes
   - Todas sus variantes
   - Todo su historial

---

## 🎨 VARIANTES - PRODUCTOS VARIABLES

### ¿QUÉ SON LAS VARIANTES?

Una variante es una versión diferente del mismo producto.

**Ejemplo**: iPhone 15 puede tener variantes:
- Negro Titánico - 256GB ($1,299)
- Plata Blanca - 256GB ($1,299)
- Oro Rosa - 512GB ($1,399)

Cada variante puede tener:
- ✓ Precio diferente
- ✓ Stock independiente
- ✓ Atributos (color, talla, etc)
- ✓ Imagen propia

### CREAR UNA VARIANTE

#### Paso 1: Crear producto variable

Al crear un producto, selecciona **"Variable (con variantes)"** como tipo

#### Paso 2: Acceder a variantes

En la vista del producto, verás la sección **"Variantes"**

Haz clic en **"Nueva Variante"**

#### Paso 3: Completar formulario

**Información Básica**

```
Nombre de la Variante *
  └─ Ej: "Rojo - Talla M"
  └─ Describe la variante claramente

SKU *
  └─ Único para cada variante
  └─ Ej: "POLERA-RJ-M"
```

**Atributos**

```
1. Haz clic en "Agregar Atributo"

2. Para cada atributo, completa:
   - Nombre: Ej "Color"
   - Valor: Ej "Rojo"

3. Agrega todos los atributos necesarios:
   - Color
   - Talla
   - Tamaño
   - Material
   - etc.

4. El botón X elimina un atributo
```

**Precios**

```
Precio * ($)
  └─ Precio específico de esta variante
  └─ Ej: 29.99

Precio Mayorista ($)
  └─ Opcional
```

**Stock**

```
Cantidad en Stock *
  └─ Stock específico de esta variante
  └─ Ej: 20
```

**Imagen (Opcional)**

```
Arrastra una imagen específica para esta variante
O deja en blanco para usar la del producto
```

**Estado**

```
Activo: Disponible para compra
Inactivo: Oculta esta variante
```

#### Paso 4: Guardar

Haz clic en **"Crear Variante"**

✅ Aparecerá en el listado de variantes

---

### EDITAR UNA VARIANTE

1. Ve al listado de variantes del producto
2. Haz clic en el **ícono de lápiz**
3. Modifica los campos deseados
4. Haz clic en **"Guardar Cambios"**

---

### ELIMINAR UNA VARIANTE

1. En el listado de variantes, haz clic en el **ícono de papelera**
2. Confirma la eliminación
3. Se eliminará la variante (el producto permanece)

---

## 🏛️ PARA SUPER ADMIN - GESTIÓN DE CATEGORÍAS

### CREAR UNA CATEGORÍA

#### Paso 1: Acceder

1. Inicia sesión como super admin
2. Ve a `/admin/categories`

#### Paso 2: Haz clic en "Nueva Categoría"

#### Paso 3: Completa el formulario

```
Nombre *
  └─ Ej: "Electrónica"

Descripción
  └─ Ej: "Dispositivos electrónicos y accesorios"

Categoría Padre
  └─ Déjalo en blanco para crear principal
  └─ Selecciona una para crear subcategoría
  └─ Ej: "Electrónica" para crear "Teléfonos"

Estado *
  └─ Activa / Inactiva

Imagen
  └─ Logo o portada de la categoría
```

#### Paso 4: Guardar

Haz clic en **"Crear Categoría"**

---

### SUBCATEGORÍAS

Para crear una subcategoría:

1. Completa el formulario normal
2. En **"Categoría Padre"**, selecciona la principal
3. Ejemplo:
   - Padre: "Electrónica"
   - Nueva: "Teléfonos"
   - Resultado: Electrónica → Teléfonos

---

### EDITAR CATEGORÍA

1. En el listado, haz clic en el **lápiz**
2. Modifica los campos
3. Haz clic en **"Guardar"**

---

### ELIMINAR CATEGORÍA

⚠️ **Solo puedes eliminar si no tiene productos**

1. Haz clic en la **papelera**
2. Si hay productos: verás error
   - Primero cambia los productos a otra categoría
   - Luego intenta eliminar nuevamente

---

## 🔍 CARACTERÍSTICAS DE INTERFAZ

### Tabla de Productos

| Columna | Descripción |
|---------|------------|
| **Producto** | Nombre + imagen miniatura |
| **SKU** | Código único |
| **Categoría** | A qué categoría pertenece |
| **Precio** | Precio público |
| **Stock** | Verde si hay, Rojo si no |
| **Estado** | Activo/Inactivo |
| **Acciones** | Ver, Editar, Eliminar |

### Búsqueda y Filtrado

Próximamente:
- [ ] Buscar por nombre
- [ ] Filtrar por categoría
- [ ] Filtrar por precio
- [ ] Ordenar por fecha, precio, stock

---

## 📸 MANEJO DE IMÁGENES

### Cómo subir imágenes

**Opción 1: Drag & Drop**
1. Selecciona imágenes del explorador
2. Arrastra y suelta en el área punteada
3. Se cargarán automáticamente

**Opción 2: Click para seleccionar**
1. Haz clic en el área punteada
2. Se abre el explorador de archivos
3. Selecciona las imágenes que desees

### Características

✓ Soporta múltiples imágenes  
✓ Preview antes de guardar  
✓ Máximo 2MB por imagen  
✓ Formatos: JPG, PNG, GIF, WebP  
✓ Primera imagen = imagen principal  
✓ Puedes eliminar imágenes individuales  

### Después de subir

- Las imágenes se guardan en `storage/public/products/`
- Se crean registros en la tabla `product_images`
- Cada imagen tiene alt-text para accesibilidad
- Puedes reordenarlas (próximamente)

---

## 🚀 FLUJOS COMPLETOS

### Flujo Completo: Vender un Producto Nuevo

```
1. PREPARACIÓN
   └─ Foto del producto (JPG/PNG)
   └─ Descripción
   └─ Precio
   └─ Cantidad en stock

2. CREAR PRODUCTO
   └─ Inicia sesión como vendedor
   └─ Ve a "Mis Productos"
   └─ Haz clic en "Nuevo Producto"
   └─ Completa formulario
   └─ Sube imágenes
   └─ Haz clic en "Crear"

3. RESULTADO
   └─ Producto visible en tu listado
   └─ Clientes pueden verlo en la tienda
   └─ Puedes editar en cualquier momento

4. VARIANTES (si aplica)
   └─ Ve a detalles del producto
   └─ Haz clic en "Nueva Variante"
   └─ Agrega cada variante
   └─ Define atributos (color, talla, etc)
```

### Flujo Completo: Organizar Categorías

```
1. CREAR PRINCIPALES
   └─ Inicia sesión como super admin
   └─ Ve a "Categorías"
   └─ Crea "Electrónica", "Ropa", etc.

2. CREAR SUBCATEGORÍAS
   └─ Para "Electrónica" crea:
   └─ "Teléfonos", "Laptops", "Tablets"

3. ASIGNAR PRODUCTOS
   └─ Vendedores seleccionan categoría al crear

4. RESULTADO
   └─ Categorías bien organizadas
   └─ Clientes pueden filtrar por categoría
```

---

## ⚠️ ERRORES COMUNES Y SOLUCIONES

### Error: "SKU ya existe"
**Problema**: Intentas usar un SKU que ya está en uso  
**Solución**: Cambia el SKU a uno único

### Error: "No puedes eliminar, hay productos"
**Problema**: Intentas eliminar una categoría con productos  
**Solución**: Cambia esos productos a otra categoría primero

### Error: "Archivo muy grande"
**Problema**: La imagen supera 2MB  
**Solución**: Comprime la imagen antes de subir

### Error: "Formato no soportado"
**Problema**: Intentas subir un formato incorrecto  
**Solución**: Usa JPG, PNG, GIF o WebP

### Las imágenes no aparecen
**Problema**: Storage link no configurado  
**Solución**: Ejecuta `php artisan storage:link`

---

## 💡 TIPS Y TRUCOS

✓ **SKU único**: Usa un formato consistente (PROD-001, PROD-002)  
✓ **Descripción clara**: Ayuda a clientes a entender el producto  
✓ **Imágenes de calidad**: Sube en alta resolución  
✓ **Stock actualizado**: Mantén los números precisos  
✓ **Precios competitivos**: Revisa precios mayoristas  
✓ **Variantes bien etiquetadas**: Describe claramente (color, talla)  
✓ **Categoría correcta**: Ayuda a clientes a encontrarlo  

---

## 🎓 PRÓXIMAS CARACTERÍSTICAS

En desarrollo:
- [ ] Búsqueda avanzada
- [ ] Filtros por atributos
- [ ] Reorder de imágenes
- [ ] Reseñas y calificaciones
- [ ] Historial de precios
- [ ] Exportar productos a CSV
- [ ] Importar productos masivo
- [ ] Multi-idioma

---

## 📞 AYUDA

**¿Dudas sobre la interfaz?**
- Pasa el mouse sobre cualquier campo para ver ayuda

**¿Problemas técnicos?**
- Consulta `MODULO_PRODUCTOS.md` para detalles técnicos
- Revisa `EXPLICACION_TECNICA.md` para arquitectura

**¿Reportar un bug?**
- Crea un issue en GitHub
- Describe el problema paso a paso

---

## ✅ CHECKLIST RÁPIDO

Antes de publicar un producto, verifica:

- [x] Nombre claro y descriptivo
- [x] SKU único y consistente
- [x] Descripción completa
- [x] Categoría correcta
- [x] Precio apropiado
- [x] Stock preciso
- [x] Imágenes de calidad
- [x] Estado: Activo
- [x] Variantes correctas (si aplica)

---

¡Listo para vender! 🚀

