<?php
$URLACTUAL = $_SERVER["REQUEST_URI"];

$txt_portadas="elije categor&iacute;a y pulsa [ || ] para entrar";
$txt_carrusel_play="cualquier bot&oacute;n para pausar carrusel";
$txt_carrusel_stop="[<]&nbsp;&nbsp;&nbsp;&nbsp;[salir]&nbsp;&nbsp;&nbsp;&nbsp;[>]";

$pagina = $_GET['pag'];
if ($pagina)
{ print '
		<!DOCTYPE html> 
		<html> 
		<head> 
			<meta charset="utf-8"> 
			<meta name="viewport" content="width=device-width, initial-scale=1"> 
			<title>El Aluminio</title> 
			<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.css" />
			<script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
			<script src="http://code.jquery.com/mobile/1.0b2/jquery.mobile-1.0b2.min.js"></script>
			
		</head> 
		<body>
		';

		$pagina = $_GET['pag'];	
		if (file_exists($pagina)) 
		{	
			include($pagina); 												
		}
print '
		
		</body>
		</html>
		';
}

?>
