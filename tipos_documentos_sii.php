<?php 
header('Access-Control-Allow-Origin: http://cv:8090');

require 'include/config_bd.php';
$oci = oci_connect($usuario,$contrasena,$urlDatos);
if (!$oci) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

//if(($_POST['empresa']!=null) && ($_POST['fecha_cierre']!=null) && ($_POST['fecha_respaldo']!=null){
$p_empresa = $_GET['cod_empresa'];
$p_tipo_fact = $_GET['tipo_fact'];


//$p_fecha_respaldo = $_POST['fecha_respaldo'];

//$sql_version = "SELECT MAX(NRO_VERSION)ULT_VERSION from SAB_AUDI_SEMESTRE_EMP where COD_EMPRESA=$p_empresa";
//$stid = oci_parse($oci, $sql_version);
//oci_execute($stid);

//oci_fetch_all($stid, $version, 0, -1, OCI_ASSOC + OCI_FETCHSTATEMENT_BY_ROW);

//$p_version=$version[0]["ULT_VERSION"];

//$sql_fecha_respaldo = "SELECT TO_CHAR(LAST_DAY(TO_DATE('".$p_fecha_consulta_cortada."','yyyymm')),'ddmmyyyy') FECHA_RESPALDO FROM DUAL";


$sql = 'BEGIN ENVIO_FACTURA_PPL(:p_empresa,:p_tipo_fact,:message,:resultado); END;';

//$stmt = oci_parse($conn,$sql);
$stid = oci_parse($oci, $sql);

//  Bind the input parameter
oci_bind_by_name($stid,':p_empresa',$p_empresa)or die('Error binding empresa');
oci_bind_by_name($stid,':p_tipo_fact',$p_tipo_fact)or die('Error binding p_tipo_fact');

//oci_bind_by_name($stid,':p_version',&$p_version)or die('Error binding version');

// Bind the output parameter
oci_bind_by_name($stid,':message',$message,1000)or die('Error binding p_contenido');
oci_bind_by_name($stid,':resultado',$resultado,1000)or die('Error binding resultado');

// Assign a value to the input 


//echo "version ==> ".$p_version."<\br>";
// "" ,".." , ".date($p_fecha_respaldo)." , ".$p_version;


$execute_return= oci_execute($stid);
//print_r($execute_return);
if (!$execute_return){


    echo "Error Execution Stored Procedure";
}else{
	//echo $message." ".$resultado;
	//$data['success'] = true;
	//$data['message'] = $resultado;
//	echo json_encode($resultado);
header('Content-Type: application/xml; charset=utf-8');
		echo "<resultado>";
		echo "<titulo>".$message."</titulo>";
		echo "<contenido>".$resultado."</contenido>";
		echo "</resultado>";
}
// $message is now populated with the output value

//print_r($data);
//oci_close($oci);


/*
if($resultado==0){

$data['success'] = true;
$data['message'] = $message;
echo json_encode($data);
}
else{
	$data['success'] = false;
	$data['message'] = $message;
	echo json_encode($data);
}
*/



//header("Content-Type: application/json");

//echo json_encode($data);

oci_close($oci);

?>