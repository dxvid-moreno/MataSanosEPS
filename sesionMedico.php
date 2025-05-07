<?php
session_start();
require ("logica/Medico.php");
require ("logica/Especialidad.php");
$id = $_SESSION["id"];
$medico = new Medico($id);
$medico -> consultar();
echo "Hola " . $medico -> getNombre() . " " . $medico -> getApellido();
echo "Usted tiene la especialidad: " . $medico -> getEspecialidad() -> getNombre();
?>