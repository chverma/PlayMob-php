<?php
  $myKey = date("dd-mm-YYYY")."88886666";
  require("modules/components/config.inc");
  session_start();
  if(isset($_SESSION["user"])) 
    echo "<script>document.location='"._HOME_PATH."';</script>";
  
  $msg="";
  if(isset($_GET['men']))
    $msg=$_GET['men'];

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

<body><!-- onResize="resizeDocument(this)" --> 
  <div data-role="header" data-position="inline">
    <h1 id="logo" >#Login</h1>
  </div>
<div data-role="content" role="main">
<form action="login.php" method="post" data-ajax="false">
  <div data-role="fieldcontain" class="ui-hide-label">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" value="" placeholder="Username"/>
  </div>
  <div data-role="fieldcontain" class="ui-hide-label">
    <label for="password">Passwd:</label>
    <input type="password" name="password" id="password" value="" placeholder="Passwd"/>
  </div>

  <div data-role="fieldcontain">
    <div class="ui-block-b"><button type="submit" data-theme="a">Submit</button></div>
  </div>	
</form>
</div>
<div data-theme="a" data-role="footer">
                <h3>
                    #Login
                </h3>
            </div>  
<script>
    var currentKey = '<?php echo hash('sha1',$myKey);?>';
    <?php
      //if($msg!=""){
	echo "document.getElementById('username').setAttribute('class','error');";
	echo "document.getElementById('password').setAttribute('class','error');";
      //}
    ?>
</script>
</body>
</html>