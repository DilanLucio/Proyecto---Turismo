<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar</title>
    <link rel="stylesheet" href="Imagenes/Consulta.css">
</head>

<header>
    <h1>Consultar Reservas</h1>
    <nav>
        <ul>
            <li><a href="../Index.html">Inicio</a></li>
            <li><a href="AdminUTP.html">Mis Reservas</a></li>
            <li><a href="">Contacto</a></li>
            <li><a href="../login.html">Salir</a></li>
        </ul>
    </nav>
</header>


<body>
    <div class="container">
        <form method="post">
            <label for="fecha">Filtrar por Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fechaSeleccionada; ?>">

            <label for="nombre">Filtrar por Nombre:</label>
            <input type="text" id="nombre" name="nombre">

            <label for="telefono">Filtrar por Teléfono:</label>
            <input type="text" id="telefono" name="telefono">

            <input type="submit" value="Filtrar">
        </form>

        <?php
        $fechaSeleccionada = date('Y-m-d');

        if (isset($_POST['fecha'])) {
            $fechaSeleccionada = $_POST['fecha'];
        }

        $serverName = "SqlServer";
        $connectionOptions = array(
            "Database" => "DataBase",
            "Uid" => "UserName",
            "PWD" => "Password"
        );

        $conn = sqlsrv_connect($serverName, $connectionOptions);

        if ($conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $query = "SELECT NombreCompleto, CorreoElectronico, NumeroCelular, FechaReservacion, NumeroPersonas, Hora
          FROM ReservasUTP
          WHERE 1=1";  
        
        $params = array();

        if (!empty($_POST['fecha'])) {
            $query .= " AND FechaReservacion = ?";
            $params[] = $_POST['fecha'];
        }

        if (!empty($_POST['nombre'])) {
            $query .= " AND NombreCompleto LIKE ?";
            $params[] = '%' . $_POST['nombre'] . '%';
        }

        if (!empty($_POST['telefono'])) {
            $query .= " AND NumeroCelular LIKE ?";
            $params[] = '%' . $_POST['telefono'] . '%';
        }

        $result = sqlsrv_query($conn, $query, $params);

        if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        echo "<div class='container'>";

        if (sqlsrv_has_rows($result)) {
            echo "<table border='1'>
        <tr>
            <th>Nombre Completo</th>
            <th>Correo Electrónico</th>
            <th>Número Celular</th>
            <th>Fecha de Reservación</th>
            <th>Número de Personas</th>
            <th>Hora de Reservacion</th>
            <th>Eliminar</th> 
        </tr>";

            while ($row = sqlsrv_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['NombreCompleto']) . "</td>";
                echo "<td>" . htmlspecialchars($row['CorreoElectronico']) . "</td>";
                echo "<td>" . htmlspecialchars($row['NumeroCelular']) . "</td>";
                echo "<td>" . htmlspecialchars($row['FechaReservacion']->format('Y-m-d')) . "</td>";
                echo "<td>" . htmlspecialchars($row['NumeroPersonas']) . "</td>";
                $hora = date_format($row['Hora'], 'H:i:s');
                echo "<td>" . htmlspecialchars($hora) . "</td>";
                echo "<td><button onclick=\"eliminarReserva('" . htmlspecialchars($row['NombreCompleto']) . "', '" . htmlspecialchars($hora) . "','" . htmlspecialchars($row['CorreoElectronico']) . "')\">Eliminar</button></td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No se encontraron reservas.</p>";
        }

        echo "</div>";

        sqlsrv_close($conn);
        ?>


        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <script>

            function eliminarReserva(nombreCompleto, horaReserva, correoElectronico) {
                if (confirm('¿Estás seguro de que deseas eliminar la reserva de ' + nombreCompleto + ' a las ' + horaReserva + '?')) {
                    // Utilizar AJAX para enviar una solicitud al servidor y manejar la eliminación en el lado del servidor.
                    $.ajax({
                        type: "POST",
                        url: 'ERUTP.php',
                        data: {
                            nombreCompleto: nombreCompleto,
                            horaReserva: horaReserva,
                            CorreoElectronico: correoElectronico
                        },
                        success: function (data) {
                            if (data.success) {
                                alert('Reserva eliminada: ' + nombreCompleto + ' a las ' + horaReserva);
                                // Puedes actualizar la interfaz de usuario aquí si es necesario.
                                window.location.reload(); // Recargar la página después de eliminar
                            } else {
                                alert('Error al eliminar la reserva: ' + data.error);
                            }
                        },
                        error: function () {
                            alert('Error en la solicitud AJAX');
                        }
                    });
                }
            }
        </script>
    </div>
</body>

</html>