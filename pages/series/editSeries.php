<div id="EditSeriesDiv" class="DataForm">
	<button id="EditSeriesButton" onclick="ShowEditSeriesForm()" class="DataFormButton">Edit Series</button>
	<div id="SeriesDataForm" class="modal">
	<form class="modal-content">
		<span class="close" onclick="HideEditSeriesForm()">&times;</span>
		<div>Name:</div>
		<div><input id="editSeriesName" type="text"/></div>
		<div id="errorDiv" class="error" style="display:none">Must not be empty</div>
		<div>Author:</div>
		<div><select id="editSeriesAuthor"><option value="0" selected>N/A</option></select></div>
		<div>Description:</div>
		<div><textarea id="editSeriesDescription" cols="60"></textarea></div>
		<div><input id="editBookSubmit" type="button" value="Submit" onclick="submitSeriesEdit()"/></div>
	</form>
	</div>
</div>

<script type="text/javascript">

	var authors;

	function getAuthorList(){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					var rez = JSON.parse(this.responseText);
					var lst = document.getElementById("editSeriesAuthor");
					if(Array.isArray(rez)){
						authors = rez;
						for(author in rez){
							var opt = document.createElement("option");
							opt.value = rez[author].id;
							opt.innerHTML = rez[author].name;
							lst.appendChild(opt);
						}
					} else {
						authors = [rez];
						var opt = document.createElement("option");
						opt.value = rez.id;
						opt.innerHTML = rez.name;
						lst.appendChild(opt);
					}
				} else {
					return false;
				}
			}
		}
		xmlhttp.open("GET", "http://localhost/LiBro/api/authors", true);
		xmlhttp.send();
	}

	function ShowEditSeriesForm(){
		loadSeries();
		var l = document.getElementById("SeriesDataForm");
		l.style.display="block";
	}

	function HideEditeriesForm(){
		var l = document.getElementById("SeriesDataForm");
		l.style.display="none";
	}

	function submitSeriesEdit(){
		var seriesName = document.getElementById("editSeriesName").value;
		if(seriesName == ""){
			document.getElementById("errorDiv").style.display="block";
			return false;
		}
		var seriesDescription = document.getElementById("editSeriesDescription").value;
		var seriesAuthor = document.getElementById("editSeriesAuthor").value;
		var series = {};
		series.name = seriesName;
		series.description = seriesDescription;
		if (seriesAuthor != 0){
			series.author = seriesAuthor;
		}
		var seriesData = JSON.stringify(series);
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					location.reload();
				} else {
					alert("Series could not be edited. Make sure data is valid, or try again later.");
					return false;
				}
			}
		}
		xmlhttp.open("PUT", "http://localhost/LiBro/api/series/<?php echo $_GET['id']?>", true);
		xmlhttp.setRequestHeader("Content-type", "application/json");
		xmlhttp.setRequestHeader("Authorization", localStorage.getItem("AuthToken"));
		xmlhttp.send(seriesData);
		return false;
	}

	function loadSeries(){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					var rez = JSON.parse(this.responseText);
					document.getElementById("editSeriesName").value = rez.name;
					document.getElementById("editSeriesDescription").value = rez.description;
					document.getElementById("editSeriesAuthor").value = (rez.authorID != null) ? (rez.authorID) : (0);
					}
				} else {
					return false;
				}
		}
		xmlhttp.open("GET", "http://localhost/LiBro/api/series/<?php echo $_GET['id']?>", true);
		xmlhttp.send();
	}
	
	getAuthorList();
	window.onclick = function(event) {
		var modal = document.getElementById("SeriesDataForm");
	    if (event.target == modal) {
	        modal.style.display = "none";
	    }
	} 
	
</script>