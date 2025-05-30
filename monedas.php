<?php
namespace Src;

use Exception;
use Src\Datos\ConsultasMoneda;
use Src\Modelos\RespuestaJson;

require_once("AutoCargadorClases.php");
AutoCargadorClases::RegistrarClases();

try {
    header("content-type: application/json; charset=utf-8");

    if($_SERVER['REQUEST_METHOD'] != "GET") {
        http_response_code(404);
        return;
    }

    $resultadoConsulta = ConsultasMoneda::ObtenerMonedas();

    if(is_null($resultadoConsulta)) {
        http_response_code(500);
        echo json_encode(new RespuestaJson([], "No se pudo obtener el listado de monedas. Por favor intentelo más tarde."));
        return;
    } else if($resultadoConsulta === false) {
        http_response_code(200);
        echo json_encode(new RespuestaJson([], "En este momento no hay monedas registradas."));
        return;
    }

    http_response_code(200);
    echo json_encode(new RespuestaJson($resultadoConsulta, ""));
} catch (Exception) {
    http_response_code(500);
    echo json_encode(new RespuestaJson([], "No se pudo obtener el listado de monedas. Por favor intentelo más tarde."));
}
