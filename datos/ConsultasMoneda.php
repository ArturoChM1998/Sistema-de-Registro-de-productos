<?php

namespace Src\Datos;

use Exception;
use Src\Modelos\Moneda;
class ConsultasMoneda
{
    /**
     *  Devolvera null ante cualquier error o resultado incorrecto
     *  Devolvera false si no se encuentran monedas
     * @return null|Moneda[]|false
     */
    public static function ObtenerMonedas() : null | array | false
    {
        try {
            $bdd = new ConexionPgSql();
            $conexion = $bdd -> ObtenerConexion();
            if(!isset($conexion)) {
                return null;
            }

            $consultaSql = "SELECT id_moneda, nombre FROM moneda";
            $resultadoConsulta = $conexion ->query($consultaSql);
            if(!$resultadoConsulta) {
                return null;
            }

            if($resultadoConsulta->rowCount() == 0) {
                return false;
            }

            $listadoMonedas = array_map(function ($fila) {
                return new Moneda(
                    $fila["id_moneda"],
                    $fila["nombre"]
                );
            }, $resultadoConsulta->fetchAll());

            return $listadoMonedas;
        } catch (Exception) {
            return null;
        }
    }
}