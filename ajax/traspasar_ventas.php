<?
$user = $_SERVER['PHP_AUTH_USER'];

$folio = $_GET['folio'];
$tipo_fact = $_GET['tipo_fact'];

require '../include/config_bd_sqlserver.php';
$oci =  mssql_connect($server, $user, $PWD);
if (!$oci) {
   // $e = oci_error();
   // trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
mssql_select_db($database, $oci); 


$sql = " SET ANSI_NULLS ON ";
$sql.= " SET ANSI_WARNINGS ON EXEC uSP_ENVIO_ORACLE "."@numero = ".$folio.", @idtipo = '".$tipo_fact."'";



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

