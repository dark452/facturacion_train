<?php

require '../include/config_bd_sqlserver.php';
$oci =  mssql_connect($server, $user, $PWD);
if (!$oci) {
   // $e = oci_error();
   // trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
mssql_select_db($database, $oci); 

$query = "SELECT v.idVenta, ve.NRO_FACT_9RUTAS, v.NumeroDocumento, t.CodigoElectronico, v.FechaEmision, v.Neto, v.iva, v.total total1, (v.GlosaImpresion+ ' ') glosa,ve.RUT_CLIENTE, COUNT(vd.idVenta) cantidad, ";
$query.= "COUNT(ve.NRO_FACT_9RUTAS) cantidad2, (SELECT ROUND(CASE WHEN TIPO_FACT = 'FA_P33' THEN (SELECT SUM(valor_pesos*cantidad*1.19) " ;
$query.= "FROM VENTA_EXPOR WHERE NRO_FACT_9RUTAS = v.NumeroDocumento and TIPO_FACT= t.CodigoElectronico GROUP BY NRO_FACT_9RUTAS, TIPO_FACT ) ELSE (SELECT SUM(valor_pesos*cantidad) FROM VENTA_EXPOR WHERE ";
$query.= "NRO_FACT_9RUTAS = v.NumeroDocumento and TIPO_FACT= t.CodigoElectronico GROUP BY NRO_FACT_9RUTAS, TIPO_FACT ) END,0) TOTAL2 FROM VENTA_EXPOR WHERE NRO_FACT_9RUTAS = v.NumeroDocumento and TIPO_FACT= t.CodigoElectronico GROUP BY NRO_FACT_9RUTAS, TIPO_FACT) total2, ";
$query.= "(SELECT ROUND(SUM(cantidad),3) FROM VentaDetalle WHERE idVenta= v.idVenta GROUP BY idVenta) TON1, ";
$query.="(SELECT SUM(cantidad) FROM VENTA_EXPOR WHERE NRO_FACT_9RUTAS = v.NumeroDocumento and TIPO_FACT= t.CodigoElectronico GROUP BY NRO_FACT_9RUTAS,TIPO_FACT) TON2 FROM TipoDocumento t, VentaDetalle vd, Venta v LEFT OUTER JOIN VENTA_EXPOR ve ON ve.NRO_FACT_9RUTAS = v.NumeroDocumento WHERE ";
$query.= "t.idTipoDocumento=v.idTipoDocumento AND t.isElectronico=1 AND ve.estado='POR ENVIAR' AND v.idVenta = vd.idVenta GROUP BY v.idVenta, ve.NRO_FACT_9RUTAS,v.NumeroDocumento, ";
$query.= "t.CodigoElectronico, v.FechaEmision, v.Neto, v.iva, v.total, v.GlosaImpresion, ve.RUT_CLIENTE order by v.idVenta, ve.NRO_FACT_9RUTAS,v.NumeroDocumento";

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
	$facturas[$i]['NRO_FACT_9RUTAS']= $row->NRO_FACT_9RUTAS;
	$facturas[$i]['NumeroDocumento']= $row->NumeroDocumento;
	$facturas[$i]['CodigoElectronico']= $row->CodigoElectronico;
	$facturas[$i]['FechaEmision']= date("d-m-Y h:i:sa",strtotime($row->FechaEmision));
	$facturas[$i]['Neto']= $row->Neto;
	$facturas[$i]['iva']= $row->iva;
	$facturas[$i]['total1']= $row->total1;
    $facturas[$i]['glosa'] = utf8_encode($row->glosa);
	$facturas[$i]['cantidad']= $row->cantidad;
	$facturas[$i]['cantidad2']= $row->cantidad2;
	$facturas[$i]['ton1'] = $row->TON1;
	$facturas[$i]['ton2'] = $row->TON2;
	$facturas[$i]['total2']=$row->total2;
	$facturas[$i]['RUT_CLIENTE'] = $row->RUT_CLIENTE;
	
   $i++;
  }
  
 //print_r($facturas);

header("Content-Type: application/json");

echo json_encode($facturas);

//oci_close($oci);
mssql_free_result($result);
mssql_close($oci);

?>
