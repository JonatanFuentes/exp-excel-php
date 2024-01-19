<?php
include "config.php";

$fechainicial = isset($_POST['fechainicial']) ? $_POST['fechainicial'] : '';
$fechafinal = isset($_POST['fechafinal']) ? $_POST['fechafinal'] : '';
$fechainicial = !empty($fechainicial) ? date('d-m-Y', strtotime($fechainicial)) : '';
$fechafinal = !empty($fechafinal) ? date('d-m-Y', strtotime($fechafinal)) : '';

if (empty($fechainicial)) {
    $fechainicial = date("d-m-Y");
    $fechafinal = date("d-m-Y");
}

$sql = "SELECT
    rit as RIT,
    fechafatal as FECHA_PERENTORIA,
    iniciales as RESPONSABLE,
    observacion as OBSERVACION,
    estado as ESTADO
    FROM agenda where fechafatal between STR_TO_DATE('$fechainicial','%Y-%m-%d') and STR_TO_DATE('$fechafinal','%Y-%m-%d') order by fechafatal asc";

$resultado = $pdo->query($sql);

$eventos = array();
while ($rows = $resultado->fetch(PDO::FETCH_ASSOC)) {
    $eventos[] = $rows;
}

if (!empty($eventos)) {
    $filename = "eventos.xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=" . $filename);

    $mostrar_columnas = false;

    foreach ($eventos as $evento) {
        if (!$mostrar_columnas) {
            echo implode("\t", array_keys($evento)) . "\n";
            $mostrar_columnas = true;
        }
        echo implode("\t", array_values($evento)) . "\n";
    }
} else {
    echo 'No hay datos a exportar';
}
exit;
?>
