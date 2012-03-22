var extend=false;
var position=0;
var lastFilter="";
var firstRun=true;

function searchBox_clicked(){
  var searchBox = document.getElementById("searchBox").value;
  //instead of searching through all fields, you should look for the category for which you are browsing
  var filter = "&title="+searchBox+"&album="+searchBox+"&artist="+searchBox+"&from=home";
  extend=false;
  position=0;
  loadSongs(filter);
}

function searchBox_pressed(e,currentKey){
var code;
if(!e) code = 13; else code = (e.keyCode ? e.keyCode : e.which);

 if(code == 13) { //Enter keycode
	  searchBox_clicked();
	}
}

function loadSongList(filter){
  extend=false;
  position=0;
  loadSongs(filter);
}

function loadAlbumList(filter){
  extend=false;
  position=0;
  loadAlbums(filter);
}

function loadArtistList(filter){
  extend=false;
  position=0;
  loadArtists(filter);
}

function loadPlayList(filter){
  extend=false;
  position=0;
  loadPlay(filter);
}

function loadSongs(filter){
  firstRun=false;
  lastFilter=filter;
  $.ajax({
	    data: "action=gettingSongs&key="+currentKey+"&iduser="+idUser+"&pos="+position+filter,
	    type: "GET",
	    dataType: "json",
	    url: "finder.php",
	    success: function(data){
	       processSongData(data);
	     }
	   });
  
}

function loadAlbums(filter){
  firstRun=false;
  lastFilter=filter;
  $.ajax({
	    data: "action=gettingAlbums&key="+currentKey+"&iduser="+idUser+"&pos="+position+filter,
	    type: "GET",
	    dataType: "json",
	    url: "finder.php",
	    success: function(data){
	       processAlbumData(data);
	     }
	   });
  
}

function loadArtists(filter){
  firstRun=false;
  lastFilter=filter;
  $.ajax({
	    data: "action=gettingArtists&key="+currentKey+"&iduser="+idUser+"&pos="+position+filter,
	    type: "GET",
	    dataType: "json",
	    url: "finder.php",
	    success: function(data){
	       processArtistData(data);
	     }
	   });
  
}

function loadPlay(filter){
  firstRun=false;
  lastFilter=filter;
  $.ajax({
	    data: "action=gettingAlbums&key="+currentKey+"&iduser="+idUser+"&pos="+position+filter,
	    type: "GET",
	    dataType: "json",
	    url: "finder.php",
	    success: function(data){
	       processAlbumData(data);
	     }
	   });
  
}

function extendSongsList(){
  extend=true;
  position++;
  loadSongs(lastFilter);
}

function extendAlbumsList(){
  extend=true;
  position++;
  loadAlbums(lastFilter);
}

function extendArtistList(){
  extend=true;
  position++;
  loadArtists(lastFilter);
}
//PLAY
function playSong(song){
  var filePath = song.getAttribute("data-filepath");
  var idSong = song.getAttribute("data-hashfile");
  sendListened(idSong);
  var player = new MediaElementPlayer('#player');
  player.pause();
  player.setSrc(filePath);
  player.play();
}

//LISTENS FUNCTIONS
function sendListened(idSong){
  //CORREGIR PL/P
  $.ajax({
	    data: "action=incrementListen&key="+currentKey+"&idSong="+idSong,
	    type: "GET",
	    dataType: "json",
	    url: "finder.php",
	    success: function(data){
	       resultListen(data);
	     }
	   });
}

function resultListen(data){
  var counter = document.getElementById(data[0].hashfile);
  
   while(counter.hasChildNodes())
	counter.removeChild(counter.firstChild);
   
  counter.appendChild(document.createTextNode(data[0].listens));
}

//PROCESS DATABASE FUNCTIONS
function processArtistData(data) {
 var listView = document.getElementById("listView");
 if(!extend) while(listView.hasChildNodes()) listView.removeChild(listView.firstChild);
 else{
  var lastExtendButton = listView.lastChild.lastChild;
  if(lastExtendButton){
    lastExtendButton = listView.lastChild.lastChild.nodeValue;
    if(lastExtendButton=="More"){
      listView.removeChild(listView.lastChild);
    }
  }
 }
     $.each(data,function(index,value) {
	var listView = document.getElementById("listView");
	if(data[index].first){
	       var artistResult = document.createElement("li");
	       artistResult.setAttribute("data-theme","c");
	       artistResult.setAttribute("class","ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c");
	       
	       artistResult.setAttribute("href","index.php?by=album&idartist="+data[index].idartist);
	       
	       var innerResult = document.createElement("div");
	       innerResult.setAttribute("class","ui-btn-inner ui-li");

		var btnText = document.createElement("div");
	       	btnText.setAttribute("class","ui-btn-text");

			var a = document.createElement("a");
			a.setAttribute("class","links");
			a.setAttribute("href","index.php?by=album&idartist="+data[index].idartist);
			a.setAttribute("data-ajax","false");

			var heading = document.createElement("h3");
		       	heading.setAttribute("class","ui-li-heading");
			heading.appendChild(document.createTextNode(data[index].name));
		a.appendChild(heading);
		btnText.appendChild(a);
		innerResult.appendChild(btnText);
			var span = document.createElement("span");
			span.setAttribute("class","ui-icon ui-icon-arrow-r ui-icon-shadow");
		innerResult.appendChild(span);
		artistResult.setAttribute("debug",data[index].where);
		artistResult.appendChild(innerResult);

	       listView.appendChild(artistResult);
	}else{
		var songResult = document.createElement("li");
	       	songResult.setAttribute("data-theme","c");
	       	songResult.setAttribute("class","ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c listButton");
		if(extend){
		  var lastLine = listView.lastChild.lastChild;
		  if(lastLine){
		    lastLine = listView.lastChild.lastChild.nodeValue;
		    if(lastLine!="No more results")
		      songResult.appendChild(document.createTextNode("No more results"));
		      listView.appendChild(songResult);
		  }
		}
		else{
		  songResult.appendChild(document.createTextNode("No results :S"));
		  listView.appendChild(songResult);
		}
		
	}
     });
     var lastLine = listView.lastChild.lastChild;
     if(lastLine){
      lastLine = listView.lastChild.lastChild.nodeValue;
      if(lastLine!="No more results" && lastLine!="No results :S"){
	var songResult = document.createElement("li");
	songResult.setAttribute("data-theme","c");
	songResult.setAttribute("class","ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c listButton");
	songResult.setAttribute("onclick","extendArtistList()");
	songResult.appendChild(document.createTextNode("More"));
	listView.appendChild(songResult);
      }
     }
 }



function processAlbumData(data) {
 var listView = document.getElementById("listView");
 if(!extend) while(listView.hasChildNodes()) listView.removeChild(listView.firstChild);
 else{
  var lastExtendButton = listView.lastChild.lastChild;
  if(lastExtendButton){
    lastExtendButton = listView.lastChild.lastChild.nodeValue;
    if(lastExtendButton=="More"){
      listView.removeChild(listView.lastChild);
    }
  }
 }
     $.each(data,function(index,value) {
	var listView = document.getElementById("listView");
	if(data[index].first){
	       var artistResult = document.createElement("li");
	       artistResult.setAttribute("data-theme","c");
	       artistResult.setAttribute("class","ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c");
	       
	       artistResult.setAttribute("href","index.php?by=song&idalbum="+data[index].idalbum);
	       
	       var innerResult = document.createElement("div");
	       innerResult.setAttribute("class","ui-btn-inner ui-li");

		var btnText = document.createElement("div");
	       	btnText.setAttribute("class","ui-btn-text");

			var a = document.createElement("a");
			a.setAttribute("class","links");
			a.setAttribute("href","index.php?by=song&idalbum="+data[index].idalbum);
			a.setAttribute("data-ajax","false");

			var heading = document.createElement("h3");
			heading.setAttribute("class","ui-li-heading");
			heading.appendChild(document.createTextNode(data[index].title));
		a.appendChild(heading);
		btnText.appendChild(a);
		innerResult.appendChild(btnText);
			var span = document.createElement("span");
			span.setAttribute("class","ui-icon ui-icon-arrow-r ui-icon-shadow");
		innerResult.appendChild(span);
		artistResult.appendChild(innerResult);
		artistResult.setAttribute("debug",data[index].where);

	       listView.appendChild(artistResult);
	}else{
		var songResult = document.createElement("li");
	       	songResult.setAttribute("data-theme","c");
	       	songResult.setAttribute("class","ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c");
		if(extend){
		  var lastLine = listView.lastChild.lastChild;
		  if(lastLine){
		    lastLine = listView.lastChild.lastChild.nodeValue;
		    if(lastLine!="No more results")
		      songResult.appendChild(document.createTextNode("No more results"));
		      listView.appendChild(songResult);
		  }
		}
		else{
		  songResult.appendChild(document.createTextNode("No results :S"));
		  listView.appendChild(songResult);
		}
		
	}
     });
     var lastLine = listView.lastChild.lastChild;
     if(lastLine){
      lastLine = listView.lastChild.lastChild.nodeValue;
      if(lastLine!="No more results" && lastLine!="No results :S"){
	var songResult = document.createElement("li");
	songResult.setAttribute("data-theme","c");
	songResult.setAttribute("class","ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c");
	songResult.setAttribute("onclick","extendAlbumsList()");
	songResult.appendChild(document.createTextNode("More"));
	listView.appendChild(songResult);
      }
     }
 }

function processSongData(data) {
 var listView = document.getElementById("listView");
 
 if(!extend){
   while(listView.hasChildNodes()){ listView.removeChild(listView.firstChild); }
 }
 else{
  
  var lastExtendButton = listView.lastChild.lastChild;
  if(lastExtendButton){
    lastExtendButton = listView.lastChild.lastChild.nodeValue;
    if(lastExtendButton=="More"){
      listView.removeChild(listView.lastChild);
    }
  }
 }
     $.each(data,function(index,value) {
	var listView = document.getElementById("listView");
	
	if(data[index].first){
	       var songResult = document.createElement("li");
	       songResult.setAttribute("data-theme","c");
	       songResult.setAttribute("class","ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c");
	       songResult.setAttribute("data-filepath",data[index].filepath);
	       songResult.setAttribute("data-hashfile",data[index].hashfile);
	       songResult.setAttribute("where",data[index].where);
	       songResult.setAttribute("onClick","playSong(this)");
	       
	       var innerSong = document.createElement("div");
	       innerSong.setAttribute("class","ui-btn-inner ui-li");

		var btnText = document.createElement("div");
	       	btnText.setAttribute("class","ui-btn-text");

			//<!--a href="#" class="ui-link-inherit" data-transition="slide">-->
			//<!--<img src="images/intro_menaje.jpg" class="ui-li-thumb">-->
			var heading = document.createElement("h3");
		       	heading.setAttribute("class","ui-li-heading");
			heading.appendChild(document.createTextNode(data[index].title));
		btnText.appendChild(heading);
			var description = document.createElement("p");
		       	description.setAttribute("class","ui-li-desc");
			  var caseImg = document.createElement("img");
				caseImg.setAttribute("class","caseImg");
				caseImg.setAttribute("src","img/caratula64x64.png");
			description.appendChild(caseImg);
			  var albumBox  = document.createElement("div");
			  albumBox.setAttribute("class","albumBox");
				
				var album = document.createElement("div");
				album.setAttribute("class","calaix");
				album.setAttribute("id","album");
				album.appendChild(document.createTextNode("from "));
				var a  = document.createElement("a");
				a.setAttribute("class","musicreferences");
				a.setAttribute("data-ajax","false");
				a.setAttribute("href","index.php?by=song&idalbum="+data[index].idalbum);
				var b = document.createElement("b");
				b.appendChild(document.createTextNode(data[index].album));
				a.appendChild(b);
			
				album.appendChild(a);
			albumBox.appendChild(album);
				var artist = document.createElement("div");
				artist.setAttribute("class","calaix");
				artist.setAttribute("id","artist");
				artist.appendChild(document.createTextNode("by "));
				var a1  = document.createElement("a");
				a1.setAttribute("class","musicreferences");
				a1.setAttribute("data-ajax","false");
				a1.setAttribute("href","index.php?by=album&idartist="+data[index].idartist);
				var b2 = document.createElement("b");
				b2.appendChild(document.createTextNode(data[index].artist));
				a1.appendChild(b2);
				artist.appendChild(a1);
			albumBox.appendChild(artist);
				var played = document.createElement("div");
				played.setAttribute("class","calaix");
				  var listens = document.createElement("div");
				  listens.setAttribute("id",data[index].hashfile);
				  listens.setAttribute("class","albumBox-listens");
				  listens.appendChild(document.createTextNode(data[index].listens));
				played.appendChild(document.createTextNode("Plays: "));
				played.appendChild(listens);
				/*var audio = document.createElement("audio");
			       	audio.setAttribute("controls","controls");
				audio.setAttribute("src",data[index].filepath);
				audio.setAttribute("type","audio/mp3");
			description.appendChild(audio);*/
			albumBox.appendChild(played);
			description.appendChild(albumBox);
		btnText.appendChild(description);
		innerSong.appendChild(btnText);
			var span = document.createElement("span");
			span.setAttribute("class","ui-icon ui-icon-arrow-r ui-icon-shadow");
		innerSong.appendChild(span);
		songResult.appendChild(innerSong);

	       listView.appendChild(songResult);
	}else{
		var songResult = document.createElement("li");
	       	songResult.setAttribute("data-theme","c");
	       	songResult.setAttribute("class","ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c listButton");
		if(extend){
		  var lastLine = listView.lastChild.lastChild;
		  if(lastLine){
		    lastLine = listView.lastChild.lastChild.nodeValue;
		    if(lastLine!="No more results"){
		      songResult.appendChild(document.createTextNode("No more results"));
		      listView.appendChild(songResult);
			}
		  }
		}
		else{
		  songResult.appendChild(document.createTextNode("No results :S"));
		  listView.appendChild(songResult);
		}
		
	}
     });

     var lastLine = listView.lastChild.lastChild;
     if(lastLine){
      lastLine = listView.lastChild.lastChild.nodeValue;
      if(lastLine!="No more results" && lastLine!="No results :S"){
	var songResult = document.createElement("li");
	songResult.setAttribute("data-theme","c");
	songResult.setAttribute("class","ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c listButton");
	songResult.setAttribute("onclick","extendSongsList()");
	songResult.appendChild(document.createTextNode("More"));
	listView.appendChild(songResult);
      }
     }
 }
