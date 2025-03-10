<?php

namespace Src\Datos;

use Exception;
use PDO;
use Src\Modelos\Bodega;
use Src\Modelos\SucursalBodega;

class ConsultasBodega
{
    /**
     * Devolvera null ante cualquier error o resultado incorrecto
     * Devolvera false si no se encuentran bodegas
     * @return array|false|null
     */
    public static function ObtenerBodegas() : null | array | false
    {
        try {
            $bdd = new ConexionPgSql();
            $conexion = $bdd -> ObtenerConexion();

            if(!isset($conexion)) {
                return null;
            }

            $resultado = $conexion ->query("SELECT id_bodega, nombre FROM bodega");
            if(!$resultado) {
                return null;
            }

            if($resultado -> rowCount() == 0) {
                return false;
            }

            $listadoBodegas = array_map(function($fila) {
                return new Bodega(
                    $fila["id_bodega"],
                    $fila["nombre"]
                );
            }, $resultado -> fetchAll());

            return $listadoBodegas;
        } catch (Exception) {
            return null;
        }
    }

    /**
     * Devolvera null ante cualquier error o resultado incorrecto
     * Devolvera false si no se encuentran sucursales para el idBodega especificado
     * @return null | SucursalBodega[] | false
     */
    public static function ObtenerSucursalesSegunBodega($idBodega) : null | array | false
    {
        try {
            $bdd = new ConexionPgSql();
            $conexion = $bdd -> ObtenerConexion();

            if(is_null($conexion)) {
                return null;
            }

            $consultaSql = "SELECT id_sucursal, id_bodega, nombre FROM sucursal_bodega WHERE id_bodega = :idBodega";
            $sentencia = $conexion -> prepare($consultaSql);
            $sentencia -> bindParam(':idBodega', $idBodega, PDO::PARAM_INT);

            $resultado = $sentencia -> execute();
            if(!$resultado) {
                return null;
            }

            if($sentencia -> rowCount() == 0) {
                return false;
            }
            $listadoSucursales = array_map(function ($fila) {
                return new SucursalBodega(
                    $fila["id_bodega"],
                    $fila["id_sucursal"],
                    $fila["nombre"]
                );
            }, $sentencia->fetchAll());
            return $listadoSucursales;
        } catch (Exception) {
            return null;
        }
    }
}