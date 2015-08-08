<?php

$folio = $_GET['folio'];
$tipo_fact = substr($_GET['tipo_fact'],-2);
$rut_empresa = $_GET['rut_empresa'];
$url = "http://asp403r.paperless.cl/Facturacion/webservices/ConsultaPDF.jsp?";
$pw = MD5('-'.$rut_empresa.'-'.$folio.'-2-');


$url.= "e=".$rut_empresa."&t=".$tipo_fact."&f=".$folio."&l=&pw=".$pw;
//echo $url;
$content = file_get_contents($url);
$xml = new SimpleXMLElement(trim($content));
//print_r($xml);
echo  json_encode($xml->Mensaje);
?>