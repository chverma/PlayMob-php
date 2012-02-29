<!DOCTYPE html> 
<html> 
<head> 
	<meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title>PlayMob: Play Mobility!</title> 
	<link rel="stylesheet" href="jquery.mobile-1.0b2.min.css" />
	<script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
	<script src="jquery.mobile-1.0b2.min.js"></script>
	
</head> 
<body> 
<div data-role="header" data-position="inline">
 	<a href="/PlayMob/" data-icon="home">Inicio</a> 
	<h1>PlayMob: Play Mobility!</h1>
</div>

<ul data-role="listview" class="ui-listview">
	<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c">
		  <div class="ui-btn-inner ui-li">
		      <div class="ui-btn-text">
			  <!--a href="#" class="ui-link-inherit" data-transition="slide">-->
			  <!--<img src="images/intro_menaje.jpg" class="ui-li-thumb">-->
			  <h3 class="ui-li-heading">Menaje</h3>
			  <p class="ui-li-desc">
			      <audio controls>
			      </audio>
			<!--  </a>-->
		      </div>
		  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>
		  </div>
	</li>
</ul>
<script type="text/javascript">
  var src1 = document.createElement("source");
  src1.setAttribute("src","files/SeanBienvenidos.ogg");

  var src2 = document.createElement("source");
  src2.setAttribute("src","files/01.Por_Lo_Visto.mp3");

  var audios = document.getElementsByTagName("audio");
  audios[0].appendChild(src1);
  audios[0].appendChild(src2);
</script>
</body>
</html>