<div id="CreateSeriesDiv" class="DataForm">
	<button id="NewSeriesButton" onclick="ShowNewSeriesForm()" class="DataFormButton">Create New Series</button>
	<div id="SeriesDataForm" class="modal">
	<form class="modal-content">
		<span class="close" onclick="HideNewSeriesForm()">&times;</span>
		<div>Name:</div>
		<div><input id="newSeriesName" type="text"/></div>
		<div>Author:</div>
		<div><select id="newSeriesAuthor"><option value="0" selected>N/A</option></select></div>
		<div>Description:</div>
		<div><textarea id="newSeriesDescription" cols="60"></textarea></div>		
		<div><input id="newSeriesSubmit" type="button" value="Submit" onclick="submitSeries()"/></div>
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
					var lst = document.getElementById("newSeriesAuthor");
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

	function ShowNewSeriesForm(){
		var l = document.getElementById("SeriesDataForm");
		l.style.display="block";
	}

	function HideNewSeriesForm(){
		var l = document.getElementById("SeriesDataForm");
		l.style.display="none";
	}

	function submitSeries(){
		var name = document.getElementById("newSeriesName").value;
		var description = document.getElementById("newSeriesDescription").value;
		var author = document.getElementById("newSeriesAuthor").value;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 201){
					window.location.href = "http://localhost/LiBro"+this.getResponseHeader("location").slice(4);
				} else {
					alert("Series could not be submitted. Make sure data is valid, or try again later.");
					return false;
				}
			}
		}
		xmlhttp.open("POST", "http://localhost/LiBro/api/series/", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.setRequestHeader("Authorization", localStorage.getItem("AuthToken"));
		xmlhttp.send("name=" + name + "&description=" + description + ((author == 0) ? "" : ("&author="+author)));
		return false;
	}

	getAuthorList();
	window.onclick = function(event) {
		var modal = document.getElementById("SeriesDataForm");
	    if (event.target == modal) {
	        modal.style.display = "none";
	    }
	} 
	
</script>