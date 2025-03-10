<?php

namespace Src\Validaciones;
use Exception;
use Src\AutoCargadorClases;
use Src\Datos\ConsultasProducto;

require_once("AutoCargadorClases.php");
AutoCargadorClases::RegistrarClases();
class ValidadorProductos {
    public static function ValidarCodigo($codigoProducto) : string | null
    {
        try {
            $codigoEstaVacio = strlen($codigoProducto) == 0;
            if($codigoEstaVacio){
                return "El código del producto no puede estar en blanco.";
            }

            $codigoTieneMenosDelLargoMinimo = strlen($codigoProducto) < 5;
            $codigoExcedeLargoMaximo = strlen($codigoProducto) > 15;
            if($codigoTieneMenosDelLargoMinimo || $codigoExcedeLargoMaximo){
                return "El código del producto debe tener entre 5 y 15 caracteres.";
            }

            $codigoTieneLetras = preg_match("/[a-z]/i", $codigoProducto);
            $codigoTieneDigitos = preg_match("/[0-9]/", $codigoProducto);
            $codigoTieneCaracteresEspeciales = preg_match("/[^a-z0-9]/i", $codigoProducto);

            if(!$codigoTieneLetras || !$codigoTieneDigitos){
                return "El código del producto debe contener letras y números";
            }
            if($codigoTieneCaracteresEspeciales){
                return "El código del producto debe contener letras y números";
            }

            return null;
        } catch (Exception) {
            return "No pudimos procesar tu solicitud. Por favor intentelo más tarde.";
        }
    }
    public static function ValidarDisponibilidadCodigo($codigoProducto) : string | null {
        try {
            $mensajeError = ConsultasProducto::ConsultarExistenciaCodigo($codigoProducto);
            if(is_string($mensajeError)){
                return $mensajeError;
            }
            return null;
        } catch (Exception) {
            return "No se pudo verificar la unicidad del código del producto. Por favor intentelo más tarde.";
        }
    }


    public static function validarNombre($nombreProducto) : string | null
    {
        $estaVacio = strlen($nombreProducto) == 0;
        if($estaVacio){
            return "El nombre del producto no puede estar en blanco.";
        }
        $dentroDelLargoPermitido = strlen($nombreProducto) >= 2 && strlen($nombreProducto) <= 50;
        if(!$dentroDelLargoPermitido){
            return "El nombre del producto debe tener entre 2 y 50 caracteres.";
        }
        return null;
    }

    public static function validarPrecio($precioProducto) : string | null
    {
        $esNumerico = is_numeric($precioProducto);
        if(!$esNumerico){
            return "El precio del producto no puede estar en blanco.";
        }

        $regexSoloValoresCeros = preg_match("/^0*[.]?0*$/", $precioProducto);
        $regexContieneDosDecimales = preg_match("/^\d*[.]?(?=\d)\d{0,2}$/", $precioProducto);
        if($regexSoloValoresCeros == 1 || $regexContieneDosDecimales == 0){
            return "El precio del producto debe ser un número positivo con hasta dos decimales.";
        }

        return null;
    }

    public static function validarMateriales($materialesProducto) : string | null
    {
        $esUnArreglo = is_array($materialesProducto);
        if(!$esUnArreglo){
            return "La propiedad materiales debe ser arreglo con los nombres de los materiales indicados.";
        }

        $estaVacio = empty($materialesProducto);
        $indicoMenosDeDosMateriales = count($materialesProducto) < 2;
        if($estaVacio || $indicoMenosDeDosMateriales){
            return "Debe seleccionar al menos dos materiales para el producto.";
        }
        return null;
    }

    public static function validarBodega($idBodegaProducto) : string | null
    {
        $indicoBodega = is_int($idBodegaProducto);
        if(!$indicoBodega) {
            return "Debe seleccionar una bodega.";
        }

        $idBodegaEsCero = $idBodegaProducto == 0;
        if($idBodegaEsCero){
            return "Debe seleccionar una bodega.";
        }

        return null;
    }

    public static function validarSucursal($idSucursalProducto) : string | null
    {
        $indicoSucursal = is_int($idSucursalProducto);
        if(!$indicoSucursal) {
            return "Debe seleccionar una sucursal para la bodega seleccionada.";
        }

        $idSucursalEsCero = $idSucursalProducto == 0;
        if($idSucursalEsCero){
            return "Debe seleccionar una sucursal para la bodega seleccionada.";
        }

        return null;
    }

    public static function validarMoneda($idMonedaProducto)
    {
        $indicoMoneda = is_int($idMonedaProducto);
        if(!$indicoMoneda) {
            return "Debe seleccionar una moneda para el producto.";
        }

        $idMonedaEsCero = $idMonedaProducto == 0;
        if($idMonedaEsCero){
            return "Debe seleccionar una moneda para el producto.";
        }

        return null;
    }

    public static function validarDescripcion($descripcionProducto)
    {
        $indicoDescripcion = is_string($descripcionProducto);
        if(!$indicoDescripcion) {
            return "La descripción del producto no puede estar en blanco.";
        }

        $descripcionEstaVacia = strlen($descripcionProducto) == 0;
        if($descripcionEstaVacia) {
            return "La descripción del producto no puede estar en blanco.";
        }

        $descripcionDentroDelLargoPermitido = strlen($descripcionProducto) >= 10 && strlen($descripcionProducto) <= 1000;
        if(!$descripcionDentroDelLargoPermitido) {
            return "La descripción del producto debe tener entre 10 y 1000 caracteres.";
        }
        return null;
    }
}
