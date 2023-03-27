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
        <div class="card">
            <div class="card-body">
                <form action="controller/CitaController.php" method="post" class="row g-3" novalidate="novalidate">
                    <div class="col-md-6">
                        <label for="inputNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="inputNombre">
                    </div>
                    <div class="col-md-6">
                        <label for="inputDni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="inputDni">
                        <div class="invalid-feedback">
                            DNI no valido, ingrese uno valido
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="inputTelef" class="form-label">Telefono</label>
                        <input type="email" class="form-control" id="inputTelef">
                    </div>
                    <div class="col-md-6">
                        <label for="inputEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="inputEmail">
                    </div>
                    <div class="col-md-6">
                        <label for="inputState" class="form-label">Tipo cita</label>
                        <select id="inputState" class="form-select">
                            <option selected>Seleccione...</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Solicitar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#inputDni").blur(function() {
            $.post("controller/ajax/pacienteAjax.php", {
                    DNI: $("#inputDni").val()
                },
                function(data, status) {
                    if (data['status'] === 200) {
                        $("#inputState").append($("<option>", {
                            value: data['value'],
                            text: data['text']
                        }))
                    } else {
                        $("#inputDni").addClass('is-invalid');
                    }

                });
        });
    });
</script>

</html>