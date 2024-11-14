<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["Correo"];
    $password = $_POST["Password"];
    $trabajo = $_POST["Trabajo"];

    $serverName = "ServerSql";
    $connectionOptions = array(
        "Database" => "DataBase",
        "Uid" => "Username",
        "PWD" => "Password"
    );

    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if (!$conn) {
        die("Error en la conexión a SQL Server: " . print_r(sqlsrv_errors(), true));
    }

  
    $sql = "SELECT Correo, Password, Trabajo FROM Usuarios WHERE Correo = ?";
    $params = array($correo);
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if (!$stmt) {
        die("Error al preparar la consulta: " . print_r(sqlsrv_errors(), true));
    }

    $result = sqlsrv_execute($stmt);

    if ($result === false) {
        die("Error al ejecutar la consulta: " . print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt);

    if ($row) {
  
        $storedPassword = $row['Password'];
        $storedTrabajo = $row['Trabajo'];

        if ($password === $storedPassword) {
         
            switch ($storedTrabajo) {
                case "HaciendaM":
                    header("Location: Administrador/AdminHacienM.html");
                    break;
                case "Perote":
                    header("Location: Administrador/AdminPerote.html");
                    break;
                case "SanMiguel":
                    header("Location: Administrador/AdminRSanMiguel.html");
                    break;
                case "TacoTorta":
                    header("Location: Administrador/AdminTacoTorta.html");
                    break;
                case "UTP":
                    header("Location: Administrador/AdminUTP.html");
                    break;
                case "Pudencianas":
                    header("Location: Administrador/AdmiPudencianas.html");
                    break;
                default:
                    echo "Lugar de trabajo incorrecto. Verifica tu lugar de trabajo.";
                    break;
            }
        } else {
            echo "<script>
            alert('Contraseña incorrecta, verifica tu contraseña.');
            window.location.href = 'Login.html';
            </script>";
           
        }
    } else {
        echo "<script>alert('Usuario no encontrado, Verifica tus credenciales.');
        window.location.href = 'Login.html';
        </script>";

    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>
