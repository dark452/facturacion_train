<?php
$errors = array(); 
$data = array();

//if(($_POST['empresa']!=null) && ($_POST['fecha_cierre']!=null) && ($_POST['fecha_respaldo']!=null){
$p_empresa = $_GET['empresa'];
$p_fecha_consulta = new DateTime($_GET['fecha_consulta']);

$p_fecha_consulta_total = date_format($p_fecha_consulta, 'dmY');

$p_fecha_consulta_cortada = date_format($p_fecha_consulta, 'Ym');
//$p_fecha_respaldo = $_POST['fecha_respaldo'];

require '../include/config_bd.php';

$oci = oci_connect($usuario,$contrasena,$urlDatos);

//$sql_version = "SELECT MAX(NRO_VERSION)ULT_VERSION from SAB_AUDI_SEMESTRE_EMP where COD_EMPRESA=$p_empresa";
//$stid = oci_parse($oci, $sql_version);
//oci_execute($stid);

//oci_fetch_all($stid, $version, 0, -1, OCI_ASSOC + OCI_FETCHSTATEMENT_BY_ROW);

//$p_version=$version[0]["ULT_VERSION"];

//$sql_fecha_respaldo = "SELECT TO_CHAR(LAST_DAY(TO_DATE('".$p_fecha_consulta_cortada."','yyyymm')),'ddmmyyyy') FECHA_RESPALDO FROM DUAL";
$sql_fecha_respaldo = "SELECT TO_CHAR(LAST_DAY(ADD_MONTHS(to_date('".$p_fecha_consulta_cortada."','yyyymm'), +1)),'ddmmyyyy') FECHA_RESPALDO FROM dual";
$stid = oci_parse($oci, $sql_fecha_respaldo);
oci_execute($stid);

oci_fetch_all($stid, $p_fecha_respaldo, 0, -1, OCI_ASSOC + OCI_FETCHSTATEMENT_BY_ROW);

$p_fecha_respaldo=$p_fecha_respaldo[0]["FECHA_RESPALDO"];

$sql = 'BEGIN SAB_AUDITORIA_MATERIAL_EXIST(:p_empresa,:p_fecha_consulta,:p_fecha_respaldo,:message, :resultado); END;';

//$stmt = oci_parse($conn,$sql);
$stid = oci_parse($oci, $sql);

//  Bind the input parameter
oci_bind_by_name($stid,':p_empresa',$p_empresa)or die('Error binding empresa');
oci_bind_by_name($stid,':p_fecha_consulta',$p_fecha_consulta_total)or die('Error binding fecha_consulta');
oci_bind_by_name($stid,':p_fecha_respaldo',$p_fecha_respaldo)or die('Error binding fecha_respaldo');
//oci_bind_by_name($stid,':p_version',&$p_version)or die('Error binding version');

// Bind the output parameter
oci_bind_by_name($stid,':message',$message,255)or die('Error binding p_contenido');
oci_bind_by_name($stid,':resultado',$resultado,1)or die('Error binding resultado');

// Assign a value to the input 
/*
echo "empresa => ".$p_empresa."<\br>";
echo "fecha_consulta => ".date($p_fecha_consulta_total)."<\br>";
echo "fecha_respaldo  => ".date($p_fecha_respaldo)."<\br>";
echo "version ==> ".$p_version."<\br>";
// "" ,".." , ".date($p_fecha_respaldo)." , ".$p_version;

*/

$execute_return= oci_execute($stid);

if (!$execute_return)
    print "Error Execution Stored Procedure";

// $message is now populated with the output value

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
?>
