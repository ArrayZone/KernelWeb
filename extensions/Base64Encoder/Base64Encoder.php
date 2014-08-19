<?php
/**
 * @name Base64Encoder This class can do/undo base64 in files
 * 	It skip files who contain "</" or "/>" to avoid html content (it don't recognize comments, so take care)
*/
class Base64Encoder {
	/**
	 * @var string $source Specify directory source (with end slash "/")
	 */
	public $source;
	
	/**
	 * @var string $desst Specify directory destination (with end slash "/")
	 */
	public $dest;
	
	/**
	 * @var array $extensions Specify extensiosn to encode
	 */
	public $extensions = array('.php');
	
	/**
	 * @var excludedFiles Specify files and directory to ignore (important specify "." and "..")
	 */
	public $excludeFiles = array('.', '..', 'Thumbs.db');
	
	/**
	 * $param array folderEncrypt
	 * Specify whichs folders will be encripted (it don't work recursively, so if you like encryt "admin/folder1" you have to put
	 * "admin/folder1" in the array
	 * With '' it will encrypt the main php files in directory
	 */
	public $foldersEncrypt;
	
	/**
	 * @var array $foldersSkip
	 * skip the folders if it exist in the new folder (it would be useful for images for example)
	 * This parameter only be used when a folder wouldn't be copied 
	 */
	public $foldersSkip;
	
	
	/**
	 * @name execute This function read directory base (without $source or $dest) and launch base64_encode/decode with all files depending vars
	 * @param $subdir Directory/Subdirectory to read
	 * @param $action Action to do (encode/decode)
	*/
	public function execute($action = 'encode', $subdir = '') {
		// Try to open the folder
		$source = $this->source;
		$dest = $this->dest;
		$foldersEncrypt = $this->foldersEncrypt;
		$extensions = $this->extensions;
		$excludeFiles = $this->excludeFiles;
		
		if ($reader = opendir($source . $subdir)) {
			// Reading all content
			while (false !== ($file = readdir($reader))) {
				if (!in_array($file, array('.', '..'))) {
					$fullfile = $subdir . $file;
					if (!in_array($file, $excludeFiles)) {
						if (is_dir($source . $fullfile) and in_array($file, $foldersEncrypt)) {
							// If is a folder will be recreate directory and read all content
							@mkdir($dest . $fullfile, 0777, true);
							$this->execute($action, $fullfile .'/');
							chmod($dest . $fullfile, fileperms($source . $subdir));
						} elseif (is_file($source . $fullfile)) {
							
							// If is file, test extension
							$ext = substr($file, stripos($file, '.'));
							if (in_array($ext, $extensions)) {
								// Coding the file
								$this->$action($fullfile);
							} else {
								// Only coping (if not excluded)
								copy ($source . $fullfile, $dest . $fullfile);
							}
						} else {
							// Copying directorys recursively
							$this->recurse_copy($source . $fullfile, $dest . $fullfile);
						}
					}
				}
			}
		} else {
			die('Dir ' . $this->source . $subdir .' could not be open');
		}
	}
	
	
	/**
	 * @name recurseCopy This function copy a directory recursively with him content and subfolders
	 * @author gimmicklessgpt at gmail dot com
	 * @url http://php.net/manual/en/function.copy.php
	*/
	private function recurse_copy($src,$dst) { 
		$dir = opendir($src); 
		$excludeFiles = $this->excludeFiles;
		
		// Creating directory destination with full permisions to avoid problems copying files
		if (!is_dir($dst)) @mkdir($dst, 0777, true);
		// Skiping directories to "skip"
		elseif (in_array($dst, $this->foldersSkip)) return true;
		
		while(false !== ( $file = readdir($dir)) ) { 
			if (!in_array($file, $excludeFiles)) {
				if ( is_dir($src . '/' . $file) ) { 
					$this->recurse_copy($src . '/' . $file, $dst . '/' . $file); 
				} else { 
					copy($src . '/' . $file, $dst . '/' . $file); 
				}
			} 
		}
		closedir($dir); 
		
		// Now changing permissions of folder
		chmod($dst, fileperms($src));
	}

	
	/**
	 * @name encode This function encode a file
	 * @param string $file Full path to the file (without $source or $dest)
	 */
	private function encode($file) {
		// You can modify and add some sentences to the files (but this function only will be loaded when a directory must be encrypted)
		// If you do this, you must modify "decode" later
		
		// Saving file in new file codificated
		$data = file_get_contents($this->source . $file);
		
		// Only encode if not have HTML code and have "<?php" in some part of the code
		if (strpos($data, '</') === FALSE and strpos($data, '/>') === FALSE and strpos($data, '<?php') !== FALSE) {
			// Deleting '<?php' because it generate error sometimes into eval() and is php tag started
			$data = str_replace('<?php', '/*-<?php-*/', $data);
			
			// Preparing file codified
			$data = "<?php eval(base64_decode(\"".base64_encode($data)."\")); ?>";
		}
		
		
		// Saving file (codified or uncodefied)
		file_put_contents($this->dest . $file, $data);
	}
	
	/**
	 * @name encode This function decode a file
	 * @param string $file Full path to the file (without $source or $dest)
	 */
	private function decode($file) {
		// Saving file in new file decodificated
		$data = file_get_contents($this->source . $file);

		// Only decode if it have '<?php eval(base64_decode'
		if (strpos($data, '<?php eval(base64_decode(') !== FALSE) {
			// obtaining text withouth eval and decode
			$data = base64_decode(Stringer::getStringBetween($data, '<?php eval(base64_decode("', '")); ?>'));
			
			// Recovering <?php 
			$data = str_replace('/*-<?php-*/', '<?php', $data);
		}
		
		// Saving file (codified or uncodefied)
		file_put_contents($this->dest . $file, $data);
	}
}