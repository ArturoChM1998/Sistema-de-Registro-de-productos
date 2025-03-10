# Sistema de Registro de productos
Contiene el código fuente del proyecto solicitado junto a las instrucciones para reproducirlo

# Software requerido
- Windows de 64 bits
- PHP 8.2
- PostgreSQL 17
- XAMPP 8.2.12
- GIT

# Instalación de XAMPP, clonación del proyecto e instalación base de datos PostgreSQL 
### Instalar XAMPP
Dirigase a: https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.12/
1. Haga click en el enlace "xampp-windows-x64-8.2.12-0-VS16-installer.exe" para descargar el instalador
2. Ejecute el instalador, haga click en aceptar si ve una alerta una vez abierto el ejecutable.
3. De click en "Next >" para continuar hasta que llege a la sección "Select Components"
4. Ya que solo utilizaremos PHP y Apache, desmarque todas las casillas
5. Nuevamente de click en "Next >", se le indicara el destino de la instalación, dejelo en la unidad del disco por ejemplo "C:\xampp" o "D:\xampp" asegurandose que no haya una carpeta existente con el mismo nombre
6. Haga click en "Next >" hasta que comience la instalación

### Clonar proyecto
1. Una vez terminada la instalación, navegue y abra la carpeta "xampp" seguida por la carpeta "htdocs", por ejemplo "C:\xampp\htdocs"
2. Dentro de la ubicación anterior, ejecute la terminal de Windows mediante click derecho dentro de la ventana para desplegar el menu contextual, una vez desplegado haga click en "Abrir en Terminal"
3. Una vez abierta la terminal, copie la siguiente instrucción sin comillas "git clone https://github.com/ArturoChM1998/Sistema-de-Registro-de-productos.git src" y presione enter para ejecutar lo cual clonara el proyecto en la carpeta

### Instalar PostgreSQL e iniciar base de datos
Dirigase a: https://www.enterprisedb.com/downloads/postgres-postgresql-downloads
1. Haga click en el icono de descarga para "Windows x86-64" en la versión "17.*". El asterisco indica que puede ser cualquier número 
2. Ejecute el instalador, haga click en "Siguiente" hasta que llege a la sección de "Selección de Componentes"
3. Desmarque la opción "Stack Builder"
4. Haga click en "Siguiente" hasta que llege a la sección "Contraseña", una vez ahí especifique una contraseña para autenticarse
5. Continue hasta la sección "Opciones Avanzadas" en la cual debe indicar la opción "Spanish (Chile)" en "Configuración Regional"
6. Siga avanzando hasta que comience la instalación
7. Una vez terminado, ejecute la aplicación "pgAdmin 4"
8. Conectese al servidor por defecto mediante la contraseña indicada previamente
9. En la base de datos "postgres" en el esquema "public", presione "Alt+Shift+Q" al mismo tiempo para abrir la ventana de consultas
10. Copie y pegue el script SQL, una vez pegado presione "F5" para ejecutar el script

### Habilitar extensiones requeridas para la interacción entre la base de datos y PHP
1. Dirigase a la carpeta de instalación de XAMPP Ej: "C:\xampp\" y abra la carpeta "php"
2. Dentro de la carpeta abra el archivo php cuya extensión es "Opciones de configuración"
3. Busque el siguiente texto sin comillas: "extension=pdo_pgsql", una vez encontrado asegurese de quitarle el caracter ";" al inicio para habilitar la extensión
4. Repita el paso anterior para "extension=pgsql"

### Establecer valores en archivo .env requeridos para la conexión a la base de datos desde PHP
1. Dirigase a la carpeta del proyecto Ej: "C:\xampp\htdocs\src" y abra el archivo .env
2. Indique la contraseña en la variable correspondiente, modifique las otras variables si ha cambiado el puerto o ingreso con otro usuario

### Probar 
1. Ejecute la aplicacion XAMPP Control Panel e inicie el servicio "Apache", si ya estaba iniciado reinicielo nuevamente para que considere los cambios en las extensiones
2. Ingrese a la siguiente dirección en el navegador para visualizar el proyecto: "http://localhost/src/
