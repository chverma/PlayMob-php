<?php
  require("../components/dbconfig.php");

init();
function init(){
  $myKey = hash('sha1',date("dd-mm-YYYY")."88886666");
  $iduser="";
  if(isset($_GET['key'])) (string) $key = $_GET['key'];
  if(isset($_GET['iduser'])) (string) $iduser = $_GET['iduser'];

  if($key==$myKey){

    if(isset($_GET['action'])) (string) $action = $_GET['action'];

    switch($action){
      case "gettingSongs":
	gettingSongs($iduser);
	break;
      case "incrementListen":
	incrementSong();
	break;
      case "gettingAlbums":
	gettingAlbums($iduser);
	break;
      case "gettingArtists":
	gettingArtists($iduser);
	break;
      default:
	break;
    }
  }
}
function gettingSongs($iduser){
  $where=getFilter();
  getSongs($iduser,$where);
}

function gettingAlbums($iduser){
  $where=getFilter();
  getAlbums($iduser,$where);
}

function gettingArtists($iduser){
  $where=getFilter();
  getArtists($iduser,$where);
}

function getLimit(){
  $orderBy="";
  $list="";
  $fromPos = 0;
  if(isset($_GET['pos'])){ 
    (integer) $fromPos = $_GET['pos'];
    $fromPos=$fromPos*6;
  }
  $limit = "LIMIT 6 OFFSET $fromPos";
  return $limit;
}
function getFilter(){
  $title="";
  $album="";
  $artist="";
  $toPos=0;
  $fromPos=0;
  $where="";
  if(isset($_GET['title'])) (string) $title = $_GET['title'];
  if(isset($_GET['idalbum'])) (string) $album = $_GET['idalbum'];
  if(isset($_GET['idartist'])) (string) $artist = $_GET['idartist'];
    
  if($title!=""){
    $where.=" AND ";
    $where.="songs.title ILIKE '%".$title."%'";
  }

  if($album!=""){
    $where.=" AND ";
    $where.="albums.idalbum =".$album;
  }

  if($artist!=""){
    $where.=" AND ";
    $where.="artist.idartist = ".$artist;
  }

  //if($where==""){ $where = "1 = 1"; }
  return $where;
}

function getSongs($iduser,$where) {
    $conn = new BD();
    $sql = "SELECT songs.title AS title, artist.idartist AS idartist,artist.name AS artist, albums.idalbum AS idalbum, albums.title AS album, songs.listens AS listens,filepath,hashfile ";
    $sql.= "FROM songs ";
    $sql.= "INNER JOIN albums ON songs.idalbum = albums.idalbum ";
    $sql.= "INNER JOIN artist ON albums.idartist = artist.idartist ";
    $sql.= "WHERE songs.iduser = ".$iduser." ".$where." ";
    $sql.= "ORDER BY listens DESC ";
    $sql.= getLimit();

   $result = $conn->execute($sql);
   
    if (!$result) {
     die('Could not query');
    }
   $jsondata = array();
   $i = 0;
   $jsondata[0]['first'] = false;
   while ($row = pg_fetch_assoc($result)) {
     $jsondata[$i]['title'] = $row['title'];
     $jsondata[$i]['idartist'] = $row['idartist'];
     $jsondata[$i]['artist'] = $row['artist'];
     $jsondata[$i]['idalbum'] = $row['idalbum'];
     $jsondata[$i]['album'] = $row['album'];
     //$jsondata[$i]['slength'] = $row['slength'];
     $jsondata[$i]['hashfile'] = $row['hashfile'];
     $jsondata[$i]['filepath'] = $row['filepath'];
     $jsondata[$i]['listens'] = $row['listens'];
     $jsondata[$i]['where'] = $sql;
     $jsondata[$i]['first'] = true;
     $i++;
  }
  echo json_encode($jsondata);
 }

function getAlbums($iduser,$where) {
    $conn = new BD();
    $sql = "SELECT albums.idalbum AS idalbum,albums.title AS title , artist.idartist AS idartist, artist.name AS artist, SUM(listens) as listens ";
    $sql.= "FROM albums ";
    $sql.= "INNER JOIN artist ON albums.idartist = artist.idartist ";
    $sql.= "INNER JOIN songs ON albums.idalbum = songs.idalbum ";
    $sql.= "WHERE albums.iduser = ".$iduser." ".$where." ";
    $sql.= "GROUP BY albums.idalbum,artist.idartist,albums.iduser ";
    $sql.= "ORDER BY albums.idalbum,albums.title, artist.idartist, artist.name, albums.iduser,SUM(listens) DESC ";
    $sql.= getLimit();
    $result = $conn->execute($sql);
   
    if (!$result) {
     die('Could not query');
    }
   $jsondata = array();
   $i = 0;
   $jsondata[0]['first'] = false;
   while ($row = pg_fetch_assoc($result)) {
     $jsondata[$i]['idalbum'] = $row['idalbum'];
     $jsondata[$i]['title'] = $row['title'];
     $jsondata[$i]['idartist'] = $row['idartist'];
     $jsondata[$i]['artist'] = $row['artist'];
     $jsondata[$i]['where'] = $sql;
     $jsondata[$i]['first'] = true;
     $i++;
  }
  echo json_encode($jsondata);
 }

function getArtists($iduser,$where) {
    $conn = new BD();
    $sql = "SELECT artist.idartist AS idartist, artist.name AS name,SUM(songs.listens) as listens ";
    $sql.= "FROM artist ";
    $sql.= "INNER JOIN songs ON artist.idartist = songs.idartist ";
    $sql.= "WHERE artist.iduser = ".$iduser." ".$where." ";
    $sql.= "GROUP BY artist.idartist,artist.iduser ";
    $sql.= "ORDER BY artist.idartist, artist.name,artist.iduser,SUM(listens) DESC ";
    $sql.= getLimit();

    $result = $conn->execute($sql);
   
    if (!$result) {
     die('Could not query');
    }
   $jsondata = array();
   $i = 0;
   $jsondata[0]['first'] = false;
   while ($row = pg_fetch_assoc($result)) {
     $jsondata[$i]['idartist'] = $row['idartist'];
     $jsondata[$i]['name'] = $row['name'];
     $jsondata[$i]['where'] = $sql;
     $jsondata[$i]['first'] = true;
     $i++;
  }
  echo json_encode($jsondata);
 }

function incrementSong(){
   if(isset($_GET['idSong'])) (string) $idSong = $_GET['idSong'];
   $conn = new BD();
   $result = $conn->execute("SELECT * FROM listenSong('".$idSong."') AS foo(listens integer, hashfile varchar)");
   
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
