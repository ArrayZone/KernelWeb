<?php /**
 * @name BasicXML for Kernel Web
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category plugin
 * 
 * Description: This script can read XML to array and save array to XML
 * This class is some slow, so you only use this when you really need and have problems reading XML
 * 
 * STATUS: In progress of translation
 */
/* Basix XML
	NOTA: Si la clave empieza por NUMERO no se usara esa clave y se usara solo el contenido (ver ejemplos)
	http://foro.arrayzone.com/index.php?topic=52
*/

/*
	EJEMPLOS MULTIPLES (Array a XML):
	
	// XML Simple
	$mixml = array(
		'tag' => array(
			'id' => '1',
			'nombre => 'prueba')
		);
	
	RESULTADO
		<raiz>
			<tag>
				<id>1</id>
				<nombre><![CDATA[prueba]]></nombre>
			</tag>
		</raiz>
		
	
	
	// XML con las claves NUMERICAS
	$mixml = array(
		1 => array(
			'apartado' => array(
				'id' => '1',
				'texto' => array(
					'es' => 'prueba',
					'cat' => 'prova',
				),
			)
		),
		2 => array(
			'apartado' => array(
				'id' => '5',
				'texto' => array(
					'es' => 'prueba5',
					'cat' => 'prova5',
				),
			),
		)
	);
	
	
	RESULTADO
	Podemos ver como las claves que empezaban por numero han sido omitidas sus etiquetas
	Esto es util si queremos que haya varias etiquetas "apartado" (ya que si existe un array con 2 claves iguales se reemplazan entre ellas)
		<raiz>
			<apartado>
				<id>1</id>
				<texto>
					<es>prueba</es>
					<cat>prova</cat>
				</texto>
			</apartado>
			<apartado>
				<id>5</id>
				<texto>
					<es>prueba5</es>
					<cat>prova5</cat>
				</texto>
			</apartado>
		</raiz>
	
*/
class BasicXML {

/* 
 *Escritura de XML
*/

	/* 
		Funcion que exporta un array a XML
		$root = Etiqueta raiz del XML
		$array = array con subarrays con la estructura del xml
			EJ:
				$array = ('Etiqueta1' => array('SubEtiqueta1' => 'Prueba'));
				
		$tabular = Si es true, se pondran saltos de linea \n y tabulaciones \t (para dejar mas limpio el XML), 
			NO RECOMENDADO SI EL ARCHIVO NO LO LEERA UN HUMANO o una aplicacion especifica
			
		$file_output = Fichero donde se guardara el XML (si no se define ninguno, se devovlera el HTML, si no, devolvera true o false si consigue o no guardar el archivo)
		
		
		
		BasicXML::array_to_xml('prueba', $test, false, '../apartados/generico/test.xml');
	*/
	function array_to_xml($root, $array, $tabular = false, $file_output = '') {
		if ($file_output != '') {
		
			if ($fl = fopen($file_output, 'w+')) {
				if (!fwrite($fl, '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL.'<'.$root.'>'.BasicXML::array_to_xml_recursive($array, $tabular, 1).PHP_EOL.'</'.$root.'>'))
					return false;	
				if (!fclose ($fl)) return false;
				
			} else return false;
		} else return $xml;
	}
	
	
	/*
		Funcion que hace de forma recursiva la lectura de array y la pasa a HTML
		Esta funcion es usada en "array_to_xml" para generar el archivo
		SOLO DEVUELVE EL HTML (utilizada en la funcion "array_to_xml")
		NO ES RECOMENDABLE USAR, si quieres la cabecera XML
		
		AVISO: En la primera linea devuelve un salto de linea (PHP_EOL) si $tabular=true, ese salto se aprovecha arriba para poner la etiqueta root...
			Ves con cuidado si utilizas directamente esta funcion
			
		ADEMAS: Algunas variables receptoras son diferentes
	*/
	function array_to_xml_recursive($array, $tabular = false, $subnivel = 0) {
		
		$return = '';
		
		// Preparamos la tabulacion (si la queremos)
		if ($tabular) $tab = PHP_EOL . (($subnivel>0) ? str_repeat("\t", $subnivel) : '');
		else $tab = '';

		// Recorremos el array
		
		foreach ($array as $key=>$value) {
			// Dependiendo de si esta vacio, tiene otro array o tiene solo texto haremos una cosa u otra
			if (empty($value)) $add = '';
			elseif (is_array($value)) $add = BasicXML::array_to_xml_recursive($value, $tabular, $subnivel+1).$tab;
			elseif (is_numeric($value)) $add = $value;
			else $add = '<![CDATA['.$value.']]>';

			if (ctype_alpha($key[0])) { // si el PRIMER caracter NO es NUMERICO
				// Ahora montamos las etiquetas, si no lo queremos tabulado y no tiene contenido, sera una etiqueta autocerrada
				$return .= $tab.'<'.$key;
				if ($tabular or $add != "") $return .= '>'.$add.'</'.$key.'>';
				else $return .='/>';
			} else $return .= $add;
		}
		
		return $return;
	}
	
	
	
/* 
 * Lecutara de XML
*/
	/*
		Funcion que lee un archivo XML y devuelve el contenido en un array
		
		$read_cdata: si lo ponemos en FALSE NO leera el contenido de los <![CDATA[]]>
		No recomendado si el XML se ha generado con esta Clase
		
		Retorna FALSE si el archivo no existe
	*/
	function file_xml_to_array($file, $read_cdata = true) {
		$libxml = $read_cdata ? LIBXML_NOCDATA : '';
		// Cargamos el contenido del XML y LEEMOS LOS CDATA
		if (!is_file($file)) return false;
		$xml = simplexml_load_file($file, 'SimpleXMLElement', $libxml);
		return BasicXML::xml_to_array($xml);
	}
	
	/*
		Esta funcion es exactamente igual que "file_xml_to_array" pero en lugar de leer un archivo lee un string o un object xml
		
		Devuelve un Array VACIO si no hay nada en el string o es ilegible
	*/
	function string_xml_to_array($xml, $read_cdata = true) {
		$libxml = $read_cdata ? LIBXML_NOCDATA : '';
		// Cargamos el contenido del XML y LEEMOS LOS CDATA
		if (!is_object($xml) and $xml == "") return array();
		
		if (!is_object($xml)) $xml = simplexml_load_string($xml, 'SimpleXMLElement', $libxml);
		return BasicXML::xml_to_array($xml);
	}
	
	/*
		Funcion que dado un string en formato XML devuelve un array
		ESTA FUNCION NO LEE LOS ATRIBUTOS DEL XML
		
		NOTA: Si hay una etiqueta repetida en el MISMO NIVEL se creara un array.
		EJ:
			<apartado>test</apartado>
			<apartado>test2</apartado>
			
			RESULTADO
				arary(
					'apartado' => array(
						0 => 'test',
						1 => 'test2'
					)
				);
		
		Esta funcion a diferencia de simplexml_load_file / simplexml_load_string devuelve un ARRAY y no un OBJETO el cual es "engorroso" de leer
		dado que algunas veces se debe poner por ejemplo "$temp = (string) $valor" para que devuelva el string real
		
		Esta funcion parsea y devuelve un array legible de manera rapida
		Si hay errores en el XML nos avisara para sanarlo
		
		$xml: Objeto XML
	*/
	function xml_to_array($xml) {
		// Dumpeamos los errores.
		// Dump de erores extraido de: (http://www.php.net/manual/es/simplexml.examples-errors.php)
		if ($xml === false) {
			echo "Error cargando XML:\n";
			foreach(libxml_get_errors() as $error) {
				print_r($error);
				echo "\t", $error->message , '\n';
			}
		}
		
		if (is_object($xml)) {
			return BasicXML::xml_to_array_recursive($xml);
		} 
		return array();
	}
	
	function xml_to_array_recursive($xml) {
		$return = array();
		if (count($xml) > 0) {
			foreach ((array) $xml as $key=>$value) {
				// SI es un objeto o un array con contenido lo recorremos, si no, le ponemos su valor (si esta vacio pues vacio)
				if ((is_object($value) or is_array($value)) and !empty($value)) $return[$key] = BasicXML::xml_to_array_recursive($value);
				else $return[$key] = (string) $value;
			}
		}
		
		return $return;
		
	}
}
?>