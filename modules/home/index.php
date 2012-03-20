<?php
  session_start();
  require("../components/config.inc");
  if(!isset($_SESSION["user"]))
    echo "<script>document.location='"._LOGINPATH."';</script>";

  $myKey = date("dd-mm-YYYY")."88886666";
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
</head>

<body onLoad="getCategories('home')"><!-- onResize="resizeDocument(this)" --> 
  <div data-role="header" data-position="inline">
    <!--a data-icon="home" href="" data-ajax="false">Home</a--> 
    <h1 id="logo" >#<?php echo $_SESSION["user"];?>'s music</h1>
    <a id="btnLogOut" href="<?php echo _LOGINPATH."login.php?logOut=true";?>" data-icon="back" data-ajax="false" class="ui-btn-right">Log Out</a>
  </div>
<div data-role="content" role="main">
  <ul id="categories" class="ui-listview" data-role='listview'>
	<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c">
		  <div class="ui-btn-inner ui-li">
		      <div class="ui-btn-text">
			  <h3 class="ui-li-heading">Home Menu Not Charged :$</h3>
		      </div>
		  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>
		  </div>
	</li>
    </ul>
</div>
<div data-role="footer" data-position="inline">
                <?php echo _FOOTER ?>
            </div>  
<script>
    var currentKey = '<?php echo hash('sha1',$myKey);?>';
</script>
</body>
</html>
