<?php
// Configura la carpeta donde se guardarán los archivos
$target_dir = "uploads/";

// Conexión a la base de datos 
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "archivopdf";


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_FILES["files"]) && !empty($_FILES["files"]["name"])) {
    foreach ($_FILES["files"]["name"] as $key => $name) {
        $target_file = $target_dir . basename($name);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        echo "Archivo $key: " . htmlspecialchars($name) . "<br>";
        // Validaciones combinadas y simplificadas
        if (file_exists($target_file)) {
            echo "El archivo ya existe. No se realizará la subida.<br> ";
            continue;
        } elseif ($_FILES["files"]["size"][$key] > 15048576) { // Ajustar tamaño máximo
            echo "El archivo es demasiado grande. No se puede subir";
            continue;
        } else {
            // Mover el archivo y mostrar mensaje de éxito
            if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $target_file)) {
                echo "El archivo " . htmlspecialchars($name) . " se subió correctamente.<br>";
            } else {
                echo "Error al subir el archivo " . $name . ".<br>";
            }
        }

        // Insertar datos en la base de datos para cada archivo
        $sql = "INSERT INTO archivos (nombre_archivo, descripcion, url_archivo, fecha_subida) 
                VALUES ('" . mysqli_real_escape_string($conn, $name) . "', 'Descripción del archivo', '$target_file', NOW())";

        if ($conn->query($sql) === TRUE) {
            echo "El archivo " . $name . " se ha subido y registrado correctamente.<br>";
        } else {
            echo "Error al subir el archivo " . $name . ": " . $conn->error . "<br>";
        }
    }
} else {
    echo "No se ha seleccionado ningún archivo.";
}
?>