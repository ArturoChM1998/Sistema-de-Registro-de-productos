<?php
namespace Src;

use Exception;
use Src\Datos\ConsultasBodega;
use Src\Modelos\RespuestaJson;

require_once("AutoCargadorClases.php");
AutoCargadorClases::RegistrarClases();

try {
    header("content-type: application/json; charset=utf-8");
    if($_SERVER['REQUEST_METHOD'] != "GET") {
        http_response_code(404);
        return;
    }

    //devolvera false si no se incluye en parametro o su valor no corresponde a un número
    $idBodega = filter_input(INPUT_GET, "idBodega", FILTER_VALIDATE_INT);
    if(!$idBodega){
        http_response_code(400);
        echo json_encode(new RespuestaJson([], 'Se debe incluir el parametro de consulta "?idBodega=" con un valor asignable a un número.'));
        return;
    }

    $resultadoObtenerSucursales = ConsultasBodega::ObtenerSucursalesSegunBodega($idBodega);
    if(is_null($resultadoObtenerSucursales)) {
        http_response_code(500);
        echo json_encode(new RespuestaJson([], "No se pudo obtener el listado de sucursales. Por favor intentelo más tarde."));
        return;
    } else if(!$resultadoObtenerSucursales){
        http_response_code(500);
        echo json_encode(new RespuestaJson([], "No se encontraron sucursales para la bodega indicada."));
        return;
    }

    http_response_code(200);
    echo json_encode(new RespuestaJson($resultadoObtenerSucursales, ""));
} catch (Exception) {
    http_response_code(500);
    echo json_encode(new RespuestaJson([], "No se pudo obtener el listado de sucursales. Por favor intentelo más tarde."));
}
