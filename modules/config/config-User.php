<?php
  session_start();
  if(!isset($_SESSION["user"]))
    echo "<script>document.location='/PlayMob/';</script>";

  $myKey = date("dd-mm-YYYY")."88886666";
  require("../components/config.inc");
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
  <!--script type="text/javascript" src="ajax.js"></script-->
  <link rel="stylesheet" href="styles.css" />
</head>

<body onLoad=""><!-- onResize="resizeDocument(this)" --> 
  <div data-role="header" data-position="inline">
    <a data-icon="home" href="<?php echo _HOME_PATH;?>" data-ajax="false">Home</a> 
    <h1 id="logo" >#MobUser<?php echo _LOGO;?></h1>
  </div>
  <div data-role="content" role="main">
  <div class="content-primary" >
      <div data-role="navbar" data-iconpos="top" >
                    <ul>
                        <li>
                            <a id="btnConfig" href="config-Player.php" data-theme="a" data-icon="gear">
                                #Settings
                            </a>
                        </li>
                        <li>
                            <a id="btnUser" href="#" data-theme="a" data-icon="home" class="ui-btn-active">
                                #<?php echo $_SESSION["user"];?>
                            </a>
                        </li>
                    </ul>
      </div>
  </div>  
  <div class="content-secondary">
    <div id="User">
	<div data-role="fieldcontain">
                    <fieldset data-role="controlgroup">
                        <label for="userName">#User</label>
                        <input id="filepath" placeholder="/path/" value="" type="text" />
                    </fieldset>
                </div>
    </div>
    
   </div>
</div>
<div data-theme="a" data-role="footer">
                <h3>
                    #ModUser
                </h3>
            </div>  
<script>
    var currentKey = '<?php echo hash('sha1',$myKey);?>';
</script>
</body>
</html>
