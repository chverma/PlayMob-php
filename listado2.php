<?

$tipo = $_GET['tipo'];
$id =   (int) $_GET['accion'];
if (!isset($_GET['accion'])) $id=1;
$stop =   (int) $_GET['stop'];

$actual=$id;
//tiempo



     
$tiempo=file("fotosFTP/tiempo_fotos.txt");
$lineast = count($tiempo); //contamos los elementos del array
for($it=0; $it < $lineast; $it++){
      if($it==1) $tiempo_play=$tiempo[$it];
      if($it==3) $tiempo_stop=$tiempo[$it];
}

//Listar archivos
$dir="fotosproductos/".$tipo;
$directorio=opendir($dir);
$num_archivos=0;
while ($archivo = readdir($directorio)){
    $extension = explode(".", $archivo);
  switch ($extension[1]) {
    case "PNG":
    case "png":
    case "JPG":
    case "JPEG":
    case "jpg":
    case "jpeg":
    case "gif":
    case "GIF":
        $num_archivos++;
        break;
}
  //if($archivos_jpg = strstr($archivo, '.jpg'))$num_archivos++; 
}
closedir($directorio);
//echo "Fotos en carpeta =".$num_archivos;
if ($id==0 || $id<1) $actual=$num_archivos;
if ($id>$num_archivos) {$actual=1;$llegoalfinal=1;}

$anterior=$actual-1;
$siguiente=$actual+1;
$precio=$actual*157;


?>
<? // print "id=$id | actual=$actual | anterior=$anterior | siguiente=$siguiente"; ?>
<? 
if (!$stop) // ESTA EN PLAY
{ 
		$txt_carrusel=$txt_carrusel_play;
		$tecla_z=$tecla_x=$tecla_c = "listado.php&tipo=$tipo&accion=$actual&stop=1";
		$urldestino="index.php?pag=listado.php&tipo=$tipo&accion=$siguiente";
		if ($llegoalfinal) $urldestino="index.php?pag=portada0.html";
		
?>	
<!-- 		<META HTTP-EQUIV="REFRESH" CONTENT="<? // echo $tiempo_play;?>;URL=<? // = //$urldestino;?>"> -->
<? } 
else { // ESTA EN STOP 
		$txt_carrusel=$txt_carrusel_stop;
		$tecla_z="listado.php&tipo=$tipo&accion=$anterior&stop=1";
		$tecla_x="portada1.html";
		$tecla_c="listado.php&tipo=$tipo&accion=$siguiente&stop=1";
?> 
<!-- 		<META HTTP-EQUIV="REFRESH" CONTENT="<? // echo $tiempo_stop;?>;URL=index.php?pag=listado.php&tipo=<? //=$tipo?>&accion=<? //=$siguiente?>&stop=0"> -->
<? } ?>


<?
//TEXTOS
//$textoproducto=file_get_contents("fotosFTP/$tipo/{$tipo}{$actual}.txt");
//$textoproducto = str_replace("\n","<br/>",$textoproducto);
//PROVA
?>



<!-- <h1>El Aluminio</h1> -->

<div data-role="header" data-position="inline">

<!-- 	<a href="index.html" data-icon="back">Cancel</a> -->
	<h1>El Aluminio</h1>
<!--	<div data-role="navbar">
	<ul>
	<li><a href="<?php echo "index2.php?pag=listado2.php&tipo=$tipo&accion=$anterior&stop=1" ?>" data-icon="arrow-l" data-transition="slide" class="ui-btn-active">Anterior</a></li>
	<li><a href="<?php echo "/mobil_cano/index.html" ?>" data-icon="home" class="ui-btn-active">Inicio</a></li>
	<li><a href="<?php echo "index2.php?pag=listado2.php&tipo=$tipo&accion=$siguiente&stop=1" ?>" data-icon="arrow-r" data-transition="slide" class="ui-btn-active">Siguiente</a></li>
	</ul>
	</div>-->
</div>
<center>
<!-- <h1><? print $tipo . " n� " . $actual; ?></h1> -->
<img src="<? if(file_exists("fotosproductos/$tipo/{$tipo}{$actual}.jpg")){
                print "fotosproductos/$tipo/{$tipo}{$actual}.jpg";
            }else{
                print "fotosproductos/$tipo/{$tipo}{$actual}.jpeg";
            }?>" style="width:90%;"/>
<? if(file_exists("fotosFTP/$tipo/{$tipo}{$actual}.txt")){
        $archivo_texto = file("fotosFTP/$tipo/{$tipo}{$actual}.txt"); //creamos el array con las lineas del archivo
        $lineas = count($archivo_texto); //contamos los elementos del array
        for($i=0; $i < $lineas; $i++){
            if($i==0) 
                    $titulo=$archivo_texto[$i];
        }
    }
?>
</center>
<div  data-role="footer" data-position="fixed"> 
	<div data-role="navbar">
	<ul>
	<li><a href="<?php echo "index2.php?pag=listado2.php&tipo=$tipo&accion=$anterior&stop=1" ?>" data-icon="arrow-l" data-transition="slide" class="ui-btn-active">Anterior</a></li>
	<li><a href="<?php echo "/mobil_aluminio/index.html" ?>" data-icon="home" class="ui-btn-active">Inicio</a></li>
	<li><a href="<?php echo "index2.php?pag=listado2.php&tipo=$tipo&accion=$siguiente&stop=1" ?>" data-icon="arrow-r" data-transition="slide" class="ui-btn-active">Siguiente</a></li>
	</ul>
</div> 

<!--<div class="logos" style="widht:1024px;">
	<img src="logos/footer_santicano.png">
</div>-->