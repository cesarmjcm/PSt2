Sistema de planificacion de actividades de la red de bibliotecas de Yaracuy

Este sistema tiene como objetivo facilitar tanto la produccion de cronogramas como la gestion de solicitudes de visita de actividades culturales en
las bibliotecas de la region

Lenguajes y Herramientas: HTML,CSS,Javascript,PHP,SQL,PHPmyAdmin
Arquitectura MVC
Controlador
Modelo y vista

Requisitos para los reportes PDF:
- PHP 7.0.33 o superior; la arquitectura de 32 bits es compatible.
- Extensiones PHP `dom`, `iconv` y `mbstring` habilitadas para Dompdf.
- `composer.json` configura PHP 7.0.33 como plataforma para mantener las dependencias compatibles.

Los botones **Exportar Excel** descargan archivos CSV con codificación UTF-8 y separador punto y coma, compatibles con Excel en Windows y sin dependencias adicionales. Están disponibles en los maestros, actividades, solicitudes y usuarios; respetan las búsquedas y permisos de cada pantalla.

Para asignar cada espacio cultural a una parroquia, respalda la base de datos y ejecuta una sola vez `db/migrations/20261116_add_parish_to_cultural_spaces.sql` sobre una base existente. La migración usa la parroquia de la actividad más reciente asociada a cada espacio; los espacios sin actividades deben asignarse desde el maestro **Espacios culturales**.

`version.php` muestra la versión y arquitectura de PHP atendidas por el servidor web. Elimínalo del servidor público después de comprobar la instalación.

### Rol Coordinador

Para habilitar el rol Coordinador en una base de datos existente, ejecuta `db/migrations/20261110_add_coordinator_role.sql`. Un administrador puede asignarlo desde la configuración de usuarios. El Coordinador conserva los permisos de Usuario y puede gestionar empleados y cuentas de usuario vinculadas a su biblioteca, actualizar los datos de esa biblioteca y editar las actividades y solicitudes de esa biblioteca. Solo un administrador puede asignar o cambiar roles; el Coordinador no puede registrar ni eliminar bibliotecas ni acceder a otras funciones exclusivas del Administrador.
