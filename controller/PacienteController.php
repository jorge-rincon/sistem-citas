<?php

include($document_root . '/model/DatosPaciente.php');

class PacienteController{

    /**
     * Variable que almacena un objeto de tipo DatosUsuario.
     *
     * @var datosPaciente
     */
    private $datosPaciente;

    public function __construct() {
        $this->datosPaciente = new DatosPaciente();
    }

    /**
     * Metodo que valida la existencia del DNI del paciente en el sistema
     * 
     * @var request
     */
    public function validateDni($request)
    {
        $response = $this->datosPaciente->validateDNI($request);
        echo'<pre>';print_r($response);echo'</pre>';
        return $response;
        
    }



}