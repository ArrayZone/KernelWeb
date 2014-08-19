<?php
include 'filevalidator.php';
include '../mimereader/mimereader.php';

echo '$_FILES: ';
print_r($_FILES);
echo '<hr />';

// Generate a new fileValidator
$fv = new fileValidator();

// If you like validate an existent file or manually file uploaded
//$fv->validateFile('test.png', 'image');
if ($_FILES) {
	if ($fv->validateUploadFile($_FILES['test'], 'image')) {
		echo 'El archivo es una imagen compatible';
	} else {
		echo 'El archivo NO es una imagen compatible';
	}	
}

?>

<form method="post" enctype="multipart/form-data">
	<input type="file" name="test" />
	<input type="submit">
</form>