<?php

$document_root = $_SERVER['DOCUMENT_ROOT'];
include($document_root . '/model/DatosCita.php');
include_once $document_root . '/controller/PacienteController.php';
date_default_timezone_set("Europe/Madrid");

class CitaController
{

    /**
     * Variable que almacena un objeto de tipo pacienteController.
     *
     * @var datosCita
     * @var pacienteController
     */
    private $datosCita;
    private $pacienteController;
    private $desdeHora;
    private $hastaHora;

    public function __construct()
    {
        $this->datosCita = new DatosCita();
        $this->pacienteController = new PacienteController();
        $this->desdeHora = '10:00:00';
        $this->hastaHora = '22:00:00';
    }

    /**
     * Metodo que inserta una cita en el sistema
     * 
     * @var request
     */
    public function insertarCita($request)
    {

        //establecer rango de hora permitidas
        $desdeHora = strtotime($this->desdeHora);
        $hastaHora = strtotime($this->hastaHora);
        $datosInsertCita = array();

        try {

            //insertamos el paciente
            $paciente['nombre'] = $request['nombre'];
            $paciente['dni'] = $request['dni'];
            $paciente['telefono'] = $request['telefono'];
            $paciente['correo'] = $request['correo'];

            if ($request['tipoCita'] == 'primeraConsulta') {
                //obtenemos el id del paciente recien creado
                $paciente_id = $this->pacienteController->insertarPaciente($paciente);
            } else {
                //obtenemos el id del paciente exitente
                $valiPaciente = $this->pacienteController->traerPacientePorDNI($request['dni']);
                $paciente_id = $valiPaciente[0]['id'];
            }

            //consultar cual es la ultima cita
            $utlCita = $this->validarUltimaCita();
            //validamos que la ultima cita esta dentro del rango
            if (isset($utlCita[0]['fecha_cita']) && strtotime($utlCita[0]['fecha_cita']) >= $desdeHora && strtotime($utlCita[0]['fecha_cita']) <= $hastaHora) {
                //ultima cita
                $fechaUltCita = new DateTime($utlCita[0]['fecha_fin_cita']);
                //sumamos 1 hora mas a la ultima hora  y validamos que no supere el rango de $hastahora
                $fechaUltCita->modify('+1 hours');
                $nuevaHora = $fechaUltCita->format('H:i:s');
                //validar que la fecha este dentro del dia correcto
                if (strtotime($nuevaHora) > $hastaHora) {
                    //calculamos fecha nueva
                    $fechaUltCita->modify('+1 days');
                    $nuevaFecha = $fechaUltCita->format('Y-m-d');
                    //$nuevaFecha = $siguenDia->format('Y-m-d H:i:s');
                    //calculamos hora fin nueva
                    $hrFinNueva = new DateTime($this->desdeHora);
                    $hrFinNueva->modify('+1 hours');
                    $nuevaHrFin =  $hrFinNueva->format('H:i:s');
                    //asignamos cita para la primera hora del siguiente dia
                    $datosInsertCita['fecha_cita'] = $nuevaFecha . " " . $this->desdeHora;
                    $datosInsertCita['fecha_fin'] = $nuevaFecha . " " . $nuevaHrFin;
                    $datosInsertCita['hora_inicio'] = $this->desdeHora;
                    $datosInsertCita['hora_fin'] = $nuevaHrFin;
                    $datosInsertCita['tipo_cita'] = $request['tipoCita'];
                    $datosInsertCita['paciente_id'] = $paciente_id;
                } else {
                    //calculamos hora inicio nueva
                    $fecFinNueva = new DateTime($utlCita[0]['fecha_fin_cita']);
                    $hrIniNueva = $fecFinNueva->format('H:i:s');
                    //calculamos hora fin nueva
                    $fecFinNueva->modify('+1 hours');
                    $nuevaFechFin =  $fecFinNueva->format('Y-m-d H:i:s');
                    //asignamos cita para la primera hora del siguiente dia
                    $datosInsertCita['fecha_cita'] = $utlCita[0]['fecha_fin_cita'];
                    $datosInsertCita['fecha_fin'] = $nuevaFechFin;
                    $datosInsertCita['hora_inicio'] = $hrIniNueva;
                    $datosInsertCita['hora_fin'] = $nuevaHora;
                    $datosInsertCita['tipo_cita'] = $request['tipoCita'];
                    $datosInsertCita['paciente_id'] = $paciente_id;
                }
            } else {
                //primera fecha
                $fecActual = new DateTime();
                $fecFormatNueva = $fecActual->format('Y-m-d H:i:s');
                $hrFormatNueva = $fecActual->format('H:i:s');
                //fecha y hora fin
                $fechfin = $fecActual->modify('+1 hours')->format('Y-m-d H:i:s');
                $hrfin = $fecActual->format('H:i:s');
                if (strtotime($hrFormatNueva) >= $desdeHora && strtotime($hrFormatNueva) <= $hastaHora) {
                    //asignamos cita para la primera hora
                    $datosInsertCita['fecha_cita'] = $fecFormatNueva;
                    $datosInsertCita['fecha_fin'] = $fechfin;
                    $datosInsertCita['hora_inicio'] = $hrFormatNueva;
                    $datosInsertCita['hora_fin'] = $hrfin;
                    $datosInsertCita['tipo_cita'] = $request['tipoCita'];
                    $datosInsertCita['paciente_id'] = $paciente_id;
                } else {
                    //primera fecha del siguente dia
                    $siguenDia = $fecActual->modify('+1 days')->format('Y-m-d');
                    $hrFin = new DateTime($this->desdeHora);
                    $hrCitFin = $hrFin->modify('+1 hours')->format('H:i:s');
                    //asignamos cita para la primera hora del siguiente dia
                    $datosInsertCita['fecha_cita'] = $siguenDia . " " . $this->desdeHora;
                    $datosInsertCita['fecha_fin'] = $siguenDia . " " . $hrCitFin;
                    $datosInsertCita['hora_inicio'] = $this->desdeHora;
                    $datosInsertCita['hora_fin'] = $hrCitFin;
                    $datosInsertCita['tipo_cita'] = $request['tipoCita'];
                    $datosInsertCita['paciente_id'] = $paciente_id;
                }
            }

            //insertar la cita
            $respuesta = $this->datosCita->insertarCita($datosInsertCita);

            if ($respuesta) {
                return '<div class="col-md-10">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Su cita ha sido asignada para el dia: <strong>' . $datosInsertCita['fecha_cita'] . '</strong>.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>';
            } else {
                return '<div>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Su cita no se ha podido registrar</strong>.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>';
            }
        } catch (Exception $error) {

            $statusTransaction['msm'] = $error->getMessage();
            $statusTransaction['status'] = 404;

            return $statusTransaction;
        }
    }

    public function validarUltimaCita()
    {
        return $this->datosCita->ultimaCita();
    }
}

/**
 * Instancia de la clase CitaController
 */
$cita = new CitaController();
$resultado = $cita->insertarCita($_POST);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prueba Jorge</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>

<body>
    <div class="container-sm">
        <div class="card position-absolute top-50 start-50 translate-middle">
            <div class="card-body">
                <?php echo $resultado; ?>
                <a class="btn btn-primary" href="../index.php" role="button">Pedir una nueva</a>
            </div>

        </div>
    </div>
</body>

</html>