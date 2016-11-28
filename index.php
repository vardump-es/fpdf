<?php 

include_once "export.php";

$exportador = new ExportadorPDF();

//obtener clientes
$clientes = json_decode(file_get_contents("data/clientes.json"));

//agregar datos al exportador
$exportador->setClientes($clientes);

$exportador->exportar();