<?php
global $iva;
$iva=21;


$equiEST['P']="ACTIVO";
$equiEST['F']="FINALIZADO";
$equiEST['A']="EN ALMACÉN";
$equiEST['T']="ENVIADO A TIENDAS";


$pathimages="c:/D/fotos_altanela/";
$urlimages="/photos/";


global $dbnivel; global $tiendas; global $dbnivelCR; global $dbnivelAPP;


$dbnivel=new DB('localhost','tpv','tpv','laltanelaTPV');


if (!$dbnivel->open()){die($dbnivel->error());};


$queryp= "select var, value from config";
$dbnivel->query($queryp);
while ($row = $dbnivel->fetchassoc()){
$config = "\$" . $row['var'] . "='" . $row['value'] . "';";
eval($config);
}
if (!$dbnivel->close()){die($dbnivel->error());};


$hoy=date('Y') . "-" . date('m') . "-" . date('d');

?>