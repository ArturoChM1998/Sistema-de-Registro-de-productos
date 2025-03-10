<?php

namespace Src\Datos;

use Exception;
use PDO;
use Src\Modelos\Producto;
class ConsultasProducto
{
    /**
     * Devolvera false ante cualquier error o resultado incorrecto
     * @param Producto $producto
     * @return bool
     */
    public static function InsertarProducto(Producto $producto) : bool
    {
        try {
            $bdd = new ConexionPgSql();
            $conexion = $bdd -> ObtenerConexion();
            if(!isset($conexion)) {
                return false;
            }

            //El id del producto es auto generado por defecto, por lo tanto no se específica
            $consultaSql = $conexion -> prepare("
                INSERT INTO producto (id_sucursal_bodega, id_moneda, codigo, nombre, precio, descripcion) 
                VALUES (:id_sucursal_bodega, :id_moneda, :codigo, :nombre, :precio, :descripcion)"
            );

            $consultaSql -> bindParam(":id_sucursal_bodega", $producto -> idSucursal, PDO::PARAM_INT);
            $consultaSql -> bindParam(":id_moneda", $producto -> idMoneda);
            $consultaSql -> bindParam(":codigo", $producto -> codigo);
            $consultaSql -> bindParam(":nombre", $producto -> nombre);
            $consultaSql -> bindParam(":precio", $producto -> precio);
            $consultaSql -> bindParam(":descripcion", $producto -> descripcion);
            $insertoConExito = $consultaSql -> execute();

            if(!$insertoConExito) {
                return false;
            }

            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Devolvera una cadena con un mensaje ante cualquier error o resultado incorrecto
     * de lo contrario devolvera null
     * @param $codigoProducto
     * @return null | string
     */
    public static function ConsultarExistenciaCodigo($codigoProducto) : null | string {
        try {
            $bdd = new ConexionPgSql();
            $conexion = $bdd -> ObtenerConexion();
            if(is_null($conexion)) {
                return "No se pudo verificar la unicidad del código del producto. Por favor intentelo más tarde.";
            }

            $consultaSql = "SELECT codigo FROM producto WHERE codigo = :codigoProducto";
            $sentencia = $conexion -> prepare($consultaSql);
            $sentencia -> bindParam(':codigoProducto', $codigoProducto);

            $resultado = $sentencia -> execute();
            if(!$resultado){
                return "No se pudo verificar la unicidad del código del producto. Por favor intentelo más tarde.";
            }

            $filasExistentes = $sentencia -> rowCount();
            if($filasExistentes != 0) {
                return "El código del producto ya está registrado.";
            }

            return null;
        }
        catch (Exception) {
            return "No se pudo verificar la unicidad del código del producto. Por favor intentelo más tarde.";
        }
    }
}