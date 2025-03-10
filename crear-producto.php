<?php

namespace Src;

use Exception;
use Src\Datos\ConsultasProducto;
use Src\Modelos\Producto;
use Src\Modelos\RespuestaJson;
use Src\Validaciones\ValidadorProductos;

require_once("AutoCargadorClases.php");
AutoCargadorClases::RegistrarClases();

try {
    header("content-type: application/json; charset=utf-8");
    header("accept: application/json");

    if($_SERVER['REQUEST_METHOD'] != "POST") {
        http_response_code(404);
        return;
    }

    $contenidoCuerpoSolicitud = file_get_contents('php://input');

    //Deserializar como array llave valor
    $datosSolicitud = json_decode($contenidoCuerpoSolicitud, true);
    if($datosSolicitud == null) {
        http_response_code(400);
        echo json_encode(new RespuestaJson([], "Debe incluir una cadena JSON válida con datos en el cuerpo de la solicitud."));
        return;
    }

    $nombresPropiedades = ["codigo", "nombre", "idBodega", "materiales", "idSucursal", "idMoneda", "precio", "descripcion"];
    foreach ($nombresPropiedades as $nombrePropiedad) {
        //Solo válida que la propiedad hay sido declarada en el JSON deserializado, no comprueba valores asignados
        $propiedadFueIncluida = property_exists((object)$datosSolicitud, $nombrePropiedad);

        if(!$propiedadFueIncluida){
            http_response_code(400);
            echo json_encode(new RespuestaJson("", "Debe incluir la propiedad" . " " . $nombrePropiedad . " " . "en el cuerpo de la solicitud."));
            return;
        }

        $validadorProducto = new ValidadorProductos();
        $mensajeValidacion = null;
        //Validar los valores y formatos de las propiedades incluidas
        //Si al llamar a los metodos de validación, se devuelve un mensaje relacionado,
        // asignara su valor a $mensajeValidacion y saldra del switch
        switch ($nombrePropiedad) {
            case "codigo":
                $mensajeValidacion = $validadorProducto::ValidarCodigo($datosSolicitud[$nombrePropiedad]);
                if(is_string($mensajeValidacion)){
                    break;
                }

                $mensajeValidacion = $validadorProducto::ValidarDisponibilidadCodigo($datosSolicitud[$nombrePropiedad]);
                if(is_string($mensajeValidacion)){
                    break;
                }
                break;
            case "nombre":
                $mensajeValidacion = $validadorProducto::validarNombre($datosSolicitud[$nombrePropiedad]);
                if(is_string($mensajeValidacion)){
                    break;
                }
                break;
            case "precio":
                $mensajeValidacion = $validadorProducto::validarPrecio($datosSolicitud[$nombrePropiedad]);
                if(is_string($mensajeValidacion)){
                    break;
                }
                break;
            case "materiales":
                $mensajeValidacion = $validadorProducto::validarMateriales($datosSolicitud[$nombrePropiedad]);
                if(is_string($mensajeValidacion)){
                    break;
                }
                break;
            case "idBodega":
                $mensajeValidacion = $validadorProducto::validarBodega($datosSolicitud[$nombrePropiedad]);
                if(is_string($mensajeValidacion)){
                    break;
                }
                break;
            case "idSucursal":
                $mensajeValidacion = $validadorProducto::validarSucursal($datosSolicitud[$nombrePropiedad]);
                if(is_string($mensajeValidacion)){
                    break;
                }
                break;
            case "idMoneda":
                $mensajeValidacion = $validadorProducto::validarMoneda($datosSolicitud[$nombrePropiedad]);
                if(is_string($mensajeValidacion)){
                    break;
                }
                break;
            case "descripcion":
                $mensajeValidacion = $validadorProducto::validarDescripcion($datosSolicitud[$nombrePropiedad]);
                if(is_string($mensajeValidacion)){
                    break;
                }
                break;
        }
        $validadorProducto = null;
        //Devolver el mensaje de validación, en caso de que lo haya
        if(is_string($mensajeValidacion)) {
            http_response_code(422);
            echo json_encode(new RespuestaJson([], $mensajeValidacion));
            return;
        }
    }

    $consultasProducto = new ConsultasProducto();
    $producto = new Producto(
        $datosSolicitud["codigo"],
        $datosSolicitud["nombre"],
        $datosSolicitud["idSucursal"],
        $datosSolicitud["idMoneda"],
        $datosSolicitud["precio"],
        $datosSolicitud["descripcion"],
    );
    $insertoConExito = $consultasProducto::InsertarProducto($producto);
    if(!$insertoConExito){
        http_response_code(500);
        echo json_encode(new RespuestaJson([], "No se pudo crear el producto, por favor intentelo más tarde."));
        return;
    }

    http_response_code(200);
    echo json_encode(new RespuestaJson([], "Se ha creado un nuevo producto."));
} catch (Exception) {
    http_response_code(500);
    echo json_encode(new RespuestaJson([], "No se pudo crear el producto, por favor intentelo más tarde."));
}
