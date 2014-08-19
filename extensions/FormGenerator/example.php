<style>
	form { display:table; }
	form > div { display:table-row; }
	form > div > div { display:table-cell; }
</style>
<?php
include 'formgenerator.php';


function LoadData() {
	// First mount array with parameters
	$fr = new formGenerator();
	$fr->logErrors = true; // It log errors when validate
	$fr->addTextArea('Dsadas','hidden', '', 'nothing');
	$fr->addInput('','hidden', 'UserID', 'nothing');
	$fr->addInput('Usuario', 'text', 'username', '', true, 'Usuario', array('class' => 'prueba'));
	$fr->addInput('Password:', 'password', 'password');
	$fr->addInput('Email', 'email', 'email', '', false, 'Tu email');
	$fr->addInput('Tu web', 'url', 'tu_web', 'http://', false, 'Web personal');
	$fr->addSeparator();
	
	$fr->addNumber('Edad', 'age', '', false, false, 2, 100, 2, false);
	$fr->addNumber('Salario', 'salario', '', true,  false, 2, 100, 2);
	$fr->addDate('Fecha', 'fecha', '', false,  false, '', '', '', false);
	$fr->addSeparator();
	
	$fr->addSelect('RANK', 'RANK', array('test','test2'));
	
	
	// Some option autocreated, Is required use addRadio, NOT addINPUT
	// in this case, the value is the same that text to show, so key is optional (is incremental)
	$fr->addRadio('Valoration', 'valoration', array(
			0=>'0',
			5=>'5',
			10=>'10'
	), null, true);
	
	$fr->addCheck('Accept conditions', 'accept conditions', '1');
	$fr->addSeparator();
	
	$fr->addSubmit('send', 'Enviar');

	return $fr;
}

// Load all form configuration
$fr = LoadData();

// Process data
if ($_POST) {
	// Checking reply
	if (!$fr->validate()) {
		echo 'unvalidated';
		echo '<br /><b>ERRORS:</b><br />'.$fr->errors;
	} else {
		echo 'validated';
		// Now you can get values and save on you like
		// or if is to manipule a database, you can use the module "FormGeneratorDB"
	}
	echo '<hr />';
}

// Show form everytime
$fr->showForm();
?>
