<?
$usuario = $_SERVER['PHP_AUTH_USER'];

$folio = $_GET['folio'];
$tipo_fact = $_GET['tipo_fact'];
$diferencia = $_GET['diferencia'];
$diferencia = abs($diferencia);
//$tipos_fact = array(3=>'NC_P61',10=> 'ND_P56', 15=>'FA_P33',17=>'FA_P34');

//$id_tipo_documento=array_search($tipo_fact,$tipos_fact);	//id_tipo_docto 9 rutas
				//print_r($tipos_fact);

				require '../include/config_bd_sqlserver.php';
$oci =  mssql_connect($server, $user, $PWD);
if (!$oci) {
   // $e = oci_error();
   // trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
mssql_select_db($database, $oci); 
				
//$query_eliminar="DELETE FROM VENTA_EXPOR WHERE NRO_FACT_9RUTAS=$folio AND TIPO_FACT='".$tipo_fact."'";
//$resultado1 =mssql_query($query_eliminar);


$sql = " SET ANSI_NULLS ON ";
$sql.= " SET ANSI_WARNINGS ON EXEC uSP_ROUND_TONELAJE_VENTA_EXPOR "."@numero = ".$folio.", @idtipo = '".$tipo_fact."', @diferencia =".$diferencia;


$resultado =mssql_query($sql);

// And we can free it like so:
//mssql_free_statement($resultado);
mssql_close($oci);

if($resultado==1){

$data['success'] = true;
echo json_encode($data);
}
else{
	$data['success'] = false;
	echo json_encode($data);
}
