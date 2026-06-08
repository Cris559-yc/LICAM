# LICAM - Línea Ciudadana de Atención Municipal

Plataforma web de reportes ciudadanos para la **Alcaldía Municipal de San Jorge**. Permite a los ciudadanos reportar problemas urbanos (calles dañadas, alumbrado público, basura, fugas de agua, entre otros) adjuntando evidencia fotográfica y la ubicación exacta en un mapa, mientras que la administración municipal recibe, clasifica, prioriza y da seguimiento a la resolución de cada reporte, manteniendo una comunicación directa con el ciudadano.

## Integrantes del grupo

| Nombre | Código de estudiante |
|--------|----------------------|
| [Cristian Yahir Campos Aparicio] | [SMSS109222] |
| [Lindis Arely Martinez Herrera] | [SMSS170822] |

## Tecnologías utilizadas

- **Backend:** Laravel (PHP)
- **Frontend:** Vue.js 3 + Tailwind CSS
- **Autenticación:** Laravel Sanctum
- **Mapas:** Leaflet.js + OpenStreetMap
- **Gestor de base de datos:** MySQL

## Requisitos previos

Antes de instalar el proyecto, asegúrate de tener instalado en tu equipo:

- PHP 8.2 o superior
- Composer
- Node.js y npm
- MySQL
- Git

> Se recomienda el uso de **Laragon**, que incluye PHP, MySQL y Composer en un solo paquete, y fue lo que se utilizo para la bd

## Instalación

Sigue los pasos en orden:

### 1. Clonar el repositorio

```bash
git clone https://github.com/USUARIO/LICAM.git
cd LICAM
```

### 2. Instalar las dependencias de PHP

```bash
composer install
```

### 3. Instalar las dependencias de JavaScript

```bash
npm install
```

### 4. Configurar el archivo de entorno

Copia el archivo de ejemplo y genera la clave de la aplicación:

```bash
copy .env.example .env
php artisan key:generate
```

### 5. Configurar la base de datos

Abre el archivo `.env` y configura los datos de conexión a MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=licam_db
DB_USERNAME=root
DB_PASSWORD=
```

Luego crea una base de datos llamada `licam_db` en tu gestor MySQL. 

### 6. Ejecutar las migraciones y los datos iniciales

```bash
php artisan migrate:fresh --seed
```

Esto creará todas las tablas y cargará los datos iniciales (roles, categorías y usuarios de prueba).

### 7. Crear el enlace de almacenamiento

Necesario para que las imágenes de los reportes sean accesibles:

```bash
php artisan storage:link
```

### 8. Iniciar el proyecto

Abre **dos terminales** y ejecuta un comando en cada una:

**Terminal 1 (backend):**

```bash
php artisan serve
```

**Terminal 2 (frontend):**

```bash
npm run dev
```

### 9. Acceder a la aplicación

Abre tu navegador en: http:\\localhost:8000
## Usuarios de prueba

El sistema incluye dos usuarios precargados para realizar pruebas:

| Rol | Correo | Contraseña |
|-----|--------|------------|
| Administrador | admin@licam.test | admin12345 |
| Ciudadano | carlos@licam.test | carlos12345 |


