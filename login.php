<script><?php
require("modules/components/dbconfig.php");
require("modules/components/config.inc");

  if( isset($_POST['username']) && isset($_POST['password']) )
  {
    if(doLogin($_POST['username'],$_POST['password'])){
      session_start();
      $_SESSION['user']=$_POST['username'];
    }
  }
  if(isset($_SESSION["user"]))
    echo "document.location='"._HOME_PATH."';";
  else
    echo "document.location='index.php?men=Invalid';";

  function doLogin($user,$passwd) {

   $conn = new BD();
   $result = $conn->execute("SELECT * FROM users WHERE username='".$user."' AND passwd='".$passwd."'");
   
    if (!$result) {
     die('Could not query');
    }

   if($row = pg_fetch_assoc($result))
     return true;

  return false;
 }
?>
</script>

<!--div data-role="fieldcontain">
	<input type="search" name="searchBox" id="searchBox" value="" onkeypress="searchBox_pressed(event)" />
      </div-->