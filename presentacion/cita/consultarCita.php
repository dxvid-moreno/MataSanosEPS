<?php

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['idCita']) && isset($_POST['nuevoEstado'])) {
    $cita = new Cita($_POST['idCita']);
    $cita->cambiarEstado($_POST['nuevoEstado']); // Debes implementar este método
}

$id = $_SESSION["id"];
$rol = $_SESSION["rol"];
?>

<body>
<?php 
include("presentacion/encabezado.php");
include("presentacion/menu" . ucfirst($rol) . ".php");
?>

<div class="container">
    <div class="row mt-3">
        <div class="col">
            <div class="card">
                <div class="card-header"><h4>Citas</h4></div>
                <div class="card-body">
                    <?php 
                    $cita = new Cita();
                    $citas = $cita->consultar($rol, $id);
                    
                    echo "<table class='table table-striped table-hover'>";
                    echo "<tr>
                            <td>Id</td>
                            <td>Fecha</td>
                            <td>Hora</td>";
                    
                    if ($rol != "paciente") {
                        echo "<td>Paciente</td>";
                    }
                    if ($rol != "medico") {
                        echo "<td>Médico</td>";
                    }
                    echo "<td>Consultorio</td>";
                    echo "<td>Estado Cita</td>";
                    
                    if ($rol == "admin") {
                        echo "<td>Cambiar Estado</td>";
                    }
                    echo "</tr>";
                    
                    foreach ($citas as $cit) {
                        echo "<tr>";
                        echo "<td>" . $cit->getId() . "</td>";
                        echo "<td>" . $cit->getFecha() . "</td>";
                        echo "<td>" . $cit->getHora() . "</td>";
                        
                        if ($rol != "paciente") {
                            echo "<td>" . $cit->getPaciente()->getNombre() . " " . $cit->getPaciente()->getApellido() . "</td>";
                        }
                        
                        if ($rol != "medico") {
                            echo "<td>" . $cit->getMedico()->getNombre() . " " . $cit->getMedico()->getApellido() . "</td>";
                        }
                        
                        echo "<td>" . $cit->getConsultorio()->getNombre() . "</td>";
                        echo "<td>" . $cit->getEstadoCita()->getValor() . "</td>";
                        //para mostrar el boton de cambiar
                        if ($rol == "admin") {
                            echo "<td><button class='btn btn-primary btn-sm' data-bs-toggle='modal' data-bs-target='#modalEstado" . $cit->getId() . "'>Cambiar</button></td>";
                        }
                        
                        echo "</tr>";

                        // Formulario para editar valor del estado
                        echo '
                        <div class="modal fade" id="modalEstado' . $cit->getId() . '" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <form method="post">
                                <div class="modal-header">
                                  <h5 class="modal-title">Cambiar estado</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                  <p>Valor actual: <strong>' . $cit->getEstadoCita()->getValor() . '</strong></p>
                                  <div class="mb-3">
                                    <label class="form-label">Nuevo estado:</label>
                                    <select name="nuevoEstado" class="form-select" required>
                                      <option value="1">Programada</option>
                                      <option value="2">Cancelada</option>
                                      <option value="3">Realizada</option>
                                      <option value="4">Incumplida</option>
                                    </select>
                                  </div>
                                  <input type="hidden" name="idCita" value="' . $cit->getId() . '">
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                  <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>';
                    }
                    
                    echo "</table>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
