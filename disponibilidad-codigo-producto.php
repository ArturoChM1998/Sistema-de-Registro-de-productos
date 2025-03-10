<?php
namespace Src;

use Exception;
use Src\Modelos\RespuestaJson;
use Src\Validaciones\ValidadorProductos;

require_once("AutoCargadorClases.php");
AutoCargadorClases::RegistrarClases();

try {
    header("content-type: application/json; charset=utf-8");

    if($_SERVER['REQUEST_METHOD'] != "GET") {
        http_response_code(404);
        return;
    }

    $parametroFueIncluido = isset($_GET["codigoProducto"]);
    if(!$parametroFueIncluido) {
        http_response_code(400);
        echo json_encode(new RespuestaJson([], 'Se debe incluir el parametro de consulta "codigoProducto" con un valor asignable a una cadena.'));
        return;
    }
    $valorParametroEsCadena = is_string($_GET["codigoProducto"]);
    $valorParametroEsCadenaVacia = empty($_GET["codigoProducto"]);
    if(!$valorParametroEsCadena || $valorParametroEsCadenaVacia) {
        http_response_code(400);
        echo json_encode(new RespuestaJson([], 'El parametro de consulta "codigoProducto" no corresponde a una cadena o esta vacía.'));
        return;
    }

    //descodificar el valor del parametro que incluye caracteres especiales
    $codigoProducto = urldecode($_GET["codigoProducto"]);

    $validador = new ValidadorProductos();
    $resultadoValidacionCodigo = $validador::ValidarCodigo($codigoProducto);

    if(is_string($resultadoValidacionCodigo)){
        http_response_code(422);
        echo json_encode(new RespuestaJson([], $resultadoValidacionCodigo));
        return;
    }

    $resultadoValidacionDisponibilidad = $validador::ValidarDisponibilidadCodigo($codigoProducto);
    if(is_string($resultadoValidacionDisponibilidad)){
        http_response_code(422);
        echo json_encode(new RespuestaJson([], $resultadoValidacionDisponibilidad));
        return;
    }

    http_response_code(200);
    echo json_encode(new RespuestaJson(true, ""));
} catch (Exception) {
    http_response_code(500);
    echo json_encode(new RespuestaJson([], "No se pudo verificar la unicidad del código del producto. Por favor intentelo más tarde."));
}
