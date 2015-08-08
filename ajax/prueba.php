<?php

$usuario    = "BD_ABASTE";
$contrasena = "abaste01";
$urlDatos   = "89.168.89.105:1521/prod11";

$conn = oci_connect($usuario,$contrasena,$urlDatos);

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, 'select cod_empresa, fecha_consulta,nro_version from bd_abaste.sab_audi_semestre_emp group by cod_empresa, fecha_consulta,nro_version');
oci_execute($stid);

$nrows = oci_fetch_all($stid, $results);
if ($nrows > 0) {
   echo "<table border=\"1\">\n";
   echo "<tr>\n";
   foreach ($results as $key => $val) {
      echo "<th>$key</th>\n";
   }
   echo "</tr>\n";
   
   for ($i = 0; $i < $nrows; $i++) {
      echo "<tr>\n";
      foreach ($results as $data) {
         echo "<td>$data[$i]</td>\n";
      }
      echo "</tr>\n";
   }
   echo "</table>\n";
} else {
   echo "No data found<br />\n";
}      
echo "$nrows Records Selected<br />\n";
 
oci_free_statement($stid);
oci_close($conn);
?>

?>
