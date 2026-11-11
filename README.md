# Marah – Sitio web de venta de kéfir

Sitio web de Marah: mockup en HTML + Tailwind y API REST en PHP + MySQL.

Curso: Programación Web Básica · Periodo 2026-20

## Mockup (HTML + Tailwind) – PA2

### Requisitos
- Node.js 18 o superior (incluye npm)

### Instalación y ejecución
```bash
# 1. Instalar dependencias (Tailwind CSS)
npm install

# 2. Generar el CSS (crea dist/output.css)
npm run build

# 3. Abrir el mockup en el navegador
#    Abrir el archivo src/index.html
```

Si haces cambios y quieres que se actualice solo:
```bash
npm run dev
```

### Estructura del proyecto
```
marah-mockup/
├── package.json          # Dependencias y scripts (build, dev)
├── tailwind.config.js    # Configuración de Tailwind (content, colores de marca, tipografía)
├── src/
│   ├── index.html        # Página (header, hero, secciones, footer)
│   └── styles.css        # Entrada de Tailwind (@tailwind + componentes con @apply)
└── dist/
    └── output.css        # CSS generado por "npm run build"
```

### Notas
- El mockup es estático: no integra PHP, MySQL ni APIs. El buscador y el formulario son maquetas visuales.
- Todo el estilo se hace con clases utilitarias de Tailwind; no se usan estilos inline.
- Es responsivo mediante prefijos `sm:`, `md:` y `lg:`.
- Alcance completo del proyecto: ver el documento de alcance incluido.
- La API se documenta en la sección de abajo.

## API REST (PHP + MySQL) – PA3

API que expone los datos de la base de datos `marah_db` en formato JSON.

### Requisitos
- PHP 8.1 o superior
- [Composer](https://getcomposer.org/)
- MySQL 8.0
- Apache con `mod_rewrite` activo (por ejemplo, XAMPP)

### Instalación

1. **Clonar el proyecto** dentro de `htdocs`:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/adrianbasurco/3657_Pagina_Web_Marah.git marah
   ```

2. **Instalar dependencias** (genera la carpeta `vendor/` y el autoload):
   ```bash
   cd marah
   composer install
   ```

3. **Crear la base de datos:** ejecutar el script `PA3_Marah_BD.sql` en MySQL Workbench.
   ⚠️ El script elimina `marah_db` si ya existe y la crea de nuevo.

4. **Configurar la conexión:** copiar `config/env.example.php` como `config/env.php` y completar los datos:
   ```php
   'DB_USER' => 'root',
   'DB_PASS' => 'tu_contraseña',
   ```
   `env.php` está en `.gitignore`: nunca se sube al repositorio.

5. **Probar:** abrir en el navegador o en Postman:
   ```
   http://localhost/marah/public/api/clientes
   ```

### Endpoints

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/api/clientes` | Lista todos los clientes |

### Formato de respuesta

Todas las respuestas tienen la misma forma:

```json
{
  "success": true,
  "message": "Clientes obtenidos correctamente",
  "data": [ ... ]
}
```

Si ocurre un error, `success` es `false`, no hay `data` y se devuelve el código HTTP correspondiente (por ejemplo, 500 si falla la conexión a la base de datos).

### Estructura

```
├── app/
│   ├── Controllers/   # Reciben la petición y llaman al modelo
│   ├── Middleware/    # CORS
│   └── Models/        # Consultas SQL
├── config/            # Conexión a la BD (env.php)
├── core/              # Router, Request, Response
├── public/            # Punto de entrada (index.php + .htaccess)
├── routes/api.php     # Definición de rutas
└── PA3_Marah_BD.sql   # Script de la base de datos
```

### Problemas comunes
- **"Ruta no encontrada" en todas las URLs:** revisar que en `httpd.conf` de Apache esté `AllowOverride All`.
- **"Error al conectar con la base de datos":** revisar los datos de `config/env.php` y que MySQL esté encendido.
- **Error con `vendor/autoload.php`:** falta ejecutar `composer install`.