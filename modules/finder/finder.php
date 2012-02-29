<?php
  require("../config/dbconfig.php");

init();
function init(){
  $myKey = hash('sha1',date("dd-mm-YYYY")."88886666");
  if(isset($_GET['key'])) (string) $key = $_GET['key'];

  if($key==$myKey){

    if(isset($_GET['action'])) (string) $action = $_GET['action'];

    switch($action){
      case "gettingSongs":
	gettingSongs();
	break;
      case "incrementListen":
	incrementSong();
	break;
      default:
	break;
    }
  }
}
function gettingSongs(){
  $where=getFilter();
  getSongs($where);
}
function getFilter(){
  if(isset($_GET['title'])) (string) $title = $_GET['title'];
  if(isset($_GET['album'])) (string) $album = $_GET['album'];
  if(isset($_GET['artist'])) (string) $artist = $_GET['artist'];
  if(isset($_GET['list'])) (string) $list = $_GET['list'];
  
  if($title) $where.="title LIKE '%".$title."%'";
  if($album){
    if($where) $where.=" AND "; 
    $where.="album LIKE '%".$album."%'";
  }
  if($artist){
    if($where) $where.=" AND ";
    $where.="artist LIKE '%".$artist."%'";
  }

  if(!$where) $where = "1 = 1";

  if($list){
    switch($list){
    case "top":
      $where.=" ORDER BY listens DESC LIMIT 5";
      break;
    default:
      $where.=" ORDER BY listens DESC LIMIT 5";
      break;
    }
  }
  return $where;
}
function getSongs($where="1=1") {

   $conn = new BD();
   $result = $conn->execute("SELECT * FROM songs WHERE ".$where);
   
    if (!$result) {
     die('Could not query');
    }
   $jsondata = array();
   $i = 0;
   $jsondata[0]['first'] = false;
   while ($row = pg_fetch_assoc($result)) {
     $jsondata[$i]['id'] = $row['id'];
     $jsondata[$i]['title'] = $row['title'];
     $jsondata[$i]['artist'] = $row['artist'];
     $jsondata[$i]['album'] = $row['album'];
     $jsondata[$i]['slength'] = $row['slength'];
     $jsondata[$i]['hashfile'] = $row['hashfile'];
     $jsondata[$i]['filepath'] = $row['filepath'];
     $jsondata[$i]['listens'] = $row['listens'];
     $jsondata[$i]['where'] = $where;
     $jsondata[$i]['first'] = true;
     $i++;
  }
  echo json_encode($jsondata);
 }

function incrementSong(){
   if(isset($_GET['idSong'])) (string) $idSong = $_GET['idSong'];
   $conn = new BD();
   $result = $conn->execute("SELECT listenSong('".$idSong."')");
   
    if (!$result) {
     die('Could not query');
    }
    $jsondata = array();
    $i = 0;
    $jsondata[0]['first'] = false;
    while ($row = pg_fetch_assoc($result)) {
      $jsondata[0]['listens'] = $row['listens'];
      $jsondata[0]['hashfile'] = $row['hashfile'];
      $jsondata[0]['first'] = true;
      $i++;
    }
    echo json_encode($jsondata);
}
?>
