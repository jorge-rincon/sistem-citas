<?php

$document_root = $_SERVER['DOCUMENT_ROOT'];
include_once $document_root . '/data/Config.php';

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

    public function __destruct() {
        $this->cfg;
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
            $query = "SELECT * FROM pacientes as pa where pa.DNI =" . $dni;
            $instancia = $conexion->prepare($query);
            $instancia->execute();
            $row = $instancia->fetchAll();
            
            if (empty($row)) {
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

    /**
     * Metodo que consulta un paciente por el DNI
     */
    public function traerPacientePorDNI($dni)
    {   
        try {
            
            $cfgDB = $this->cfg->configDB();
            //abrimos la conexion a la BD
            $dsn = 'mysql:host=' . $cfgDB['db']['host'] . ';dbname=' . $cfgDB['db']['name'];
            $conexion = new PDO($dsn, $cfgDB['db']['user'], $cfgDB['db']['pass'], $cfgDB['db']['options']);
            //escribimos la consulta
            $query = "SELECT * FROM pacientes as pa where pa.DNI =" . $dni;
            $instancia = $conexion->prepare($query);
            $instancia->execute();
            $row = $instancia->fetchAll();            
            
            return $row;

        } catch (PDOException $error) {

            $statusTransaction['msm'] = $error->getMessage();
            $statusTransaction['status'] = 404;
            return $statusTransaction;
        }
        
    }

    /**
     * Metodo que inserta un paciente, retorna el id del registro creado
     */
    public function insertarPaciente($paciente)
    {   
        try {
            
            $cfgDB = $this->cfg->configDB();
            //abrimos la conexion a la BD
            $dsn = 'mysql:host=' . $cfgDB['db']['host'] . ';dbname=' . $cfgDB['db']['name'];
            $conexion = new PDO($dsn, $cfgDB['db']['user'], $cfgDB['db']['pass'], $cfgDB['db']['options']);
            //escribimos la consulta
            $query = "INSERT INTO pacientes (nombre, dni, telefono, correo)";
            $query .= "values (:" . implode(", :", array_keys($paciente)) . ")";            
            $instancia = $conexion->prepare($query);
            $instancia->execute($paciente);
            $paciente_id = $conexion->lastInsertId();
            
            return $paciente_id;

        } catch (PDOException $error) {

            $statusTransaction['msm'] = $error->getMessage();
            $statusTransaction['status'] = 404;
            $statusTransaction['error'] = $error;
            return $statusTransaction;
        }
        
    }



}
