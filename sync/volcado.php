<?php
set_time_limit(0);
ini_set("memory_limit", "-1");

require_once("../db.php");
require_once("../variables.php");
require_once("../functions/sync.php");

$tosync=array();

$dbnivelAPP=new DB('2.139.164.215','tpv','tpv','laltalena');
$dbnivelBAK=new DB('2.139.164.215','tpv','tpv','laltalena_backup');



$debug=1;

$hoy=date('Y') . "-" . date('m') . '-' . date('d');
$fecha = new DateTime($hoy);
$fecha->sub(new DateInterval('P90D'));
$bttDEV= $fecha->format('Y-m-d');


if($debug){echo "cuadra ________________________- \n\n";}

$horr=date('G');


$file = fopen ("http://api.ipify.org/?format=txt", "r");
while (!feof ($file)) { $ip = fgets ($file, 1024);};
fclose($file);







	



################# sumo stocks
$stll="";

if (!$dbnivel->open()){die($dbnivel->error());};
$queryp= "select * from stocklocal;";
$dbnivel->query($queryp);if($debug){echo "$queryp <br>\n\n" . $dbnivel->error();};

while ($row = $dbnivel->fetchassoc()){
		
$id=$row['id'];         
$id_art=$row['id_art'];           
$cod=$row['cod'];          
$stock=$row['stock'];         
$alarma=$row['alarma'];         
$pvp=$row['pvp'];   	
			
$stll.="($id_art,$cod,$stock,$alarma,'$pvp'),";		
	
};
if (!$dbnivel->close()){die($dbnivel->error());};


$stll=substr($stll, 0,-1);



if (!$dbnivelBAK->open()){die($dbnivelBAK->error());};
$queryp= "DELETE FROM stocklocal_$id_tienda;";
$dbnivelBAK->query($queryp);
if($debug){echo "$queryp <br>\n\n";};

$queryp= "INSERT INTO stocklocal_$id_tienda (id_art,cod,stock,alarma,pvp) VALUES $stll;";
$dbnivelBAK->query($queryp);
if($debug){echo "$queryp <br>\n\n";};


if (!$dbnivelBAK->close()){die($dbnivelBAK->error());};


########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33
########################################################################33


		
#####fin doit





if($debug){echo "cuadra ________________________- \n\n";}