<?php
$serverName = "SqlServer";
$connectionOptions = array(
    "Database" => "DataBase",
    "Uid" => "username",
    "PWD" => "Password"
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("Error en la conexión a SQL Server: " . print_r(sqlsrv_errors(), true));
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $nuevaContraseña = $_POST["nueva_contraseña"];

   
    $sql = "SELECT * FROM Usuarios WHERE Correo = ?";
    $params = array($correo);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt);
    if ($row) {
       
        $sql = "UPDATE Usuarios SET Password = ? WHERE Correo = ?";
        $params = array($nuevaContraseña, $correo);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die("Error al actualizar la contraseña: " . print_r(sqlsrv_errors(), true));
        }

        echo "<script>
        alert('Contraseña Actualizada')
        window.location.href = 'Login.html';
        </script>";
    } else {
        echo "<script>
        alert('Usuario no encontrado, Verifica tu Correo.')
        window.location.href = 'Login.html';
        </script>";
    }
}

?>
