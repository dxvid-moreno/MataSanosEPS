<?php
session_start();
require ("logica/Admin.php");
$id = $_SESSION["id"];
$admin = new Admin($id);
$admin -> consultar();
echo "Hola " . $admin -> getNombre() . " " . $admin -> getApellido();
?>
