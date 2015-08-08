<?php

require '../include/config_bd_sqlserver.php';
$oci =  mssql_connect($server, $user, $PWD);
if (!$oci) {
   // $e = oci_error();
   // trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
mssql_select_db($database, $oci); 

$query = "SELECT idventa, nro_fact_9rutas, numerodocumento, codigoelectronico, fechaemision, ";
$query.= "neto, iva, total1, glosa, rut_cliente,cantidad,cantidad2, ";
$query.= "CASE WHEN (total2-total1 = 1 OR total2-total1 =-1) THEN total1 ELSE total2 END AS total2, ton1,ton2 ";
$query.= "FROM facturas_procesar_vtafac ";
$query.= "ORDER BY idventa, nro_fact_9rutas, numerodocumento";

//echo $query;
//$stid = oci_parse($oci, $query);
$result = mssql_query($query);
//oci_execute($stid);

//oci_fetch_all($stid, $data, 0, -1, OCI_ASSOC + OCI_FETCHSTATEMENT_BY_ROW);


 //$result = mssql_query('SELECT * FROM Venta');
$facturas = array();
$i=0;
 while (($row = mssql_fetch_object($result))) 
    { 
	$facturas[$i]['nro_fact_9rutas']= $row->nro_fact_9rutas;
	$facturas[$i]['numerodocumento']= $row->numerodocumento;
	$facturas[$i]['codigoelectronico']= $row->codigoelectronico;
	$facturas[$i]['fechaemision']= date("d-m-Y h:i:sa",strtotime($row->fechaemision));
	$facturas[$i]['neto']= $row->neto;
	$facturas[$i]['iva']= $row->iva;
	$facturas[$i]['total1']= $row->total1;
    $facturas[$i]['glosa'] = utf8_encode($row->glosa);
	$facturas[$i]['cantidad']= $row->cantidad;
	$facturas[$i]['cantidad2']= $row->cantidad2;
	$facturas[$i]['ton1'] = $row->ton1;
	$facturas[$i]['ton2'] = $row->ton2;
	$facturas[$i]['total2']=$row->total2;
	$facturas[$i]['rut_cliente'] = $row->rut_cliente;
	
   $i++;
  }
  
 //print_r($facturas);

header("Content-Type: application/json");

echo json_encode($facturas);

//oci_close($oci);
mssql_free_result($result);
mssql_close($oci);

?>
