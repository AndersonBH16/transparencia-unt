<!DOCTYPE html>
<html>
<head>
    <title>Mis Archivos Subidos</title>
    <link rel="stylesheet" href="stylever.css">
</head>
<body>
    <h1>Mis Archivos</h1>
    <?php
    // Conexión a la base de datos 
    $servername = "localhost";
    $username = "root";
    $password = "123456";
    $dbname = "archivopdf";


    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Consulta para obtener los archivos
    $sql = "SELECT * FROM archivos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<thead><tr><th>Título del Archivo</th><th>Descripción</th><th>Visualizar</th></tr></thead>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["nombre_archivo"] . "</td>";
            echo "<td>" . $row["descripcion"] . "</td>";
            echo "<td><a href='" . $row["url_archivo"] . "'>$row[nombre_archivo]</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No hay archivos subidos.";
    }

    $conn->close();
    ?>
</body>
</html>