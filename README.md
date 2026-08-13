# Café de Lima

Sistema web para la gestión de disponibilidad de platos, registro de consumos, análisis de consumo, cálculo de riesgo de agotamiento, alertas de reposición y reportes del restaurante Café de Lima.

Este documento explica cómo instalar y levantar el proyecto para realizar las pruebas principales.

---

## 1. Tecnologías utilizadas

- PHP 8.2
- Laravel 12
- MySQL
- Laravel Breeze
- Blade
- Bootstrap 5
- JavaScript
- Chart.js
- Vite
- Maatwebsite Excel
- DomPDF
- Composer
- Node.js / npm

---

## 2. Requisitos previos

Antes de levantar el proyecto se recomienda tener instalado:

- XAMPP con PHP 8.2 y MySQL.
- Composer 2.x.
- Node.js 22.12 o superior.
- npm.
- Git, si el proyecto será obtenido desde GitHub.

Para verificar las versiones instaladas:

```powershell
php -v
composer -V
node -v
npm -v
```

También se recomienda verificar que PHP tenga habilitadas las extensiones principales:

```text
mbstring
openssl
pdo_mysql
fileinfo
zip
gd
xml
ctype
curl
```

Se pueden revisar con:

```powershell
php -m
```

> Para ejecutar el proyecto mediante `php artisan serve` no es necesario iniciar Apache de XAMPP. Sí es necesario iniciar MySQL.

---

## 3. Obtener el proyecto

### Opción A: archivo ZIP

Descomprimir el proyecto y abrir una terminal dentro de la carpeta:

```powershell
cd C:\ruta\del\proyecto\cafe-lima
```

### Opción B: GitHub

```powershell
git clone https://github.com/Abigail30514/cafe-lima.git
cd cafe-lima
```

---

## 4. Instalar dependencias de Laravel

Desde la raíz del proyecto ejecutar:

```powershell
composer install
```

Este comando instalará las dependencias PHP definidas en `composer.json`.

---

## 5. Instalar dependencias del frontend

Ejecutar:

```powershell
npm install
```

Luego generar los archivos del frontend:

```powershell
npm run build
```

Si se desea trabajar en modo desarrollo se puede usar:

```powershell
npm run dev
```

En ese caso se debe mantener esa terminal abierta.

---

## 6. Configurar el archivo `.env`

Si el proyecto no contiene un archivo `.env`, crear una copia de `.env.example`:

```powershell
copy .env.example .env
```

Abrir el archivo `.env` y verificar como mínimo la siguiente configuración:

```env
APP_NAME="Café de Lima"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_TIMEZONE=America/Lima

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cafe_lima
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@cafelima.test"
MAIL_FROM_NAME="${APP_NAME}"
```

> Importante: verificar que `DB_CONNECTION` tenga el valor `mysql`.

Si MySQL tiene una contraseña configurada, colocarla en:

```env
DB_PASSWORD=
```

Luego generar la clave de Laravel:

```powershell
php artisan key:generate
```

Y limpiar cualquier configuración almacenada en caché:

```powershell
php artisan optimize:clear
```

---

## 7. Importar la base de datos

El proyecto se entrega junto con una exportación de la base de datos utilizada durante el desarrollo.

Archivo:

```text
bd/cafe_lima.sql
```

### Importación mediante phpMyAdmin

1. Iniciar **MySQL** desde XAMPP.
2. Abrir:

```text
http://localhost/phpmyadmin
```

3. Crear una nueva base de datos con el nombre:

```text
cafe_lima
```

4. Seleccionar la base de datos `cafe_lima`.
5. Ir a la opción **Importar**.
6. Seleccionar el archivo:

```text
bd/cafe_lima.sql
```

7. Ejecutar la importación.

La base de datos entregada contiene la estructura y los registros utilizados para las pruebas del sistema.

> No ejecutar `php artisan migrate:fresh`, ya que este comando elimina todos los datos importados.

Después de importar la base se puede comprobar el estado de las migraciones con:

```powershell
php artisan migrate:status
```

---

## 8. Levantar el sistema

Verificar primero que **MySQL se encuentre iniciado**.

Luego ejecutar:

```powershell
php artisan serve
```

Laravel mostrará normalmente la siguiente dirección:

```text
http://127.0.0.1:8000
```

Abrirla desde el navegador.

---

## 9. Credenciales de acceso

La base de datos entregada ya contiene usuarios registrados para probar los diferentes roles del sistema.

### Administrador

```text
Correo: admin@cafelima.test
Contraseña: CafeLima2026!
```

### Cocina

```text
Correo: cocina@cafelima.test
Contraseña: CafeLima2026!
```

### Atención

```text
Correo: atencion@cafelima.test
Contraseña: CafeLima2026!
```

---

## 10. Roles y accesos

### Administrador

Tiene acceso a:

- Dashboard.
- Disponibilidad.
- Consumos.
- Análisis de consumo.
- Riesgo de agotamiento.
- Alertas de reposición.
- Historial.
- Categorías.
- Productos.
- Usuarios.
- Reportes.
- Exportación a Excel y PDF.

### Cocina

Tiene acceso a:

- Dashboard.
- Disponibilidad.
- Actualización de disponibilidad.
- Consumos.
- Análisis de consumo.
- Riesgo de agotamiento.
- Alertas de reposición.
- Historial.

### Atención

Tiene acceso principalmente a:

- Dashboard.
- Consulta de disponibilidad.

Si un usuario intenta ingresar manualmente a una ruta que no corresponde a su rol, el sistema puede mostrar un error **403**, lo cual corresponde al control de permisos implementado.

---

## 11. Orden recomendado para las pruebas

### 11.1 Inicio de sesión

Ingresar con cada uno de los usuarios indicados anteriormente y comprobar que las opciones visibles cambien según el rol.

---

### 11.2 Dashboard

Validar los indicadores principales relacionados con:

- disponibilidad;
- consumo;
- productos críticos;
- últimas actualizaciones;
- información resumida de la operación.

---

### 11.3 Categorías

Con el usuario Administrador:

- consultar categorías;
- crear una categoría;
- editar una categoría;
- eliminar una categoría cuando corresponda.

---

### 11.4 Productos

Con el usuario Administrador:

- consultar productos;
- crear un producto;
- editar información;
- cambiar su estado;
- utilizar filtros;
- validar el campo destacado;
- eliminar un producto cuando corresponda.

Estados utilizados por el sistema:

```text
1 = Disponible
2 = Bajo stock
3 = Agotado
```

---

### 11.5 Disponibilidad

Probar:

- búsqueda de platos;
- filtro por categoría;
- filtro por estado;
- consulta del estado actual.

Con Administrador o Cocina también se puede probar el cambio de disponibilidad.

---

### 11.6 Registro de consumos

Con Administrador o Cocina ingresar al módulo **Consumos**.

Probar:

- selección del plato;
- cantidad;
- fecha;
- hora;
- observación;
- registro del consumo.

Los consumos registrados son utilizados posteriormente por el historial, análisis de consumo y cálculo del riesgo.

---

### 11.7 Análisis de consumo

Ingresar a **Análisis de consumo**.

Probar los periodos disponibles y revisar:

- consumo total;
- cantidad de registros;
- productos con consumo;
- promedio diario;
- variación respecto al periodo anterior;
- ranking de productos;
- gráfico de consumo por día.

---

### 11.8 Riesgo de agotamiento

Ingresar a **Riesgo de agotamiento** y validar:

- estado actual del plato;
- consumo reciente;
- promedio diario;
- tendencia;
- puntaje;
- nivel de riesgo.

Niveles utilizados:

```text
Bajo
Medio
Alto
Crítico
```

El cálculo considera el estado actual del plato y su comportamiento reciente de consumo.

---

### 11.9 Alertas de reposición

Ingresar a **Alertas de reposición**.

Validar:

- plato;
- prioridad;
- nivel de riesgo;
- puntaje;
- consumo reciente;
- alerta;
- recomendación.

Las alertas y recomendaciones utilizan el resultado del cálculo de riesgo.

---

### 11.10 Historial

Ingresar a **Historial**.

Probar los filtros disponibles y comprobar los registros relacionados con:

- cambios de disponibilidad;
- consumos;
- usuario;
- fecha y hora;
- observaciones.

---

### 11.11 Usuarios

Con el usuario Administrador ingresar a **Usuarios**.

Probar:

- listado de usuarios;
- creación;
- edición;
- asignación de roles;
- validación de permisos.

---

### 11.12 Reportes

Con el usuario Administrador ingresar a **Reportes**.

Probar filtros por:

- rango de fechas;
- categoría;
- estado.

Luego comprobar:

- resultados mostrados en pantalla;
- exportación a Excel;
- exportación a PDF.

Los archivos exportados deben mantener la información correspondiente a los filtros aplicados.

---

## 12. Recuperación de contraseña

Durante el desarrollo se utilizó Mailtrap para comprobar el envío de correos.

Para permitir que el proyecto sea evaluado sin compartir credenciales externas, el archivo `.env` puede configurarse con:

```env
MAIL_MAILER=log
```

Después ejecutar:

```powershell
php artisan optimize:clear
```

### Prueba del flujo

1. Ir a **¿Olvidaste tu contraseña?**
2. Ingresar el correo de un usuario registrado.
3. Solicitar el enlace de recuperación.
4. Revisar:

```text
storage/logs/laravel.log
```

5. Buscar dentro del registro una URL que contenga:

```text
reset-password
```

6. Copiar la URL y abrirla en el navegador.
7. Registrar una nueva contraseña.

De esta manera se puede validar el flujo de recuperación sin utilizar las credenciales privadas de Mailtrap.

---

## 13. Prueba responsive

El sistema fue adaptado para diferentes tamaños de pantalla.

Para realizar la prueba se puede utilizar el modo responsive de las herramientas para desarrolladores del navegador.

Resoluciones de referencia:

```text
Computadora: 1366 x 768
Tablet:      768 x 1024
Móvil:       390 x 844
```

Se recomienda revisar principalmente:

- Login.
- Dashboard.
- Disponibilidad.
- Consumos.
- Análisis de consumo.
- Riesgo de agotamiento.
- Alertas.
- Historial.
- Reportes.
- Productos.
- Categorías.
- Usuarios.

En pantallas pequeñas algunos elementos cambian su distribución para facilitar la lectura y navegación.

---

## 14. Comandos útiles

### Levantar Laravel

```powershell
php artisan serve
```

### Levantar Vite en desarrollo

```powershell
npm run dev
```

### Generar archivos frontend

```powershell
npm run build
```

### Ver las rutas

```powershell
php artisan route:list
```

### Revisar información de Laravel

```powershell
php artisan about
```

### Limpiar caché

```powershell
php artisan optimize:clear
```

### Revisar migraciones

```powershell
php artisan migrate:status
```

---

## 15. Solución de errores frecuentes

### Error: `Unknown database 'cafe_lima'`

Verificar que la base:

```text
cafe_lima
```

haya sido creada y que el archivo SQL haya sido importado.

---

### Error: `could not find driver`

Verificar que PHP tenga habilitado:

```text
pdo_mysql
```

---

### Error: `Call to undefined function mb_split()`

Habilitar en `php.ini`:

```text
extension=mbstring
```

Luego cerrar y abrir nuevamente la terminal.

---

### Error: `Vite manifest not found`

Ejecutar:

```powershell
npm install
npm run build
```

---

### Error de conexión con MySQL

Revisar las variables:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cafe_lima
DB_USERNAME=root
DB_PASSWORD=
```

---

### Error 403

Verificar el rol del usuario.

Las restricciones por rol son parte del funcionamiento esperado del sistema.

---

### El correo de recuperación no llega a una bandeja

Si se está utilizando:

```env
MAIL_MAILER=log
```

el correo no se envía a una cuenta real.

El enlace se registra en:

```text
storage/logs/laravel.log
```

---

### Problemas con Excel o PDF

Ejecutar:

```powershell
composer install
php artisan optimize:clear
```

También verificar que PHP tenga habilitadas las extensiones:

```text
zip
gd
mbstring
xml
```

---

## 16. Inicio rápido

Una vez instalados PHP, Composer, Node.js y MySQL:

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan optimize:clear
npm run build
```

Luego:

1. Iniciar MySQL.
2. Crear la base `cafe_lima`.
3. Importar `bd/cafe_lima.sql`.
4. Verificar la configuración de `.env`.
5. Ejecutar:

```powershell
php artisan serve
```

6. Abrir:

```text
http://127.0.0.1:8000
```

7. Ingresar con una de las credenciales indicadas en este README.

---

## 17. Seguridad

El proyecto no debe publicar información privada del entorno de desarrollo.

No se debe incluir:

```text
.env
node_modules/
vendor/
credenciales reales de Mailtrap
contraseñas personales
```

Las dependencias pueden reconstruirse mediante:

```powershell
composer install
npm install
```

---

## 18. Repositorio

```text
https://github.com/Abigail30514/cafe-lima.git
```

---

## 19. Resumen de permisos

| Funcionalidad | Administrador | Cocina | Atención |
|---|:---:|:---:|:---:|
| Dashboard | ✅ | ✅ | ✅ |
| Consultar disponibilidad | ✅ | ✅ | ✅ |
| Cambiar disponibilidad | ✅ | ✅ | ❌ |
| Consumos | ✅ | ✅ | ❌ |
| Análisis de consumo | ✅ | ✅ | ❌ |
| Riesgo de agotamiento | ✅ | ✅ | ❌ |
| Alertas de reposición | ✅ | ✅ | ❌ |
| Historial | ✅ | ✅ | ❌ |
| Categorías | ✅ | ❌ | ❌ |
| Productos | ✅ | ❌ | ❌ |
| Usuarios | ✅ | ❌ | ❌ |
| Reportes | ✅ | ❌ | ❌ |
| Exportación Excel/PDF | ✅ | ❌ | ❌ |

---

**Proyecto académico — Café de Lima — 2026**
