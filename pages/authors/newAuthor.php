<div id="CreateAuthorDiv" class="DataForm">
	<button id="NewAuthorButton" onclick="ShowNewAuthorForm()" class="DataFormButton">Create New Author</button>
	<div id="AuthorDataForm" class="modal">
	<form class="modal-content">
		<span class="close" onclick="HideNewAuthorForm()">&times;</span>
		<div>Name:</div>
		<div><input id="newAuthorName" type="text"/></div>
		<div>Biography:</div>
		<div><textarea id="newAuthorBio" cols="60"></textarea></div>		
		<div><input id="newAuthorSubmit" type="button" value="Submit" onclick="submitAuthor()"/></div>
	</form>
	</div>
</div>

<script type="text/javascript">

	function ShowNewAuthorForm(){
		var l = document.getElementById("AuthorDataForm");
		l.style.display="block";
	}

	function HideNewAuthorForm(){
		var l = document.getElementById("AuthorDataForm");
		l.style.display="none";
	}

	function submitAuthor(){
		var name = document.getElementById("newAuthorName").value;
		var bio = document.getElementById("newAuthorBio").value;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 201){
					window.location.href = "http://localhost/LiBro"+this.getResponseHeader("location").slice(4);
				} else {
					console.log(this);
					alert("Author could not be submitted. Make sure data is valid, or try again later.");
					return false;
				}
			}
		}
		xmlhttp.open("POST", "http://localhost/LiBro/api/authors", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.setRequestHeader("Authorization", localStorage.getItem("AuthToken"));
		xmlhttp.send("name=" + name + ((bio != "") ? ("&bio=" + bio) : ""));
		return false;
	}

	window.onclick = function(event) {
		var modal = document.getElementById("AuthorDataForm");
	    if (event.target == modal) {
	        modal.style.display = "none";
	    }
	} 
	
</script>