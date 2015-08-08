<?php

require '../include/config_bd.php';
$oci = oci_connect($usuario,$contrasena,$urlDatos);
if (!$oci) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$query = " SELECT TIPO_FACT, RUT_CLIENTE RUT_CLIENTE, NRO_FACT, CASE WHEN TIPO_FACT = 'NC_P61' THEN 'NO EXISTE' ELSE ''||GRP_FACT END AS GRP_FACT, TO_CHAR(FECHA_FACT,'DD/MM/YYYY') FECHA_FACT, NETO_PESOS, IVA_PESOS, TOTAL_PESOS,NETO_DOLAR,TOTAL_DOLAR, ";
$query.= "vf_obt_nombre_cliente(RUT_CLIENTE,COD_EMP) NOMBRE_CLIENTE FROM VF_MOV_FACTURAC_DTE_VW  WHERE COD_EMP =2 AND ESTADO = 'FACTURADO' AND ENVIO_FACTURE='S' ORDER BY NRO_FACT DESC";

$stid = oci_parse($oci, $query);
oci_execute($stid);

oci_fetch_all($stid, $data, 0, -1, OCI_ASSOC + OCI_FETCHSTATEMENT_BY_ROW);

header("Content-Type: application/json");
echo json_encode($data);

oci_close($oci);

?>