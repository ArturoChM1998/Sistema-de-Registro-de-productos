<?php
namespace Src\Modelos;

class Producto
{
    public string $codigo;
    public string $nombre;
    public int $idSucursal;
    public int $idMoneda;
    public float $precio;
    public string $descripcion;
    public function __construct($codigo, $nombre, $idSucursal, $idMoneda, $precio, $descripcion) {
        $this->codigo = $codigo;
        $this -> idSucursal = $idSucursal;
        $this -> idMoneda = $idMoneda;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
    }
}
