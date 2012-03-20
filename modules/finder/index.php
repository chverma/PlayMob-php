<?php
  session_start();
  if(!isset($_SESSION["user"]))
    echo "<script>document.location='/PlayMob/';</script>";
    
  $myKey = date("dd-mm-YYYY")."88886666";
  //include_once("mobiledetect.php");
  require("../components/config.inc");
  if(isset($_GET['by']))
    $by = $_GET['by'];

  switch($by){
    case "album":
      if(isset($_GET['idartist']))
	$loadList="loadAlbumList('&idartist=".$_GET['idartist']."')";
      else {
	$loadList="loadAlbumList('&list=top')";
      }
      
      $extendList="extendAlbumsList();";
      break;
    case "artist":
      $loadList="loadArtistList('&list=top')";
      $extendList="extendArtistList();";
      break;
    case "song":
    default:
      if(isset($_GET['idalbum']))
	$loadList="loadSongList('&idalbum=".$_GET['idalbum']."')";
      else {
	$loadList="loadSongList('&list=top')";
      }
      
      $extendList="extendSongsList();";
      break;
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-es" lang="es-es">
<head>
  <meta http-equiv="content-type" content="text/html" charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1"> 
  <title>PlayMob: Play Mobility!</title>
  <!-- JQueryMobile js & css -->
  <link rel="stylesheet" href="<?php echo _LIBRARY_PATH;?>JQueryMobile/jquery.mobile-1.0b2.min.css" />
  <script src="<?php echo _LIBRARY_PATH;?>JQueryMobile/jquery.min.1.6.js"></script><!--http://code.jquery.com/jquery-1.6.2.min.js-->
  <script src="<?php echo _LIBRARY_PATH;?>JQueryMobile/jquery.mobile-1.0b2.min.js"></script>
  <!-- modifications -->
  <script type="text/javascript" src="ajax.js"></script>
  <link rel="stylesheet" href="styles.css" />
  <script src="<?php echo _LIBRARY_PATH;?>johnPlayer/build/mediaelement-and-player.min.js"></script>
  <link rel="stylesheet" href="<?php echo _LIBRARY_PATH;?>johnPlayer/build/mediaelementplayer.min.css" />
</head>

<body onload="<?php echo $loadList; ?>"><!-- onResize="resizeDocument(this)" --> 
  <div data-role="header" data-position="inline">
    <a data-icon="home" href="<?php echo _HOME_PATH;?>" data-ajax="false">Home</a> 
    <h1 id="logo" ><?php echo _LOGO;?></h1>
    <div id="searchPanel" >
      <input type="text" id="searchBox" onkeypress="searchBox_pressed(event)" value="Search" onblur="if(value=='') value = 'Search'" onfocus="if(value=='Search') value = ''">
      <a onclick="searchBox_clicked()" id="btnSearch" ></a>
    </div>
    <!--a id="allSongs" onclick="loadSongList('top')">All Songs</a-->
  </div>

  <div data-role="content">
    <ul id="listView" class="ui-listview" data-role='listview'>
	<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c">
		  <div class="ui-btn-inner ui-li">
		      <div class="ui-btn-text">
			  <h3 class="ui-li-heading">Search it!</h3>
		      </div>
		  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>
		  </div>
	</li>
    </ul>
  </div>

<div id="footer" data-role="footer" data-position="inline">
  <audio id="player" src="#" type="audio/mp3" controls="controls">		
  </audio>
  <script>
    <?php
      //$uagent = new uagent_info();
      //if($uagent->DetectTierTablet()){
	echo "var winW = $(window).width();";
	echo "$('#player').mediaelementplayer({audioWidth: winW,audioHeight: 30});";
      //} ?>
    $(window).scroll(function(){
      if ($(window).scrollTop() == $(document).height() - $(window).height()){
	//if(!firstRun)
	  <?php echo $extendList; ?>
      }
    });
    var currentKey = '<?php echo hash('sha1',$myKey);?>';
    var idUser = '<?php echo $_SESSION["iduser"];?>';
  </script>
</div>

</body>
</html>
