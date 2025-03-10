<?php

namespace Src\Modelos;
class RespuestaJson
{
    public mixed $datos;
    public string $mensaje;
    public function __construct(mixed $datos, string $mensaje)
    {
        $this->datos = $datos;
        $this->mensaje = $mensaje;
    }
}