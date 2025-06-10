<?php 
require ("logica/Persona.php");
require ("logica/Paciente.php");

$filtro = $_GET["filtro"];
$paciente = new Paciente();
$pacientes = $paciente -> buscar($filtro);
if (count($pacientes) > 0) {
    echo "<table class='table table-striped table-hover mt-3'>";
    echo "<tr><th>Id</th><th>Nombre</th><th>Apellido</th><th>Correo</th></tr>";
    foreach ($pacientes as $index => $pac) {
        $nombre = htmlspecialchars($pac->getNombre());
        $apellido = htmlspecialchars($pac->getApellido());
        $correo = htmlspecialchars($pac->getCorreo());
        $palabras = preg_split('/\s+/', $filtro);
        
        foreach ($palabras as $palabra) {
            if ($palabra === '') continue;
            $pattern = '/(' . preg_quote($palabra, '/') . ')/i';
            
            $nombre = preg_replace_callback($pattern, function($match) {
                return "<strong>" . $match[1] . "</strong>";
            }, $nombre);
                
                $apellido = preg_replace_callback($pattern, function($match) {
                    return "<strong>" . $match[1] . "</strong>";
                }, $apellido);
        }
        
        echo "<tr>";
        echo "<td>" . $pac->getId() . "</td>";
        echo "<td>" . $nombre . "</td>";
        echo "<td>" . $apellido . "</td>";
        echo "<td>" . $correo . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "<div class='alert alert-danger mt-3' role='alert'>No hay resultados</div>";
}

?>
