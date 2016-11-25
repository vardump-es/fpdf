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

	//funcion que dibuja la tabla en cuestión
	private function dibujarTabla(){

		//columna identificador cliente
		//se posiciona el cursor
		$this->setXY(10,self::comienzoTabla);
		//se hace un cuadro de texto (ancho, alto, texto, bordes, alineación del texto)
		$this->MultiCell(20,5,'ID',1,'C');
		// se dibuja la columna
		$this->Rect(10,self::comienzoTabla,30,self::limitePagina);

		//columna nombre
		//se posiciona el cursor
		$this->setXY(30,self::comienzoTabla);
		//se hace un cuadro de texto 
		$this->MultiCell(100,5,'Nombre',1,'C');
		// se dibuja la columna
		$this->Rect(30,self::comienzoTabla,30,self::limitePagina)

		//columna edad
		//se posiciona el cursor
		$this->setXY(130,self::comienzoTabla);
		//se hace un cuadro de texto 
		$this->MultiCell(10,5,'Edad',1,'C');
		// se dibuja la columna
		$this->Rect(30,self::comienzoTabla,30,self::limitePagina)
	}

}