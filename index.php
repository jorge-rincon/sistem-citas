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
                <form action="controller/CitaController.php" method="post" class="row g-3">
                    <div class="col-md-6">
                        <label for="inputNombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="inputNombre" required>
                    </div>
                    <div class="col-md-6">
                        <label for="inputDni" class="form-label">DNI</label>
                        <input type="text" class="form-control" name="dni" id="inputDni" required>
                    </div>
                    <div class="col-md-6">
                        <label for="inputTelef" class="form-label">Telefono</label>
                        <input type="text" class="form-control" name="telefono" id="inputTelef" required>
                    </div>
                    <div class="col-md-6">
                        <label for="inputEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" name="correo" id="inputEmail" required>
                        <div class="invalid-feedback">
                            Formato de correo invalido
                        </div>
                        <div class="valid-feedback">
                            Formato de correo valido
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="inputState" class="form-label">Tipo cita</label>
                        <select id="inputState" name="tipoCita" class="form-select" required>
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
        //validamos la existencia del DNI
        $("#inputDni").blur(function() {
            $.post("controller/ajax/pacienteAjax.php", {
                    DNI: $("#inputDni").val()
                },
                function(data, status) {
                    const row = JSON.parse(data)
                    $("#inputState").empty();
                    if (row.status === 200) {
                        $("#inputState").append($("<option>", {
                            value: row.value,
                            text: row.text
                        }))
                    }

                });
        });

        //validamos campo email
        const email = document.getElementById("inputEmail");
        // expresion regular para validar correo
        const validEmail = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
        email.addEventListener('blur', (e) => {
            let inEmail = email.value
            if (validEmail.test(inEmail)) {
                email.classList.remove('is-invalid');
                email.classList.add('is-valid');
            } else {
                email.classList.add('is-invalid');
            }
        })

    });
</script>

</html>