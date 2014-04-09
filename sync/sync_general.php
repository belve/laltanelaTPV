<?php

if($debug){echo "general ________________________- \n\n";}

if (!$dbnivelAPP->open()){die($dbnivelAPP->error()); $noconectado=1;}; $hazpedidos=array();$querys2=array();$querstldone=array();

$querys=array();$queryshechas=array(); $alarmas=array();
$queryp= "select * from syncupdate where id_tiend=$id_tienda limit 300;";
$dbnivelAPP->query($queryp);
while ($row = $dbnivelAPP->fetchassoc()){
$querys[$row['id']]=$row['syncSql'];	
}
if($debug){echo "$queryp \n\n";};


$queryp= "select id, stockmin, id_articulo, cantidad, (select codbarras from articulos where id=id_articulo) as cod from repartir where id_tienda=$id_tienda AND estado='P';";
$dbnivelAPP->query($queryp);
while ($row = $dbnivelAPP->fetchassoc()){
$alarmas[$row['cod']]=$row['stockmin'];
$idartis[$row['cod']]=$row['id_articulo'];		
$qants[$row['cod']]=$row['cantidad'];	
}
if($debug){echo "$queryp \n Alarmas:\n"; print_r($alarmas);};

$queryp= "UPDATE repartir SET estado='F' where id_tienda=$id_tienda AND estado='P';";
$dbnivelAPP->query($queryp);
if($debug){echo "$queryp \n\n";};

$rotos="";
$queryp= "select codbarras from articulos where stock <= 0 and congelado=0;";
$dbnivelAPP->query($queryp);
while ($row = $dbnivelAPP->fetchassoc()){$rotos.=$row['codbarras'] . ",";};
$rotos=substr($rotos, 0, strlen($rotos)-1);
if($debug){echo "$queryp \n\n"; echo $rotos;};

if (!$dbnivelAPP->close()){die($dbnivelAPP->error());};





if (!$dbnivel->open()){die($dbnivel->error());};


$queryp= "DELETE FROM dev_ticket_det WHERE id_ticket IN (SELECT id_ticket FROM dev_tickets WHERE fecha <= '$bttDEV');";
$dbnivel->query($queryp);if($debug){echo "\n $queryp \n";};
$queryp= "DELETE FROM dev_tickets WHERE fecha <= '$bttDEV';";
$dbnivel->query($queryp);if($debug){echo "$queryp \n\n";};

$queryp= "select * from syncupdate;";
$dbnivel->query($queryp);
while ($row = $dbnivel->fetchassoc()){
$querys2[$row['id']]=$row['syncSql'];	
}
if($debug){echo "$queryp \n querys2:\n "; print_r($querys2);};


if(count($querys)>0){foreach ($querys as $id => $queryp) {
$dbnivel->query($queryp); echo "\n" . $dbnivel->error() . "\n";
if(strlen($dbnivel->error())==0){$queryshechas[$id]=1;};
}}
if($debug){echo "$queryp \n\n";};
echo "------ to queryshechas ----- \n";
print_r($queryshechas);
echo "------ to queryshechas ----- \n";




if(count($alarmas)>0){foreach ($alarmas as $cod => $alar) {

$id="";
$queryp= "select id from stocklocal where cod=$cod;";
$dbnivel->query($queryp);
while ($row = $dbnivel->fetchassoc()){$id=$row['id'];};

$idarti=$idartis[$cod];

if($id){
$queryp= "update stocklocal set alarma=$alar where cod=$cod;";
$dbnivel->query($queryp); $tosync[]=$queryp;
}else{
$queryp= "INSERT INTO stocklocal (id_art,cod,alarma,stock) VALUES ($idarti,$cod,$alar,0);";
$dbnivel->query($queryp);	$tosync[]=$queryp;
}

}}


echo "------ to sync ----- \n";
print_r($tosync);
echo "------ to sync ----- \n";


$activos="";
$queryp= "select cod from stocklocal where cod not like '%0009999' AND stock <= alarma;";
$dbnivel->query($queryp);
while ($row = $dbnivel->fetchassoc()){$activos.=$row['cod'] . ","; };
$activos=substr($activos, 0,-1);
if($debug){echo "$queryp \n\n Activos: \n $activos";};


$prev="";
$queryp= "select id from articulos where congelado=0 and codbarras not in(select distinct codbarras from pedidos) AND codbarras IN ($activos) AND codbarras NOT IN ($rotos);";
$dbnivel->query($queryp);
while ($row = $dbnivel->fetchassoc()){$prev.=$row['id'] . ",";};
$prev=substr($prev, 0,-1);

if($debug){
echo "\n $queryp \n"; 	
echo "Prev: \n $prev \n\n"; 

};








if (!$dbnivel->close()){die($dbnivel->error());};

if(count($tosync)>0){foreach ($tosync as $point => $sql){
SyncModBD($sql,$id_tienda);
}}$tosync=array();



if (!$dbnivelAPP->open()){die($dbnivelAPP->error());};

$queryp= "SELECT (select codbarras from articulos where id=id_articulo) as cod from repartir WHERE id_tienda=$id_tienda AND cantidad > 0 AND id_articulo IN ($prev);";
$dbnivelAPP->query($queryp);
while ($row = $dbnivelAPP->fetchassoc()){$hazpedidos[$row['cod']]=1;};
if($debug){echo "$queryp \n hazpedidos: \n"; print_r($hazpedidos);  echo $dbnivelAPP->error();};

if (!$dbnivelAPP->close()){die($dbnivelAPP->error());};




if (!$dbnivelBAK->open()){die($dbnivelBAK->error());};

if(count($querys2)>0){foreach($querys2 as $idstl => $quer){
$dbnivelBAK->query($quer);	if($debug){echo "$quer \n\n"; echo $dbnivelBAK->error();};	
if(strlen($dbnivelBAK->error())==0){$querstldone[$idstl]=1;};	
}}

if (!$dbnivelBAK->close()){die($dbnivelBAK->error());};



if (!$dbnivel->open()){die($dbnivel->error());};

if(count($hazpedidos)>0){foreach($hazpedidos as $codapedir => $point){
$queryp= "INSERT INTO pedidos (codbarras) VALUES ($codapedir);";
$dbnivel->query($queryp);
if($debug){echo "$queryp \n creopedidos: \n";  echo $dbnivelAPP->error();};	
}}



if(count($querstldone)>0){foreach($querstldone as $idhecho => $pont){
$queryp= "delete from syncupdate where id=$idhecho;";
$dbnivel->query($queryp);	
}}
if (!$dbnivel->close()){die($dbnivel->error());};




if (!$dbnivelAPP->open()){die($dbnivelAPP->error()); $noconectado=1;};




echo "------ to queryshechas ----- \n";
print_r($queryshechas);
echo "------ to queryshechas ----- \n";

if(count($queryshechas)>0){foreach ($queryshechas as $idhecho => $nada) {
$queryp= "delete from syncupdate where id=$idhecho;";
$dbnivelAPP->query($queryp); echo $dbnivelAPP->error();
}}






if (!$dbnivelAPP->close()){die($dbnivelAPP->error());};




?>