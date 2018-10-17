<div id="EditAuthorDiv" class="DataForm">
	<button id="EditAuthorButton" onclick="ToggleEditAuthorView()" class="DataFormButton">Edit Author</button>
	<div id="AuthorDataForm" style="display:none">
	<form>
		<div>Name:</div>
		<div><input id="editAuthorName" type="text"/></div>
		<div>Biography:</div>
		<div><textarea id="editAuthorBio" cols="60"></textarea></div>		
		<div><input id="editAuthorSubmit" type="button" value="Submit" onclick="submitAuthorEdit()"/></div>
	</form>
	</div>
</div>

<script type="text/javascript">

	function ToggleEditAuthorView(){
		var l = document.getElementById("AuthorDataForm");
		if(l.style.display=="none"){
			loadAuthor();
			l.style.display="block";
		} else {
			l.style.display="none";
		}	
	}

	function submitAuthorEdit(){
		var name = document.getElementById("editAuthorName").value;
		var bio = document.getElementById("editAuthorBio").value;
		var author = {};
		author.name = name;
		author.bio = bio;
		var authorData = JSON.stringify(author);
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					location.reload();
				} else {
					alert("Author could not be edited. Make sure data is valid, or try again later.");
					return false;
				}
			}
		}
		xmlhttp.open("PUT", "http://localhost/LiBro/api/authors/<?php echo $_GET['id']?>", true);
		xmlhttp.setRequestHeader("Content-type", "application/json");
		xmlhttp.setRequestHeader("Authorization", localStorage.getItem("AuthToken"));
		xmlhttp.send(authorData);
		return false;
	}

	function loadAuthor(){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					var rez = JSON.parse(this.responseText);
					document.getElementById("editAuthorName").value = rez.name;
					document.getElementById("editAuthorBio").value = rez.bio
					}
				} else {
					return false;
				}
		}
		xmlhttp.open("GET", "http://localhost/LiBro/api/authors/<?php echo $_GET['id']?>", true);
		xmlhttp.send();
	}
	
</script>