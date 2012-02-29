<?php
$noError = true;
$songs = array();
require("../config/dbconfig.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
   <HEAD>
      <title>PlayMob: Play Mobility -> Indexer Files</title>
      <link rel="stylesheet" href="../../style/default.css" type="text/css">
      <meta http-equiv="content-type" content="text/html" charset="utf-8" />
   </HEAD>
   <BODY>
      <?php indexer_init(); ?>
   </BODY>
</HTML>

<?php

function indexer_init()
{
    global $songs;
    global $noError;
    if(indexer_requeriments()){
      indexer_indexingFolder(_SONGPATH);
      include('XMLSerializer.inc');
      //indexer_getXMLFromObjectArray($songs);
	if($noError)
		echo "<h1 class='successful'>The indexer process was finished successfully!</h1>";
      //CURRENT XML & HASH
      $newXML = utf8_encode(indexer_getXMLFromObjectArray($songs));
      $newHashXML = hash("md5",$newXML);

      //LAST XML & HASH stored in DB
      $dbXMLArray = getLastXML();
      $dbXML = $dbXMLArray['xml'];
      $dbHashXML = $dbXMLArray['hash'];

      //echo "<h1 class='successful'>$dbHashXML ; $newHashXML</h1>";
      if($dbHashXML != $newHashXML) {
	$diff = compareSongs($dbXML,$newXML);
      
	if(!saveXML($newXML)){
	  echo "<h1 class='error'>Error insert!</h1>";
	}else{
	  echo "<h1 class='successful'>Database was updated successfully!</h1>";
	}
      }else{
	echo "<h1 class='successful'>The database songs are already updated</h1>";
      }
    }
}

function indexer_requeriments()
{
	global $noError;
	if (!extension_loaded("ktaglib")){
		echo "<h1 class='error'>The library 'ktaglib' isn't loaded</h1>";
		$noError=false;
		return false;
	}
	return true;
}

function indexer_getXMLFromObjectArray($songs)
{
	return XMLSerializer::generateValidXmlFromObjectArray($songs,"songs","song");
}

class song {
    public $title;
    public $artist;
    public $album;
    public $length;
    public $filepath;
    public $hashfile;
    public function __construct($title,$artist,$album,$length,$filepath,$hash) {
        $this->title = $title;
	$this->artist = $artist;
	$this->album = $album;
	$this->length = $length;
	$this->filepath = $filepath;
	$this->hashfile = $hash;
    }

    public function getTitle() {
      return $this->title;
    }
    public function getArtist() {
      return $this->artist;
    }
    public function getAlbum() {
      return $this->album;
    }
    public function getLength() {
      return $this->length;//slength en la BD
    }
    public function getFilePath() {
      return $this->filepath;
    }
    public function getHash() {
      return $this->hashfile;
    }
}

function indexer_indexingFolder($folder)
{
   global $songs;
   global $noError;
   if(file_exists($folder)){
	  if ($currentDir = opendir($folder)) {
	    while (($file = readdir($currentDir)) != false ) {
	      $filePath = $folder."/".$file;
	      if (!is_dir($filePath)){
		$extension=strstr($file, '.');
		if($extension == '.mp3' || $extension == '.ogg'){
		  try{
		    $mpeg = new KTaglib_MPEG_File($filePath);
		  }catch(Exception $e){
		    echo "<h1 class='error'>Error-> ".$e->getMessage()."</h1>";
		    $noError=false;
		  }
		  $audioProperties = $mpeg->getAudioProperties();
		  $ID3v1Tag = $mpeg->getID3v1Tag();
		  $title = utf8_encode($ID3v1Tag->getTitle());
		  $artist = utf8_encode($ID3v1Tag->getArtist());
		  $album = utf8_encode($ID3v1Tag->getAlbum());
		  $length = utf8_encode($audioProperties->getLength());//slength en la BD
// 		  if($title!=""){
 		    try{
		      if($title=="" || !$title) $title = "Unknow title";
		      if($artist=="" || !$artist) $artist = "Unknow artist";
		      if($album=="" || !$album) $album = "Unknow album";
		      $song = new song($title,$artist,$album,$length,$filePath,hash_file('md5', $filePath));
		      array_push($songs,$song);
		    }catch(Exception $e){
		      echo "<h1 class='error'>Error-> ".$e->getMessage()."</h1>";
		      $noError=false;
		    }
// 		  }
		}
	      }else{
		if($file!="." && $file!=".."){
		  indexer_indexingFolder($filePath);
		}
	      }
	    }
	  }else{
	    echo "<h1 class='error'>Error-> This directory '".$folder."' isn't readable, please change this permissions</h1>";
	    $noError=false;
	  }
	  closedir($currentDir);
   }else{
     	echo "<h1 class='error'>Error-> This directory '".$folder."' isn't exist, please create it or change it</h1>";
	$noError=false;
   }

}

function getLastXML()
{
   $conn = new BD();
   $result = $conn->execute("SELECT xml,hash FROM indexedsongs WHERE fecha = (Select max(fecha) from indexedsongs)");
   $arr = array();
    if (!$result) {
     die('Could not query');
    }else{
      if ($row = pg_fetch_assoc($result)) {
	  $arr['xml'] = $row['xml'];
	  $arr['hash'] = $row['hash'];
      }
    }
  return $arr;
}

function saveXML($xml){
    $conn = new BD();
    $result = $conn->execute("INSERT INTO indexedsongs (xml,hash) VALUES ('".$xml."','".hash("md5",$xml)."')");
    if (!$result){
      return false;
    } else {
     return true;
    }
}

function compareSongs($old,$new){
  $oldXml = simplexml_load_string($old);
  $oldArray = array();
  foreach ($oldXml->song as $song) {
    $oldArray[] = $song->hashfile;
  }
  sort($oldArray,SORT_STRING);

  $newXml = simplexml_load_string($new);
  $newArray = array();
  foreach ($newXml->song as $song) {
    $newArray[] = $song->hashfile;
  }
  sort($newArray,SORT_STRING);
  $contNew=0;
  $contOld=0;
  //echo "<h1 class='error'>Size old: ".count($oldArray)."</h1>";
  //echo "<h1 class='error'>Size new: ".count($newArray)."</h1>";
  while($contNew<count($newArray) && $contOld<count($oldArray)){
    $result = strcmp ($newArray[$contNew],$oldArray[$contOld]);
     //echo "ITERACIO-> ".$newArray[$contNew]." - ".$oldArray[$contOld]."<br/>";
    if($result > 0 ) {
      echo "<h1 class='error'>Removed: ".$oldArray[$contOld]."</h1>";
      $conn = new BD();
      $conn->execute("DELETE FROM songs where hashfile='".$oldArray[$contOld]."'");
      $contOld++;
    }else if($result < 0){
      $result = $newXml->xpath("/songs/song[hashfile='".$newArray[$contNew]."']");
      while(list( , $node) = each($result)) {
	echo "<h1 class='successful'>Added: ".$node->title."</h1>";
	$conn = new BD();
	$conn->execute("INSERT INTO songs (title,filepath,hashfile,artist,album) VALUES ('".$node->title."','".$node->filepath."','".$node->hashfile."','".$node->artist."','".$node->album."')");
      }
      $contNew++;
    }else{
      //echo "Equals-> ".$newArray[$contNew]." - ".$oldArray[$contOld]."<br/>";
      $result = $newXml->xpath("/songs/song[hashfile='".$newArray[$contNew]."']");
      $newFilePath="";
      while(list( , $node) = each($result)) {
	$newFilePath = $node->filepath;
      }
      $result = $oldXml->xpath("/songs/song[hashfile='".$oldArray[$contOld]."']");
      $oldFilePath="";
      while(list( , $node) = each($result)) {
	$oldFilePath = $node->filepath;
      }
      if(strcmp($oldFilePath,$newFilePath)!=0){
	echo "<h1 class='successful'>Updated: ".substr($newFilePath, 12)."</h1>";
	$conn = new BD();
	$conn->execute("UPDATE songs SET filepath='".$newFilePath."' WHERE hashfile = '".$newArray[$contNew]."'");
      }
      $contNew++;
      $contOld++;
    }
    //echo $newArray[$contNew]." - ".$oldArray[$contOld]."<br/>";
  }
  return "diff";
}
?>

	
