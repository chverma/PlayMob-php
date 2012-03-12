<?php
  require("../components/dbconfig.php");

init();
function init(){
  $myKey = hash('sha1',date("dd-mm-YYYY")."88886666");
  if(isset($_GET['key'])) (string) $key = $_GET['key'];

  if($key==$myKey){
    if(isset($_GET['menu'])) (string) $menu = $_GET['menu'];
    getCategories($menu);
  }
}

function getCategories($menu) {

   $conn = new BD();
   $result = $conn->execute("SELECT * FROM categories WHERE visible=TRUE AND menu='".$menu."'"); 
   
    if (!$result) {
     die('Could not query');
    }
   $jsondata = array();
   $i = 0;
   $jsondata[0]['first'] = false;
   while ($row = pg_fetch_assoc($result)) {
     $jsondata[$i]['idcat'] = $row['idcat'];
     $jsondata[$i]['name'] = $row['name'];
     $jsondata[$i]['path'] = $row['path'];
     if($row['ajax']!="f")
	$jsondata[$i]['ajax'] = "true";
      else
	$jsondata[$i]['ajax'] = "false";
     $jsondata[$i]['first'] = true;
     $i++;
  }
  echo json_encode($jsondata);
 }
?>
