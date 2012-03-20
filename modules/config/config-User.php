<?php
  session_start();
  if(!isset($_SESSION["user"]))
    echo "<script>document.location='/PlayMob/';</script>";

  $myKey = date("dd-mm-YYYY")."88886666";
  require("../components/config.inc");
  require("../components/dbconfig.php");

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
  <!--ToastMessage -->
  <script src="<?php echo _LIBRARY_PATH;?>akquinet-toastmessage/jquery.toastmessage.js" type="text/javascript"></script>
  <link href="<?php echo _LIBRARY_PATH;?>akquinet-toastmessage/jquery.toastmessage.css" rel="stylesheet" type="text/css" />
  <!-- modifications -->
  <!--script type="text/javascript" src="scripts.js"></script-->
  <link rel="stylesheet" href="styles.css" />
</head>

<body onLoad=""><!-- onResize="resizeDocument(this)" --> 
  <div data-role="header" data-position="inline">
    <a data-icon="home" href="<?php echo _HOME_PATH;?>" data-ajax="false">Home</a> 
    <h1 id="logo" >#<?php echo $_SESSION["user"]."'s settings: User Account";?></h1>
  </div>
  <div data-role="content" role="main">
  <div class="content-primary" >
      <div data-role="navbar" data-iconpos="top" >
                    <ul>
                        <li>
                            <a id="btnConfig" href="config-Player.php" data-theme="a" data-icon="gear" data-ajax="false">
                                #Settings
                            </a>
                        </li>
                        <li>
                            <a id="btnUser" href="#" data-theme="a" data-icon="home" class="ui-btn-active" data-ajax="false">
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
	  <label for="username">User Name</label>
	  <input id="username" name="username" placeholder="<?php echo $_SESSION['user']; ?>" type="text" disabled="true"/>
	</fieldset>
      </div>
      <div data-role="fieldcontain">
	<fieldset data-role="controlgroup">
	  <label for="password">Password</label>
	  <input id="password" name="password" type="password" />
	</fieldset>
      </div>
      <div data-role="fieldcontain">
	<fieldset data-role="controlgroup">
	  <label for="repassword">Repeat Password</label>
	  <input id="repassword" name="repassword" type="password" />
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
    <?php
       if( isset($_POST['password']) || isset($_POST['repassword']) ){
	  $passwd = $_POST['password'];
	  $rePasswd=$_POST['repassword'];
	  if($passwd != $rePasswd){
	      echo "$().toastmessage('showErrorToast', 'Password does not match!');";
	  }else{
	      $conn = new BD();
	      $result = $conn->execute("UPDATE users SET passwd='".$passwd."' WHERE iduser=".$_SESSION["iduser"]);   
	      if (!$result) { die('Could not query'); }
	      else { echo "$().toastmessage('showSuccessToast', 'Password saved!');"; }
	  }
	}
    ?>
</script>
</body>
</html>
