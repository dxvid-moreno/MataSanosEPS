<?php
$id = $_SESSION["id"];
$medico = new Medico($id);
$medico -> consultar();
echo "Hola " . $medico -> getNombre() . " " . $medico -> getApellido();
echo "Usted tiene la especialidad: " . $medico -> getEspecialidad() -> getNombre();
?>