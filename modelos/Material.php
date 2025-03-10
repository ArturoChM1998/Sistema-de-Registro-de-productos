<?php

namespace Src\Modelos;
class Material
{
    public string $nombre;
    public int $id;
    public function __construct($nombre, $id)
    {
        $this->nombre = $nombre;
        $this->id = $id;
    }
}