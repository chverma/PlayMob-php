<script><?php
require("modules/components/dbconfig.php");
require("modules/components/config.inc");
session_start();

  if(isset($_GET['logOut'])) doLogOut();

  if( isset($_POST['username']) && isset($_POST['password']) && isset($_POST['login']) )
  {
    doLogin($_POST['username'],$_POST['password']);
  }
  else if( isset($_POST['username']) && isset($_POST['password']) && isset($_POST['register']) )
  {
    //doLogin($_POST['username'],$_POST['password']);
    doRegister($_POST['username'],$_POST['password']);
  }

  function doLogin($user,$passwd) {

   $conn = new BD();
   $result = $conn->execute("SELECT * FROM users WHERE username='".$user."' AND passwd='".$passwd."'");
   
    if (!$result) {
     echo "document.location='index.php?men=Could not query';";
    }

   if($row = pg_fetch_assoc($result)){
      $_SESSION["iduser"] = $row['iduser'];
      $_SESSION['user'] = $row['username'];
      echo "document.location='"._HOME_PATH."';";
    }else{
      echo "document.location='index.php?men=Invalid User and Password combination';";
    }

  return false;
 }

  function doLogOut() {
    $_SESSION["iduser"]="";
    $_SESSION["user"]="";
    session_destroy();
    echo "document.location='index.php'";
  }

  function doRegister($user,$passwd){

    $conn = new BD();
    $result = $conn->execute("SELECT * FROM users WHERE username='".$user."'");
    if($row = pg_fetch_assoc($result)){
      echo "document.location='index.php?men=User $user already exist';";   
    }else{
      $conn = new BD();
      $result = $conn->execute("INSERT INTO users(username,passwd) VALUES('".$user."','".$passwd."')");
      if (!$result) { echo "document.location='index.php?men=Could not query';"; }
      else{ doLogin($user,$passwd);}
    }
  }
?>
</script>

<!--div data-role="fieldcontain">
	<input type="search" name="searchBox" id="searchBox" value="" onkeypress="searchBox_pressed(event)" />
      </div-->