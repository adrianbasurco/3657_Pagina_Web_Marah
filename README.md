# Marah – Sitio web de venta de kéfir

Sitio web de Marah, emprendimiento de kéfir de leche artesanal. Incluye una tienda pública, un panel de administración con inicio de sesión y una API REST en PHP + MySQL.

Curso: Programación Web Básica · ISIL · Periodo 2026-20
Alumno: Adrian Basurco

## Funcionalidades

- **Tienda (`index.html`):** catálogo y buscador con filtros (nombre, categoría, stock y rango de precio) cargados desde la base de datos.
- **Panel de administración (`admin.html`):** crear, editar y eliminar productos (CRUD completo).
- **Inicio de sesión (`login.html`):** solo un usuario autenticado puede entrar al panel y modificar productos.
- **API REST:** responde en JSON y protege con sesión los endpoints que modifican datos.

## Tecnologías

| Capa | Tecnología |
|---|---|
| Presentación | HTML, Tailwind CSS 3, JavaScript (`fetch`) |
| Servicio | PHP 8.1 (POO, MVC simple, Composer con autoload PSR-4) |
| Datos | MySQL 8.0 con PDO, procedimientos almacenados |
| Seguridad | `password_hash` / `password_verify`, sesiones de PHP |

## Requisitos

- PHP 8.1 o superior
- [Composer](https://getcomposer.org/)
- MySQL 8.0
- Apache con `mod_rewrite` activo (por ejemplo, XAMPP)
- Node.js 18 o superior (solo para compilar el CSS de Tailwind)

## Instalación

1. **Clonar el proyecto** dentro de `htdocs`:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/adrianbasurco/3657_Pagina_Web_Marah.git marah
   cd marah
   ```

2. **Instalar dependencias de PHP** (genera la carpeta `vendor/` y el autoload):
   ```bash
   composer install
   ```

3. **Crear la base de datos:** ejecutar `PA3_Marah_BD.sql` en MySQL Workbench.
   ⚠️ El script elimina `marah_db` si ya existe y la crea de nuevo.

4. **Crear la tabla de usuarios:** ejecutar `usuarios.sql` en MySQL Workbench. Crea la tabla `usuarios` y el procedimiento `sp_validar_usuario`.

5. **Crear el usuario administrador:**
   1. Generar el hash de la contraseña (en Windows con XAMPP):
      ```bash
      C:\xampp\php\php.exe -r "echo password_hash('tu_clave', PASSWORD_DEFAULT);"
      ```
   2. Insertar el usuario en MySQL con el hash generado:
      ```sql
      INSERT INTO usuarios (nombre, correo, password)
      VALUES ('Nombre', 'correo@ejemplo.com', 'HASH_GENERADO');
      ```
   ⚠️ Nunca guardes la contraseña en texto plano ni la escribas en archivos del proyecto.

6. **Configurar la conexión:** copiar `config/env.example.php` como `config/env.php` y completar los datos:
   ```php
   'DB_USER' => 'root',
   'DB_PASS' => 'tu_contraseña',
   ```
   `env.php` está en `.gitignore`: nunca se sube al repositorio.

7. **Compilar el CSS** (solo si se modifican clases de Tailwind):
   ```bash
   npm install
   npm run build
   ```
   Mientras se desarrolla, `npm run dev` regenera el CSS automáticamente al guardar.

## Páginas

| Página | URL | Acceso |
|---|---|---|
| Tienda | `http://localhost/marah/public/` | Público |
| Iniciar sesión | `http://localhost/marah/public/login.html` | Público |
| Panel de administración | `http://localhost/marah/public/admin.html` | 🔐 Requiere sesión |

## Endpoints de la API

Base: `http://localhost/marah/public/api`

| Método | Ruta | Descripción | Acceso |
|---|---|---|---|
| GET | `/productos` | Lista los productos activos con su categoría | Público |
| GET | `/productos/{id}` | Obtiene un producto por su id | Público |
| POST | `/productos` | Crea un producto nuevo | 🔐 |
| PUT | `/productos/{id}` | Actualiza todos los datos de un producto | 🔐 |
| DELETE | `/productos/{id}` | Desactiva un producto (borrado lógico) | 🔐 |
| GET | `/categorias` | Lista las categorías activas | Público |
| GET | `/clientes` | Lista los clientes | 🔐 |
| POST | `/login` | Inicia sesión con `correo` y `password` | Público |
| POST | `/logout` | Cierra la sesión | Público |
| GET | `/sesion` | Indica si hay una sesión activa y el nombre del usuario | Público |

🔐 = requiere haber iniciado sesión. Sin sesión, la API responde con **401**.

### Formato de respuesta

Todas las respuestas tienen la misma forma:

```json
{
  "success": true,
  "message": "Productos obtenidos correctamente",
  "data": [ ... ]
}
```

Si ocurre un error, `success` es `false`, no hay `data` y se devuelve el código HTTP correspondiente:

| Código | Significado |
|---|---|
| 200 | Operación exitosa |
| 201 | Recurso creado (POST) |
| 400 | Datos enviados incorrectos o incompletos |
| 401 | No autenticado o credenciales incorrectas |
| 404 | Recurso o ruta no encontrados |
| 500 | Error del servidor (por ejemplo, falla la conexión a la BD) |

## Estructura del proyecto

```
├── app/
│   ├── Controllers/        # Reciben la petición, validan y responden
│   ├── Middleware/         # CORS y AuthMiddleware (protección con sesión)
│   └── Models/             # Consultas SQL y llamadas a procedimientos
├── config/                 # Conexión a la BD (env.php, no se sube)
├── core/                   # Router, Request, Response
├── public/                 # Raíz web: todo lo que ve el navegador
│   ├── index.php           # Punto de entrada de la API
│   ├── .htaccess           # Redirige /api/... a index.php
│   ├── index.html          # Tienda
│   ├── login.html          # Inicio de sesión
│   ├── admin.html          # Panel de administración
│   ├── js/                 # app.js, login.js, admin.js
│   ├── css/output.css      # CSS generado por Tailwind
│   └── img/
├── routes/api.php          # Definición de rutas
├── src/styles.css          # Entrada de Tailwind (@tailwind + @apply)
├── PA3_Marah_BD.sql        # Script de la base de datos (PA3)
└── usuarios.sql            # Tabla de usuarios y sp_validar_usuario
```

## Problemas comunes

- **"Ruta no encontrada" en todas las URLs:** revisar que en `httpd.conf` de Apache esté `AllowOverride All`.
- **"Error al conectar con la base de datos":** revisar los datos de `config/env.php` y que MySQL esté encendido.
- **Error con `vendor/autoload.php`:** falta ejecutar `composer install`.
- **La página se ve sin estilos o con imágenes gigantes:** faltan clases en el CSS; ejecutar `npm run build` y recargar con Ctrl + F5.
- **La API dice "Faltan datos obligatorios" aunque se enviaron:** el JSON está mal escrito (por ejemplo, una coma al final o una clave sin valor).
- **"Credenciales incorrectas" con la contraseña correcta:** revisar que el `INSERT` del usuario use el hash generado con `password_hash`, no la contraseña en texto plano.