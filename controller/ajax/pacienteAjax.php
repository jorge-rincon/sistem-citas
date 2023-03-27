<?php

$document_root = $_SERVER['DOCUMENT_ROOT'];
include($document_root . '/controller/PacienteController.php');

$pacienteController = new PacienteController();
$pacienteController->validateDni($_POST['DNI']);