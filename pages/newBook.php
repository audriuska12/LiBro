<div id="CreateBookDiv">
	<button id="NewBookButton" onclick="ToggleNewBookView()">Create New Book</button>
	<div id="BookDataForm" style="display:none">
	<form>
		<div>Title:</div>
		<div><input id="newBookTitle" type="text"/></div>
		<div>Description:</div>
		<div><input id="newBookDescription" type="text"/></div>
		<div>Published on:</div>
		<div><input id="newBookPublished" type="date"/></div>
		<div>Author:</div>
		<div><select id="newBookAuthor"><option value="0" selected>N/A</option></select></div>
		<div>Series:</div>
		<div><select id="newBookSeries"><option value="0" selected>N/A</option></select></div>
		<div><input id="newBookSubmit" type="button" value="Submit" onclick="submitBook()"/></div>
	</form>
	</div>
</div>

<script type="text/javascript">

	var authors;
	var series;

	function getAuthorList(){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					var rez = JSON.parse(this.responseText);
					var lst = document.getElementById("newBookAuthor");
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

	function getSeriesList(){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					var rez = JSON.parse(this.responseText);
					var lst = document.getElementById("newBookSeries");
					if(Array.isArray(rez)){
						series = rez;
						for(ser in rez){
							var opt = document.createElement("option");
							opt.value = rez[ser].id;
							opt.innerHTML = rez[ser].name;
							lst.appendChild(opt);
						}
					} else {
						series = [rez];
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
		xmlhttp.open("GET", "http://localhost/LiBro/api/series", true);
		xmlhttp.send();
	}

	function ToggleNewBookView(){
		var l = document.getElementById("BookDataForm");
		if(l.style.display=="none"){
			l.style.display="block";
		} else {
			l.style.display="none";
		}	
	}

	function submitBook(){
		var title = document.getElementById("newBookTitle").value;
		var description = document.getElementById("newBookDescription").value;
		var published = document.getElementById("newBookPublished").value;
		var author = document.getElementById("newBookAuthor").value;
		var series = document.getElementById("newBookSeries").value;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 201){
					window.location.href = "http://localhost/LiBro"+this.getResponseHeader("location").slice(4);
				} else {
					alert("Book could not be submitted. Make sure data is valid, or try again later.");
					return false;
				}
			}
		}
		xmlhttp.open("POST", "http://localhost/LiBro/api/books.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.setRequestHeader("Authorization", localStorage.getItem("AuthToken"));
		xmlhttp.send("title="+title+"&description="+description+"&published="+published + ((author == 0) ? "" : ("&author="+author)) + ((series == 0) ? "" : ("&series="+series)));
		return false;
	}

	getAuthorList();
	getSeriesList();
	
</script>