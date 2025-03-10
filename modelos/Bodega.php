<?php

namespace Src\Modelos;

class Bodega
{
    public int $idBodega;
    public string $nombre;
    public function __construct($idBodega, $nombre)
    {
        $this->idBodega = $idBodega;
        $this->nombre = $nombre;
    }
}