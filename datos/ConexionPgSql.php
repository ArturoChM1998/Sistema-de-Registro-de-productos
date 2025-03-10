<?php

namespace Src\Datos;
use PDO;
use PDOException;
class ConexionPgSql
{
    /**
     * Devolvera null en caso de no poder conectar a la base de datos
     * @return PDO|null
     */
    public function ObtenerConexion() : ?PDO
    {
        try {
            $env = parse_ini_file('.env');
            $hostServidor = $env["HOST_SERVIDOR_PGSQL"];
            $nombreUsuario = $env["NOMBRE_USUARIO_PGSQL"];
            $contrasenaUsuario = $env["CONTRASENA_USUARIO_PGSQL"];
            $puertoServidor = $env["PUERTO_SERVIDOR_PGSQL"];
            $nombreBdd = $env["NOMBRE_BDD_PGSQL"];
            return new PDO(
                "pgsql:host=$hostServidor;port=$puertoServidor;dbname=$nombreBdd;",
                $nombreUsuario,
                $contrasenaUsuario
            );
        } catch (PDOException) {
            return null;
        }
    }
}