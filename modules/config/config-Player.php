<?php
  session_start();
  if(!isset($_SESSION["user"]))
    echo "<script>document.location='/PlayMob/';</script>";

  $myKey = date("dd-mm-YYYY")."88886666";
  include_once("mobiledetect.php");
  require("../components/config.inc");
  require("../components/dbconfig.php");
  $conn = new BD();
  if(isset($_POST['filepath'])){
    $fdbSongPath=$_POST['filepath'];
    $result = $conn->execute("UPDATE users SET songspath='".$fdbSongPath."' WHERE iduser=".$_SESSION["iduser"]);   
    if (!$result) { die('Could not query'); }
  }else{
    $result = $conn->execute("SELECT songspath FROM users WHERE iduser=".$_SESSION["iduser"]);   
    if (!$result) { die('Could not query'); }
    while ($row = pg_fetch_assoc($result)) {
     $fdbSongPath = $row['songspath'];
    }
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
  <!--script type="text/javascript" src="ajax.js"></script-->
  <link rel="stylesheet" href="styles.css" />
</head>

<body onLoad=""><!-- onResize="resizeDocument(this)" --> 
  <div data-role="header" data-position="inline">
    <a data-icon="home" href="<?php echo _HOME_PATH;?>" data-ajax="false">Home</a> 
    <h1 id="logo" >#<?php echo $_SESSION["user"]."'s settings: Player Config";?></h1>
  </div>
  <div data-role="content" role="main">
  <div class="content-primary" >
      <div data-role="navbar" data-iconpos="top" >
                    <ul>
                        <li>
                            <a id="btnConfig" href="#" data-theme="a" data-icon="gear" class="ui-btn-active" data-ajax="false">
                                #Settings
                            </a>
                        </li>
                        <li>
                            <a id="btnUser" href="config-User.php" data-theme="a" data-icon="home" data-ajax="false">
                                #<?php echo $_SESSION["user"];?>
                            </a>
                        </li>
                    </ul>
      </div>
  </div>  
  <div class="content-secondary">
    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" data-ajax="false">
      <div data-role="fieldcontain">
	<fieldset data-role="controlgroup">
	  <label for="filepath">Where are the song files?</label>
	  <input id="filepath" name="filepath" placeholder="/path/to/files/" value="<?php echo $fdbSongPath; ?>" type="text" />
	</fieldset>
      </div>

      <div data-role="fieldcontain">
	<div class="ui-block-b"><button type="submit" data-theme="a" data-icon="check">Save</button></div>
      </div>	
    </form>
   </div>
</div>
  <div data-theme="a" data-role="footer">
                <?php echo _FOOTER ?>
            </div>
<script>
    var currentKey = '<?php echo hash('sha1',$myKey);?>';
  </script>
</body>
</html>
