<?php

$document_root = $_SERVER['DOCUMENT_ROOT'];
include_once $document_root . '/data/Config.php';

class DatosCita{

    /**
     * Variable que almacena un objeto de tipo Config.
     * @var cfg
     */
    private $cfg;

    public function __construct() {
        $this->cfg = new Config();
    }

    /**
     * Metodo que consulta ultima hora de una cita
     */

    public function ultimaCita()
    {
        try {

            $cfgDB = $this->cfg->configDB();
            //abrimos la conexion a la BD
            $dsn = 'mysql:host=' . $cfgDB['db']['host'] . ';dbname=' . $cfgDB['db']['name'];
            $conexion = new PDO($dsn, $cfgDB['db']['user'], $cfgDB['db']['pass'], $cfgDB['db']['options']);
            //escribimos la consulta
            $query = "select date_format(max(c.fecha_fin_cita), '%H:%i:%s') as fecha_cita, c.fecha_fin_cita  from citas c group by c.fecha_fin_cita order by c.fecha_fin_cita desc limit 1";
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

    public function insertarCita($cita)
    {
        try {
            $cfgDB = $this->cfg->configDB();
            //abrimos la conexion a la BD
            $dsn = 'mysql:host=' . $cfgDB['db']['host'] . ';dbname=' . $cfgDB['db']['name'];
            $conexion = new PDO($dsn, $cfgDB['db']['user'], $cfgDB['db']['pass'], $cfgDB['db']['options']);
            //escribimos la consulta
            $query = "INSERT INTO citas (fecha_cita, fecha_fin_cita, hora_inicio, hora_fin, tipo_cita,paciente_id)";
            $query .= "values (:" . implode(", :", array_keys($cita)) . ")";            
            $instancia = $conexion->prepare($query);
            $instancia->execute($cita);
            
            return true;

        } catch (PDOException $error) {

            $statusTransaction['msm'] = $error->getMessage();
            $statusTransaction['status'] = 404;
            $statusTransaction['error'] = $error;
            return $statusTransaction;
        }
    }


}
