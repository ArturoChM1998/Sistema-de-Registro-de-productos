<?php

namespace Src;

class AutoCargadorClases
{
    public static function RegistrarClases()
    {
        spl_autoload_register(function ($className) {
            //Incluira la ubicación relativa desde la raiz hasta la clase
            //según como haya sido establecida en el namespace/use

            //Arreglo con de cada carpeta contenedora y la clase
            $pathClase = explode(DIRECTORY_SEPARATOR, $className);
            //Extraer el nombre de la clase sin ubicación
            $nombreClaseSinPath = end($pathClase);

            //Le quita el nombre de la clase a la ubicación relativa
            //Se asume que todas las carpetas seran nombradas en minuscula y no tendran caracteres especiales
            $pathRelativoCarpetaContenedoraClase = str_replace($nombreClaseSinPath, '', $className);
            $pathRelativoCarpetaContenedoraClase = strtolower($pathRelativoCarpetaContenedoraClase);

            //Finalmente se arma el path absoluto de la clase en el sistema, con el fin de ser importada por la función
            $pathAbsolutoClase = dirname(__DIR__) . DIRECTORY_SEPARATOR . $pathRelativoCarpetaContenedoraClase . $nombreClaseSinPath . ".php";
            require $pathAbsolutoClase;
        });
    }
}