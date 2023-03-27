<?php

include($document_root . '/data/Config.php');

class DatosPaciente{

    /**
     * Variable que almacena un objeto de tipo Config.
     *
     * @var conect
     * @var configDb
     */
    private $cfg;

    public function __construct() {
        $this->cfg = new Config();
    }

    /**
     * Metodo que consulta en la BD si el DNI existe
     */
    public function validateDNI($dni)
    {   
        $statusTransaction = array();

        try {
            
            $cfgDB = $this->cfg->configDB();
            //abrimos la conexion a la BD
            $dsn = 'mysql:host=' . $cfgDB['db']['host'] . ';dbname=' . $cfgDB['db']['name'];
            $conexion = new PDO($dsn, $cfgDB['db']['user'], $cfgDB['db']['pass'], $cfgDB['db']['options']);
            //escribimos la consulta
            $query = "SELECT * FROM paciente as pa where pa.DNI =" . $dni;
            $instancia = $conexion->prepare($query);
            $row = $instancia->execute();

            if ($row['dni'] !== 0) {
                $statusTransaction['value'] = 'primeraConsulta';
                $statusTransaction['text'] = 'Primera consulta';
                $statusTransaction['status'] = 200;
            }else{
                $statusTransaction['value'] = 'revision';
                $statusTransaction['text'] = 'Revision';
                $statusTransaction['status'] = 200;
            }


            return $statusTransaction;

        } catch (PDOException $error) {

            $statusTransaction['msm'] = $error->getMessage();
            $statusTransaction['status'] = 404;
            return $statusTransaction;
        }
        
    }



}
