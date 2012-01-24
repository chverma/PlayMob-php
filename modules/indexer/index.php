<?php
  $GLOBALS['songspath']  = "../../files";
  $songs = array();
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
   <HEAD>
      <title>PlayMob: Play Mobility -> Indexer Files</title>
      <link rel="stylesheet" href="../../style/default.css" type="text/css">
   </HEAD>
   <BODY>
      <?php indexer_init(); ?>
   </BODY>
</HTML>

<?php
function indexer_init()
{
    global $songs;
    if(indexer_requeriments()){
      indexer_indexingFolder($GLOBALS['songspath']);
      include('XMLSerializer.inc');
      //indexer_getXMLFromObjectArray($songs);
      echo "<h1 class='successful'>The indexer process was finished successfully!</h1>";
    }
}

function indexer_requeriments()
{
  if (!extension_loaded("ktaglib")){
    echo "<h1 class='error'>The library 'ktaglib' isn't loaded</h1>";
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
      return $this->length;
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
		  }
		  $audioProperties = $mpeg->getAudioProperties();
		  $ID3v1Tag = $mpeg->getID3v1Tag();
		  $title = $ID3v1Tag->getTitle();
		  $artist = $ID3v1Tag->getArtist();
		  $album = $ID3v1Tag->getAlbum();
		  $length = $audioProperties->getLength();
		  if($title!=""){
 		    try{
		      $song = new song($title,$artist,$album,$length,$filePath,hash_file('md5', $filePath));
		      array_push($songs,$song);
		    }catch(Exception $e){
		      echo "<h1 class='error'>Error-> ".$e->getMessage()."</h1>";
		    }
		  }
		}
	      }else{
		if($file!="." && $file!=".."){
		  indexer_indexingFolder($filePath);
		}
	      }
	    }
	  }else{
	    echo "<h1 class='error'>Error-> This directory '".$folder."' isn't readable, please change this permissions</h1>";
	  }
	  closedir($currentDir);
   }else{
     echo "<h1 class='error'>Error-> This directory '".$folder."' isn't exist, please create it or change it</h1>";
   }

}
?>

	