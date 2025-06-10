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
        $sql = "SELECT p.idPaciente, p.nombre, p.apellido, p.correo
            FROM Paciente p
            WHERE ";
        $palabras = preg_split('/\s+/', $filtro, -1, PREG_SPLIT_NO_EMPTY);
        if (empty($palabras)) {
            return $sql . "1=0";
        }
        $condiciones = [];
        foreach ($palabras as $palabra) {
            $palabra_segura = $palabra;
            $condiciones[] = "(p.nombre LIKE '%" . $palabra_segura . "%' OR p.apellido LIKE '%" . $palabra_segura . "%')";
        }
        $sql .= implode(" AND ", $condiciones);
        
        return $sql;
    }
}
