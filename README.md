Sistema de planificacion de actividades de la red de bibliotecas de Yaracuy

Este sistema tiene como objetivo facilitar tanto la produccion de cronogramas como la gestion de solicitudes de visita de actividades culturales en
las bibliotecas de la region

Lenguajes y Herramientas: HTML,CSS,Javascript,PHP,SQL,PHPmyAdmin
Arquitectura MVC
Controlador
Modelo y vista

Requisitos para los reportes PDF:
- PHP 7.3.2 o superior; la arquitectura de 32 bits es compatible.
- Extensiones PHP `dom`, `iconv` y `mbstring` habilitadas para Dompdf.
- `composer.json` configura PHP 7.3.2 como plataforma para mantener las dependencias compatibles.

`version.php` muestra la versión y arquitectura de PHP atendidas por el servidor web. Elimínalo del servidor público después de comprobar la instalación.

### Rol Coordinador

Para habilitar el rol Coordinador en una base de datos existente, ejecuta `db/migrations/20261110_add_coordinator_role.sql`. Un administrador puede asignarlo desde la configuración de usuarios. El Coordinador conserva los permisos de Usuario y puede gestionar empleados y cuentas de usuario vinculadas a su biblioteca, actualizar los datos de esa biblioteca y editar las actividades y solicitudes de esa biblioteca. Solo un administrador puede asignar o cambiar roles; el Coordinador no puede registrar ni eliminar bibliotecas ni acceder a otras funciones exclusivas del Administrador.
