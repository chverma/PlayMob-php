<?php
$noError = true;
$songs = array();
require("../components/dbconfig.php");
session_start();
  if(!isset($_SESSION["user"]))
    echo "<script>document.location='"._LOGINPATH."';</script>";

$conn= new BD();
$fdbSongPath="";
$result = $conn->execute("SELECT songspath FROM users WHERE iduser=".$_SESSION["iduser"]);   
    if (!$result) { die('Could not query'); }
    while ($row = pg_fetch_assoc($result)) {
     $fdbSongPath = $row['songspath'];
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

<body><!-- onResize="resizeDocument(this)" --> 
  <div data-role="header" data-position="inline">
    <a data-icon="home" href="<?php echo _HOME_PATH;?>" data-ajax="false">Home</a>
    <h1 id="pagTitle" ># Update your song list ;-)</h1>
  </div>
<div data-role="content" role="main">
  <ul id="messages" class="ui-listview" data-role='listview'>
	<?php indexer_init(); ?>
    </ul>
</div>
<div data-theme="a" data-role="footer">
                <?php echo _FOOTER ?>
            </div>  
<script>
    var currentKey = '<?php echo hash('sha1',$myKey);?>';
</script>
</body>
</html>


<?php

function indexer_init()
{
    global $songs;
    global $noError;
    global $fdbSongPath;
    if(indexer_requeriments()){
      indexer_indexingFolder($fdbSongPath);
      include('XMLSerializer.inc');
      if($noError){
	echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c">';
	echo '  <div class="ui-btn-inner ui-li">';
	echo '	      <div class="ui-btn-text">';
	echo '		  <h3 class="ui-li-heading">The indexer process was finished successfully :)</h3>';
	echo '	      </div>';
	echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
	echo '  </div>';
	echo '</li>';
      }else 
	die('');

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
	    echo '<li data-theme="c" style="color:red;" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c">';
	    echo '  <div class="ui-btn-inner ui-li">';
	    echo '	      <div class="ui-btn-text">';
	    echo '		  <h3 class="ui-li-heading">Error insert :(</h3>';
	    echo '	      </div>';
	    echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
	    echo '  </div>';
	    echo '</li>';
	  }else{
	    echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c">';
	    echo '  <div class="ui-btn-inner ui-li">';
	    echo '	      <div class="ui-btn-text">';
	    echo '		  <h3 class="ui-li-heading">Database was updated successfully :)</h3>';
	    echo '	      </div>';
	    echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
	    echo '  </div>';
	    echo '</li>';
	  }
      }else{
	  echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c">';
	  echo '  <div class="ui-btn-inner ui-li">';
	  echo '	      <div class="ui-btn-text">';
	  echo '		  <h3 class="ui-li-heading">The database songs are already updated</h3>';
	  echo '	      </div>';
	  echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
	  echo '  </div>';
	  echo '</li>';
      }
    }
}

function indexer_requeriments()
{
	global $noError;
	global $fdbSongPath;
	if (!extension_loaded("ktaglib")){
	  echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c" style="color:red;">';
	  echo '  <div class="ui-btn-inner ui-li">';
	  echo '	      <div class="ui-btn-text">';
	  echo '		  <h3 class="ui-li-heading">The library \'ktaglib\' isn\'t loaded :$</h3>';
	  echo '	      </div>';
	  echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
	  echo '  </div>';
	  echo '</li>';
		$noError=false;
		return false;
	}

	if ($fdbSongPath=="../../relativePath"){
	  echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c" style="color:red;">';
	  echo '  <div class="ui-btn-inner ui-li">';
	  echo '	      <div class="ui-btn-text">';
	  echo '		  <h3 class="ui-li-heading">Configure the Songs Filepath to scan this directory <a href="'._CONFIG_PATH.'config-Player.php" data-ajax="false">Configure</a></h3>';
	  echo '	      </div>';
	  echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
	  echo '  </div>';
	  echo '</li>';
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
		    echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c" style="color:red;">';
		    echo '  <div class="ui-btn-inner ui-li">';
		    echo '	      <div class="ui-btn-text">';
		    echo '		  <h3 class="ui-li-heading">'.$e->getMessage().'</h3>';
		    echo '	      </div>';
		    echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
		    echo '  </div>';
		    echo '</li>';
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
		      echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c" style="color:red;">';
		      echo '  <div class="ui-btn-inner ui-li">';
		      echo '	      <div class="ui-btn-text">';
		      echo '		  <h3 class="ui-li-heading">'.$e->getMessage().'</h3>';
		      echo '	      </div>';
		      echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
		      echo '  </div>';
		      echo '</li>';
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
	    echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c" style="color:red;">';
	    echo '  <div class="ui-btn-inner ui-li">';
	    echo '	      <div class="ui-btn-text">';
	    echo '		  <h3 class="ui-li-heading">This directory \''.$folder.'\' isn\'t readable, please change this permissions</h3>';
	    echo '	      </div>';
	    echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
	    echo '  </div>';
	    echo '</li>';
	    $noError=false;
	  }
	  closedir($currentDir);
   }else{
	  echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c" style="color:red;">';
	  echo '  <div class="ui-btn-inner ui-li">';
	  echo '	      <div class="ui-btn-text">';
	  echo '		  <h3 class="ui-li-heading" >This directory \''.$folder.'\' isn\'t exist, please create it or change it</h3>';
	  echo '	      </div>';
	  echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
	  echo '  </div>';
	  echo '</li>';
	  $noError=false;
   }
	  
}

function getLastXML()
{
   $conn = new BD();
   $result = $conn->execute("SELECT xml,hash FROM indexedsongs WHERE fecha = (Select max(fecha) from indexedsongs) AND iduser=".$_SESSION['iduser']);
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
    $result = $conn->execute("INSERT INTO indexedsongs (xml,hash,iduser) VALUES ('".$xml."','".hash("md5",$xml)."',".$_SESSION['iduser'].")");
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
      echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c">';
	  echo '  <div class="ui-btn-inner ui-li">';
	  echo '	      <div class="ui-btn-text">';
	  echo '		  <h3 class="ui-li-heading">Removed: '.$oldArray[$contOld].'</h3>';
	  echo '	      </div>';
	  echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
	  echo '  </div>';
	  echo '</li>';
      $conn = new BD();
      $conn->execute("DELETE FROM songs where hashfile='".$oldArray[$contOld]."' AND iduser=".$_SESSION['iduser']);
      $contOld++;
    }else if($result < 0){
      $result = $newXml->xpath("/songs/song[hashfile='".$newArray[$contNew]."']");
      while(list( , $node) = each($result)) {
	echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c">';
	  echo '  <div class="ui-btn-inner ui-li">';
	  echo '	      <div class="ui-btn-text">';
	  echo '		  <h3 class="ui-li-heading">Added: '.$node->title.'</h3>';
	  echo '	      </div>';
	  echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
	  echo '  </div>';
	  echo '</li>';
	$conn = new BD();
	$conn->execute("INSERT INTO songs (title,filepath,hashfile,artist,album,iduser) VALUES ('".$node->title."','".$node->filepath."','".$node->hashfile."','".$node->artist."','".$node->album."',".$_SESSION['iduser'].")");
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
	echo '<li data-theme="c" class="ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c">';
	  echo '  <div class="ui-btn-inner ui-li">';
	  echo '	      <div class="ui-btn-text">';
	  echo '		  <h3 class="ui-li-heading">Updated: '.substr($newFilePath, 12).'</h3>';
	  echo '	      </div>';
	  echo '	  <span class="ui-icon ui-icon-arrow-r ui-icon-shadow"></span>';
	  echo '  </div>';
	  echo '</li>';
	$conn = new BD();
	$conn->execute("UPDATE songs SET filepath='".$newFilePath."' WHERE hashfile = '".$newArray[$contNew]."' AND iduser=".$_SESSION['iduser']);
      }
      $contNew++;
      $contOld++;
    }
    //echo $newArray[$contNew]." - ".$oldArray[$contOld]."<br/>";
  }
  return "diff";
}
?>

	
