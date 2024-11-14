<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["Correo"];
    $telefono = $_POST["Telefono"];
    $fechaReservacion = $_POST["FechaReservacion"];
    $numeroPersonas = $_POST["Ntickets"];
    $Hora = $_POST["HoraReservacion"];

    // Valida que no haya campos vacios 
    if (empty($nombre) || empty($telefono) || empty($fechaReservacion) || empty($numeroPersonas) || empty($Hora)) {
        echo "<script>
            alert('Por favor, complete todos los campos');
            window.location.href = 'UtpRecorrido.html';
            </script>";
        exit();

    }

    // Conexión a la base de datos
    $serverName = "SqlServer";
    $connectionOptions = array(
        "Database" => "DataBase",
        "Uid" => "Username",
        "PWD" => "Password"
    );
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    // Verificar la conexión
    if (!$conn) {
        die("Error en la conexión a SQL Server: " . sqlsrv_errors());
    }

    // Verificar disponibilidad de fecha
    $sql_check_date = "SELECT COUNT(*) as count FROM ReservasUTP WHERE FechaReservacion = ? and Hora = ?";
    $params_check_date = array($fechaReservacion,$Hora);
    $stmt_check_date = sqlsrv_query($conn, $sql_check_date, $params_check_date);
    
    if ($stmt_check_date === false) {
        die("Error en la comprobación de fecha: " . sqlsrv_errors());
    }
    
    $row_date = sqlsrv_fetch_array($stmt_check_date);
    $count_date = $row_date['count'];
    
    if ($count_date > 0) {
        echo "<script>
        alert('Hora ocupada');
        window.location.href = 'UtpRecorrido.html';
        </script>";
    } else {
            // Realizar la reserva
            $sql_insert = "INSERT INTO ReservasUTP (NombreCompleto, CorreoElectronico, NumeroCelular, FechaReservacion, NumeroPersonas, Hora) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            $params_insert = array($nombre, $correo, $telefono, $fechaReservacion, $numeroPersonas, $Hora);
            $stmt_insert = sqlsrv_query($conn, $sql_insert, $params_insert);

            if ($stmt_insert === false) {
                die("Error en la inserción de datos: " . sqlsrv_errors());
            } else {
            
            require 'PHPMailer/Exception.php';
            require 'PHPMailer/PHPMailer.php';
            require 'PHPMailer/SMTP.php';

          
            $mail = new PHPMailer(true);

           
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'Correo@test.com';
            $mail->Password = 'pkjdwntvrqfhmdzf';
            $mail->SMTPSecure = 'tls'; 
            $mail->Port = 587; 

          
            $mail->setFrom('descubrelamagiadeparras@gmail.com', 'Administrador');
            $mail->addAddress($correo, $nombre); 

            $mail->isHTML(false);
            $mail->Subject = 'Confirmación de Reserva';
            $mail->Body = "Gracias por tu reserva.\n\nDetalles de la reserva: En la Universidad Tecnologica de Parras \nNombre: $nombre\nCorreo: $correo\nTeléfono: $telefono\nFecha de Reservación: $fechaReservacion\nHora de Reservación: $Hora\nNúmero de Personas: $numeroPersonas";

           
            if ($mail->send()) {
              
                $mailNotificacion = new PHPMailer(true);
                $mailNotificacion->isSMTP();
                $mailNotificacion->Host = 'smtp.gmail.com';
                $mailNotificacion->SMTPAuth = true;
                $mailNotificacion->Username = 'descubrelamagiadeparras@gmail.com';
                $mailNotificacion->Password = 'pkjdwntvrqfhmdzf';
                $mailNotificacion->SMTPSecure = 'tls'; 
                $mailNotificacion->Port = 587; 

      
                $mailNotificacion->setFrom('descubrelamagiadeparras@gmail.com', 'Administrador');
                $mailNotificacion->addAddress('dilan.lucio26@gmail.com', 'Administrador'); 

                $mailNotificacion->isHTML(false);
                $mailNotificacion->Subject = 'Nueva Reserva';
                $mailNotificacion->Body = "Nueva reserva recibida.\n\nDetalles de la reserva:\nNombre: $nombre\nCorreo: $correo\nTeléfono: $telefono\nFecha de Reservación: $fechaReservacion\nHora de Reservación: $Hora\nNúmero de Personas: $numeroPersonas";

                // Enviar el correo de notificación
                $mailNotificacion->send();

                echo "<script>alert('Reserva exitosa');
                window.location.href = 'UtpRecorrido.html';
                </script>";
            } else {
                echo "<script>alert('Error al enviar el correo de confirmación. Por favor, contacta al administrador.');</script>";
                echo $mail->ErrorInfo;
                $mail->SMTPDebug = 2; 
            }       
        }

    }
    sqlsrv_close($conn);
}
?>