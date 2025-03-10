<?php

namespace Src;

use Src\Modelos\Material;

include("AutoCargadorClases.php");
AutoCargadorClases::RegistrarClases();

$listadoMateriales = [
    new Material("Plástico", "1"),
    new Material("Vidrio", "2"),
    new Material("Cuero", "3"),
    new Material("Metal", "4"),
    new Material("Algódon", "5"),
];

//require("index.html");
