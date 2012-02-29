function results(data) {
 var listView = document.getElementById("songlist");
	
 while(listView.hasChildNodes())
	listView.removeChild(listView.firstChild);	

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
			heading.appendChild(document.createTextNode(data[index].album+" - "+data[index].title));
		btnText.appendChild(heading);
			var listens = document.createElement("h3");
			listens.setAttribute("id",data[index].hashfile);
			listens.setAttribute("class","ui-li-listens");
			listens.appendChild(document.createTextNode(data[index].listens));
		btnText.appendChild(listens);
			var description = document.createElement("p");
		       	description.setAttribute("class","ui-li-desc");
				/*var audio = document.createElement("audio");
			       	audio.setAttribute("controls","controls");
				audio.setAttribute("src",data[index].filepath);
				audio.setAttribute("type","audio/mp3");
			description.appendChild(audio);*/
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

function sendRequest(currentKey){
  $.ajax({
    data: "action=gettingSongs&key="+currentKey,
    type: "GET",
    dataType: "json",
    url: "finder.php",
    success: function(data){
       results(data);
     }
   });
}

function sendRequestP(e,currentKey){
var code;
if(!e)
	code = 13
else
	code = (e.keyCode ? e.keyCode : e.which);

 if(code == 13) { //Enter keycode
	  $.ajax({
	    data: "title="+document.getElementById("inTitle").value+"&key="+currentKey,
	    type: "GET",
	    dataType: "json",
	    url: "finder.php",
	    success: function(data){
	       results(data);
	     }
	   });
	}
}
/*$("#inTitle").bind('keypress', function(e) {
 var code = (e.keyCode ? e.keyCode : e.which);
 if(code == 13) { //Enter keycode
	  $.ajax({
	    data: "title="+document.getElementById("inTitle").value,
	    type: "GET",
	    dataType: "json",
	    url: "finder.php",
	    success: function(data){
	       results(data);
	     }
	   });
	}
});
});*/
function loadSongs(list,currentKey){
  $.ajax({
	    data: "action=gettingSongs&list="+list+"&key="+currentKey,
	    type: "GET",
	    dataType: "json",
	    url: "finder.php",
	    success: function(data){
	       results(data);
	     }
	   });
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
  var player = document.getElementById("mep_0");
  player.setAttribute("width",$(window).width());
}