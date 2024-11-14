<?php 

header('Content-Type: application/json');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se proporcionaron los parámetros necesarios
    if (isset($_POST['nombreCompleto']) && isset($_POST['horaReserva']) && isset($_POST['CorreoElectronico'])) {
        $nombreCompleto = $_POST['nombreCompleto'];
        $horaReserva = $_POST['horaReserva'];
        $Correo = $_POST['CorreoElectronico'];

        // Conectar a la base de datos
        $serverName = "SelServer";
        $connectionOptions = array(
            "Database" => "DataBase",
            "Uid" => "UserName",
            "PWD" => "Password"
        );
        $conn = sqlsrv_connect($serverName, $connectionOptions);

        // Verificar la conexión a la base de datos
        if ($conn === false) {
            die(json_encode(array('error' => 'Error de conexión a la base de datos')));
        }

        // Preparar y ejecutar la consulta SQL de eliminación
        $query = "DELETE FROM ReservasUTP WHERE NombreCompleto = ? AND Hora = ?";
        $params = array($nombreCompleto, $horaReserva);
        $result = sqlsrv_query($conn, $query, $params);

        // Verificar el resultado de la consulta
        if ($result) {
            // Envío del correo electrónico
            require '../PHPMailer/Exception.php';
            require '../PHPMailer/PHPMailer.php';
            require '../PHPMailer/SMTP.php';

            $mail = new PHPMailer(true);

            try {
                // Configuración de PHPMailer
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'correo@test.com';
                $mail->Password = 'Password';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Configuración del correo electrónico
                $mail->setFrom('correo@test.com', 'Administrador');
                $mail->addAddress($Correo, $nombreCompleto);
                $mail->isHTML(false);
                $mail->Subject = 'Eliminacion de Reserva';
                $mail->Body = "Tu reserva en:\nEn la Universidad Tecnologica de Parras fue eliminada\n\nNombre: $nombreCompleto\nCorreo: $Correo\nHora de Reservación: $horaReserva";

                // Envío del correo electrónico
                if ($mail->send()) {
                    echo json_encode(array('success' => true));
                } else {
                    // Error en el envío del correo electrónico
                    echo json_encode(array('error' => 'Error en el envío del correo electrónico: ' . $mail->ErrorInfo));
                }

                // Cerrar la conexión a la base de datos
                sqlsrv_close($conn);
            } catch (Exception $e) {
                // Captura de excepciones de PHPMailer
                echo json_encode(array('error' => 'Error en PHPMailer: ' . $e->getMessage()));
            }
        } else {
            // Error en la eliminación de la base de datos
            echo json_encode(array('error' => 'Error en la eliminación de la base de datos'));
        }
    } else {
        // No se proporcionaron los parámetros necesarios
        echo json_encode(array('error' => 'Parámetros no proporcionados'));
    }
} else {
    // La solicitud no es de tipo POST, manejar según sea necesario
    echo json_encode(array('error' => 'Método no permitido'));
}
?>
