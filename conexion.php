<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["Correo"]) && isset($_POST["Password"]) && isset($_POST["Trabajo"])) {
        $Correo = $_POST["Correo"];
        $Password = $_POST["Password"];
        $Trabajo = $_POST["Trabajo"];

        $serverName = "SqlServer";
        $connectionOptions = array(
            "Database" => "DataBase",
            "Uid" => "Username",
            "PWD" => "Password"
        );
        $conn = sqlsrv_connect($serverName, $connectionOptions);

        if (!$conn) {
            die("Error en la conexión a SQL Server: " . sqlsrv_errors());
        }

        $checkEmailQuery = "SELECT Correo FROM Usuarios WHERE Correo = ?";
        $params = array($Correo);
        $checkEmailStmt = sqlsrv_query($conn, $checkEmailQuery, $params);

        if (sqlsrv_fetch($checkEmailStmt)) {
            echo "<script>alert('El correo electrónico ya está registrado.');
            window.location.href = 'Login.html';
            </script>";
        } else {
            $insertQuery = "INSERT INTO Usuarios (Correo, Password, Trabajo) VALUES (?,?,?)";
            $params = array($Correo, $Password, $Trabajo);
            $insertStmt = sqlsrv_query($conn, $insertQuery, $params);

            if ($insertStmt === false) {
                $errors = sqlsrv_errors();
                $errorMessages = [];

                foreach ($errors as $error) {
                    $errorMessages[] = "SQLSTATE: " . $error['SQLSTATE'] . ", Code: " . $error['code'] . ", Message: " . $error['message'];
                }

                die("Error en la inserción de datos: " . implode("<br>", $errorMessages));
            } else {
                echo "<script>alert('Registro Exitoso');
                window.location.href = 'Login.html';
                </script>";
            }
        }

        sqlsrv_close($conn);
        exit();
    } else {
        echo "<script>alert('Complete todos los campos.');
        window.location.href = 'Login.html';
        </script>";
    }
}
?>
