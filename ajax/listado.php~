<?php
if(isset($_GET['cod_empresa']) && $_GET['nro_version']){
$empresa = $_GET['cod_empresa'];
$version = $_GET['nro_version'];

require '../include/config_bd.php';


$oci = oci_connect($usuario,$contrasena,$urlDatos);
if (!$oci) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$query = "SELECT COD_EMPRESA, TO_CHAR(fecha_consulta,"."'DD/MM/YYYY'".") FECHA_CONSULTA, TIPO_MONEDA, CODIGO_MATERIAL, DESCRIPCION_MATERIAL, CUENTA, SALDO, PRECIO_PESOS, MONTO_SISTEMA_PESOS, ";
$query.= "MONTO_CALCULADO_PESOS, PRECIO_DOLAR, MONTO_SISTEMA_DOLAR, MONTO_CALCULADO_DOLAR, TO_CHAR(FECHA_ULT_SALIDA,"."'DD/MM/YYYY'".") FECHA_ULT_SALIDA, NRO_VERSION,  TO_CHAR(FECHA_ULT_COMPRA,"."'DD/MM/YYYY'".") FECHA_ULT_COMPRA FROM sab_audi_semestre_emp ";
$query.= "WHERE cod_empresa=".$empresa." and nro_version=".$version;
//echo $query;

$stid = oci_parse($oci, $query);
oci_execute($stid);

oci_fetch_all($stid, $data, 0, -1, OCI_ASSOC + OCI_FETCHSTATEMENT_BY_ROW);

header("Content-Type: application/json");
echo json_encode($data);

oci_close($oci);
}
?>
