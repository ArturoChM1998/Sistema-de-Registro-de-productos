<?php

namespace Src\Modelos;

class SucursalBodega
{
    public int $idSucursal;
    public int $idBodega;
    public string $nombre;

    public function __construct($idBodega, $idSucursal, $nombre)
    {
        $this->idSucursal = $idSucursal;
        $this->idBodega = $idBodega;
        $this->nombre = $nombre;
    }
}