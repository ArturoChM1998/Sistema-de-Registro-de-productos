<?php

namespace Src\Modelos;

class Moneda
{
    public int $idMoneda;
    public string $nombre;

    public function __construct($idMoneda, $nombre)
    {
        $this->idMoneda = $idMoneda;
        $this->nombre = $nombre;
    }
}