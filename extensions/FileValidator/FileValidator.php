<?php /**
 * @name fileValidator for Kernel Web
 * @version 1.0
 * @copyright ArrayZone 2014
 * @license AZPL or later; see License.txt or http://arrayzone.com/license
 * @category plugin
 * 
 * Description: Este script valida archivos segun su extension y MIME
 * Se pueden validar archivos directamente desde el $_FILE (pasando el array) o archivos subidos.
 * 
 * FUNCIONES A UTILIAR POR UN DESARROLLADOR:
 * validateFile('rutaArchivo', 'TipoEsperado', 'TestExtension?');
 * validateUploadFile($_FILE['indice'], 'TipoEsperado', 'TestExtension?');
 * 
 * Para saber que extensiones y tipos de mime son soportados revisa "public $extensions" y "function validate"
 * 
 * THIS PLUGIN REQUIRE "mimeReader" PLUGIN
 */

class FileValidator {
	public $log = '';
	
	/** 
	 * @rejectExtensions array Extensiones que seran rechazadas de forma automatica (se comprueben o no las extensiones)
	 */
	public $rejectExtensions = array(
		'php', 'php3', 'php4', 'php5', 'exe', 'htaccess', 'htpasswd'
	);
	
	/**
	 * @param array $extensions Contain all extensions compatible to each type
	 * You can add all you want here or specifiy when class is declarated
	 */
	public $extensions = array(
		'image' => array(
			'jpg', 'jpeg', 'jfif', 'ief', 'png', 'gif', 'ico'
		),
		'zip' => array(
			'zip'
		)
	);
	

	/**
	 * @name validateFile Comprueba un ARCHIVO que este en el DISCO
	 * Si quieres comprobar archivos subidos puedes utilizar "validateUploadedFile" para agilizar la carga
	 * @param string $file Path to file to validate
	 * @param string/array $type Especifica el/los tipos de ficheros que se aceptan
	 */
	public function validateFile($file, $type = '', $testExtensions = true) {
		$mime = new MimeReader($file);
		return $this->validate($file, $mime->get_type(), $type, $testExtensions);
	}

	/**
	 * @name validateUploadFile Comprueba un archivo SUBIDO
	 * Para que esto funcione se le debe pasar el $_FILE[] del fichero correspondiente
	 * @param array $FILE
	 * @param string/array $type Especifica el/los tipos de ficheros que se aceptan
	 */
	public function validateUploadFile($file, $type = '', $testExtensions = true) {
		return $this->validate($file['name'], $file['type'], $type, $testExtensions);
	}
	
	
	/**
	 * @name Validate Valida 
	 * @param string $name
	 * @param string $mime Especifica el mime del archivo que se esta enviando
	 * @param string/array $type Especifica el/los tipos de ficheros que se aceptan.
	 * Actualmente compatibles:
	 * 	- image
	 *	- text
	 *	- zip
	 * 	
	 * Si esta vacio solo se comprobara que no este en "rejectExtensions"
	 * @param boolean $testExtensions If is on, extensions will be checked
	 */
	public function validate($name, $mime, $type, $testExtensions = true) {
		// Sacamos la extension del archivo
		$extension = substr($name, stripos($name, '.') + 1);

		// Primero revisamos que no sea una extension ilegal
		if (in_array($extension, $this->rejectExtensions)) return false;
		
		if ($type != '' and $testExtensions) {
			if (isset($this->extensions[$type])) {
				if (!in_array($extension, $this->extensions[$type])) return false;			
			} else {
				die('El tipo "'.$type.'" solicitado en el validador de archivos no existe. Modificalo o añadelo.');
			}
		}
		
		// Comprobamos el MIME
		// La comprobacion es un poco hardcore, todo sea dicho, solo se valida una parte del mime
		// EJ: de image/jpeg no se valida el jpeg, solo que ponga  "image"
		// Se podria validar el "jpeg" añadiendo la extension, pero en algunos casos podemos encontrar imagenes que la extension
		// original ha sido modificada, aún y asi la imagen es correcta
		if (strpos($mime, $extension) !== FALSE) return true;
		
		return false;
	}

}