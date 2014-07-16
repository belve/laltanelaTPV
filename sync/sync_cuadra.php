<?php
if($debug){echo "cuadra ________________________- \n\n";}
if (!$dbnivelAPP->open()){die($dbnivelAPP->error());}; 
$horr=date('G');


$file = fopen ("http://cursodecursos.com:8080/test.php", "r");
while (!feof ($file)) { $ip = fgets ($file, 1024);};
fclose($file);





$ldoneP=0;

$existt="";
$queryp= "select ip from tasks where idt=$id_tienda;";
$dbnivelAPP->query($queryp); if($debug){echo "$queryp \n\n";};
while ($row = $dbnivelAPP->fetchassoc()){$existt=$row['ip'];};

if(!$existt){
$queryp= "INSERT INTO tasks (idt,ip) VALUES ($id_tienda,'$ip');";
$dbnivelAPP->query($queryp);if($debug){echo "$queryp \n\n";};	
}

$queryp= "select ldoneP from tasks where idt=$id_tienda AND ldoneF='$hoy';";
$dbnivelAPP->query($queryp); if($debug){echo "$queryp \n\n";};
while ($row = $dbnivelAPP->fetchassoc()){
$ldoneP=$row['ldoneP'];	
}
if (!$dbnivelAPP->close()){die($dbnivelAPP->error());};



$doitC=0;
if(($horr >= 7)&&($horr < 15)){$ldonePN=1;}
if(($horr >= 15)&&($horr < 21)){$ldonePN=2;}
if(($horr >= 21)&&($horr < 24)){$ldonePN=3;}

if($ldoneP<$ldonePN){$ldoneP=$ldonePN;$doitC=1;}

if($doitC){
	



################# sumo stocks
$stll="";

if (!$dbnivel->open()){die($dbnivel->error());};
$queryp= "select * from stocklocal_$id_tienda;";
$dbnivel->query($queryp);if($debug){echo "$queryp <br>\n\n";};
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
$stll=substr($stll, 0,1);



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


		
}#####fin doit






if($debug){echo "cuadra ________________________- \n\n";}