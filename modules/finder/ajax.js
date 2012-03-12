var extend=false;
var position=0;
var lastFilter="";
var firstRun=true;
function processSongData(data) {
 var listView = document.getElementById("songlist");
 if(!extend) while(listView.hasChildNodes()) listView.removeChild(listView.firstChild);	

     $.each(data,function(index,value) {
	var listView = document.getElementById("songlist");
	if(data[index].first){
	       var songResult = document.createElement("li");
	       songResult.setAttribute("data-theme","c");
	       songResult.setAttribute("class","ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c");
	       songResult.setAttribute("data-filepath",data[index].filepath);
	       songResult.setAttribute("data-hashfile",data[index].hashfile);
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
				var b = document.createElement("b");
				b.appendChild(document.createTextNode(data[index].album));
			
				album.appendChild(b);
			albumBox.appendChild(album);
				var artist = document.createElement("div");
				artist.setAttribute("class","calaix");
				artist.setAttribute("id","artist");
				artist.appendChild(document.createTextNode("by "));
				var b2 = document.createElement("b");
				b2.appendChild(document.createTextNode(data[index].artist));
				artist.appendChild(b2);
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
	       	songResult.setAttribute("class","ui-btn ui-btn-icon-right ui-li ui-li-has-thumb ui-btn-down-a ui-btn-up-c");
		songResult.appendChild(document.createTextNode("No results :S"));
		listView.appendChild(songResult);
	}
     });
 }

function searchBox_pressed(e,currentKey){
var code;
if(!e) code = 13; else code = (e.keyCode ? e.keyCode : e.which);

 if(code == 13) { //Enter keycode
	  searchBox_clicked();
	}
}

function searchBox_clicked(){
  var searchBox = document.getElementById("searchBox").value;
  var filter = "&title="+searchBox+"&album="+searchBox+"&artist="+searchBox+"&from=home";
  extend=false;
  position=0;
  loadSongs(filter);
}
function loadSongList(list){
  var filter = "&list="+list;
  extend=false;
  position=0;
  loadSongs(filter);
}
function loadSongs(filter){
  firstRun=false;
  lastFilter=filter;
  $.ajax({
	    data: "action=gettingSongs&key="+currentKey+"&pos="+position+filter,
	    type: "GET",
	    dataType: "json",
	    url: "finder.php",
	    success: function(data){
	       processSongData(data);
	     }
	   });
  
}

function extendSongsList(){
  extend=true;
  position++;
  loadSongs(lastFilter);
}
function playSong(song){
  var filePath = song.getAttribute("data-filepath");
  var idSong = song.getAttribute("data-hashfile");
  sendListened(idSong);
  var player = new MediaElementPlayer('#player');
  player.pause();
  player.setSrc(filePath);
  player.play();
}

function sendListened(idSong){
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
function resizeDocument(){
  /*var width = $(window).width();
  var footer = document.getElementById("mep_0");
  //var controlPlayer = document.getElementsByClassName("mejs-controls")[0];
  //var inner = document.getElementsByClassName("mejs-inner")[0];
  //var mediaelement = document.getElementsByClassName("mejs-mediaelement")[0];
  //mediaelement.setAttribute("width",width);
//   controlPlayer.setAttribute("width",width+"px");
  //inner.setAttribute("width",width+"px");
  footer.setAttribute("width",width+"px");*/
}