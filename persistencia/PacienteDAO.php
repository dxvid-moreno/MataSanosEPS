<?php

class PacienteDAO{
    private $id;
    private $nombre;
    private $apellido;
    private $correo;
    private $clave;
    private $fechaNacimiento;

    public function __construct($id = 0, $nombre = "", $apellido = "", $correo = "", $clave = "", $fechaNacimiento = ""){
        $this -> id = $id;
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
        $this -> correo = $correo;
        $this -> clave = $clave;
        $this -> fechaNacimiento = $fechaNacimiento;
    }

       
    public function autenticar(){
        return "select idPaciente
                from Paciente
                where correo = '" . $this -> correo . "' and clave = '" . md5($this -> clave) . "'";
    }
    
    public function consultar(){
        return "select p.nombre, p.apellido, p.correo, p.fechaNacimiento  
                from Paciente p
                where idPaciente = '" . $this -> id . "'";
    }

    public function buscar($filtro){
        $filtro = trim($filtro); // Elimina espacios al inicio y al final del filtro.
        
        // Divide el filtro de búsqueda en palabras individuales.
        // Usamos preg_split para manejar múltiples espacios y eliminar entradas vacías.
        $palabras = preg_split('/\s+/', $filtro, -1, PREG_SPLIT_NO_EMPTY);
        
        // Si el filtro está vacío (no hay palabras), devolvemos una consulta que no encontrará nada.
        // Esto es para evitar errores si el filtro llega vacío.
        if (empty($palabras)) {
            return "SELECT p.idPaciente, p.nombre, p.apellido, p.correo FROM Paciente p WHERE 1=0"; // 1=0 siempre es falso
        }
        
        $sql = "SELECT p.idPaciente, p.nombre, p.apellido, p.correo
            FROM Paciente p
            WHERE "; // La cláusula WHERE se construirá a continuación.
        
        $condiciones = []; // Array para guardar las condiciones para cada palabra.
        
        // Para cada palabra en el filtro de búsqueda:
        foreach ($palabras as $palabra) {
            // Escapamos la palabra para que sea segura en la consulta SQL (evitar inyección SQL)
            // y para que funcione con LIKE.
            $palabra_segura = $palabra; // En un entorno real, usarías prepared statements o mysqli_real_escape_string.
            // Para este ejemplo, lo dejamos así por simplicidad, pero tenlo en cuenta.
            
            // Creamos una condición: la palabra debe aparecer en el nombre O en el apellido.
            // Ej: (p.nombre LIKE '%cristian%' OR p.apellido LIKE '%cristian%')
            $condiciones[] = "(p.nombre LIKE '%" . $palabra_segura . "%' OR p.apellido LIKE '%" . $palabra_segura . "%')";
        }
        
        // Unimos todas las condiciones con 'AND'.
        // Esto significa que un paciente solo será un resultado si CADA PALABRA del filtro
        // se encuentra en su nombre o en su apellido.
        // Ej: Para "cristian feo", la consulta será:
        // WHERE (p.nombre LIKE '%cristian%' OR p.apellido LIKE '%cristian%')
        //   AND (p.nombre LIKE '%feo%' OR p.apellido LIKE '%feo%')
        $sql .= implode(" AND ", $condiciones);
        
        return $sql; // Devuelve la consulta SQL completa.
    }
}
