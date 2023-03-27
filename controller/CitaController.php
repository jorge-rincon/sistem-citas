<?php

include_once('../model/DatosCita.php');
include_once('../controller/PacienteController.php');

class CitaController{

    /**
     * Variable que almacena un objeto de tipo DatosUsuario.
     *
     * @var datosCita
     * @var pacienteController
     */
    private $datosCita;
    private $pacienteController;

    public function __construct() {
        //$this->datosCita = new DatosCita();
        $this->pacienteController = new PacienteController();
    }

    /**
     * Metodo que inserta una cita en el sistema
     * 
     * @var request
     */
    public function insertarCita($request)
    {
        $dni = $this->pacienteController->validateDNI($request);
        echo'<pre>';print_r($dni);echo'</pre>';
    }



}

echo'<pre>';print_r($_POST);echo'</pre>';

/*$cita = new CitaController();
$cita->insertarCita(1);*/