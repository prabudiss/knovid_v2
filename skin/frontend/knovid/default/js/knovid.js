var selObj = null; 

function toggleChapter(obj){
	//Set the default object incase of null
	if (obj == null) {
		obj = document.getElementById("navChapter").getElementsByClassName('chapter-unlocked')[0];
	}

	//unselect the previous object
	if (selObj) {
		selObj.className = "chapter-unlocked";
	}

	//set the current object incase of 
	if (obj.className != "chapter-locked") { 
		selObj = obj;
		selObj.className = "chapter-play";
	}
}

function loadVideo(pid, index, obj){

	//Toggle Chatper titles
	toggleChapter(obj);			

	var prod_id = jQuery(this).attr("prod_id");
	var obj = jQuery(this);
	var data = "prod_id=" + pid;
	var base_url = document.getElementById('prod_baseurl').value;
	data += "&index=" + index;
	data += "&type=1"  
		jQuery.ajax({
		  url: base_url+'courses/index/GetVideo',
		  dataType: 'html',
		  type : 'post',
		  data: data,
		  statusCode: {
		  		200: function(response) {
		  			document.getElementById('divVideoContent').innerHTML = response;
		  			window.location.href = "#Learning";
		  		},
		  		404: function() {
		  		alert("Something went wrong!!!");
		  	}
		  }
		});		
}