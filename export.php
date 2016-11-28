<?php

include_once "vendor/fpdf/fpdf.php";

//para utilizar FPDF debemos extender su clase principal
class ExportadorPDF extends FPDF{

	private $clientes;

	const limitePagina = 240; 
	const comienzoTabla = 30;
	const alturaFila = 10;

	//meter los clientes que vamos a exportar
	public function setClientes($clientes){
		$this->clientes = $clientes;
	}

	//exportar PDF
	public function exportar(){

		//añadir primer pagina
		$this->AddPage();

		//se recorre a los clientes
		foreach ($this->clientes as $cliente) {
			$this->exportarCliente($cliente);
			//se verifica si se necesita o no una nueva página
			$this->analizarSaltoDePagina();
		}

		//La opción D es para forzar la descarga del archivo.
		$this->Output('D','clientes_'.date('Y-m-d').'.pdf');
	}
	
	private function analizarSaltoDePagina(){
		//si la posición X del documento supera el límite de salto de pagina
		if($this->GetY()>self::limitePagina - self::alturaFila){
			//se añade una nueva página
			$this->AddPage();	
		}
	}


	//dibujar cada fila de cliente
	public function exportarCliente($cliente){

		$starty = $this->GetY();
		//formato de la fuente (fuente,estilo,tamaño)
		$this->SetFont('Arial','',10);

		//columna identificador cliente
		//se posiciona el cursor
		$this->setXY(10,$starty);
		//se convierde de UTF-8 a windows-1252 porque FPDF no acepta UTF-8
		$this->Cell(20,self::alturaFila, iconv('UTF-8', 'windows-1252', $cliente->id_cliente),'T','C');

		//columna nombre
		//se posiciona el cursor
		$this->setXY(30,$starty);
		//se hace un cuadro de texto 
		$this->Cell(100,self::alturaFila, iconv('UTF-8', 'windows-1252', $cliente->nombre),'T','L');

		//columna edad
		//se posiciona el cursor
		$this->setXY(130,$starty);
		//se hace un cuadro de texto 
		$this->Cell(20,self::alturaFila, iconv('UTF-8', 'windows-1252', $cliente->edad) ,'T','C');

		//salto de línea
		$this->Ln();

	}

	//sobrecargamos la funcion AddPage para que cada vez que se agrega una página
	public function AddPage(){
		//llamamos a la creación de paginas de FPDF y luego añadimos nuestra funcinalidad
		parent::addPage();

		//se dibuja la tabla
		$this->dibujarTabla();
		//se posiciona en el comienzo de la tabla
		$this->starty = self::comienzoTabla;
	}

	//funcion que dibuja la tabla en cuestión
	private function dibujarTabla(){


		//formato de la fuente (fuente,estilo,tamaño)
		$this->SetFont('Arial','B',10);

		//columna identificador cliente
		//se posiciona el cursor
		$this->setXY(10,self::comienzoTabla);
		//se hace un cuadro de texto (ancho, alto, texto, bordes, alineación del texto)
		$this->MultiCell(20,5,'ID',1,'C');
		// se dibuja la columna (x,y,ancho,alto)
		$this->Rect(10,self::comienzoTabla,20,self::limitePagina-self::comienzoTabla);

		//columna nombre
		//se posiciona el cursor
		$this->setXY(30,self::comienzoTabla);
		//se hace un cuadro de texto 
		$this->MultiCell(100,5,'Nombre',1,'C');
		// se dibuja la columna
		$this->Rect(30,self::comienzoTabla,100,self::limitePagina-self::comienzoTabla);

		//columna edad
		//se posiciona el cursor
		$this->setXY(130,self::comienzoTabla);
		//se hace un cuadro de texto 
		$this->MultiCell(20,5,'Edad',1,'C');
		// se dibuja la columna
		$this->Rect(130,self::comienzoTabla,20,self::limitePagina-self::comienzoTabla);
	}

	//sobrecargar la funcion Header para imprimir un encabezado

	public function Header(){
		$this->SetFont('Arial','B',16);
		$this->setXY(10,10);
		$this->Cell(200,10, 'PRUEBA SALIDA',0,'C');
	}

}