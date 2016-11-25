<?php

include_once "vendor/fpdf/fpdf.php";

//para utilizar FPDF debemos extender su clase principal
class ExportPDF extends FPDF{

	private $clientes;

	const limitePagina = 240; 
	const comienzoTabla = 40;

	//meter los clientes que vamos a exportar
	public function setClientes($clientes){
		$this->clientes = $clientes;
	}

	public function exportar(){

		//se recorre a los clientes
		foreach ($clientes as $cliente) {
			$this->exportarCliente($cliente);
			//se verifica si se necesita o no una nueva página
			$this->analizarSaltoDePagina();
		}

		//La opción D es para forzar la descarga del archivo.
		$this->Output('D','clientes_'.date('Y-m-d').'.pdf');
	}
	
	private function analizarSaltoDePagina(){
		//si la posición X del documento supera el límite de salto de pagina
		if($this->starty>self::limitePagina){
			//se añade una nueva página
			$this->AddPage();	
		}
	}

	//sobrecargamos la funcion AddPage para que cada vez que se agrega una página
	public function AddPage(){
		//se dibuja la tabla
		$this->dibujarTabla();
		//se posiciona en el comienzo de la tabla
		$this->starty = self::comienzoTabla;
	}

}