<?php

include '../include/config_bd.php';

$oci = oci_connect($usuario,$contrasena,$urlDatos);
if (!$oci) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}



$stid = oci_parse($oci, 'select cod_empresa, fecha_consulta,nro_version from sab_audi_semestre_emp group by cod_empresa, fecha_consulta,nro_version');
oci_execute($stid);

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><resultado></resultado>');
$xml->addAttribute('version', '1.0');
	while($fila = oci_fetch_array($stid,OCI_ASSOC+OCI_RETURN_NULLS)){
	foreach ($fila as $item) {
	
		$material = $xml->addChild('material');
        $material->addChild('cod_empresa',$item['cod_empresa']);
        $material->addChild('fecha_consulta',$item['fecha_consulta']);
        $material->addChild('tipo_moneda',$item['tipo_moneda']);
        $material->addChild('codigo_material',$item['codigo_material']);
    }
	}


Header('Content-type: text/xml');
echo $xml->asXML();
