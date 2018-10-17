<div id="CreateAuthorDiv" class="DataForm">
	<button id="NewAuthorButton" onclick="ToggleNewAuthorView()" class="DataFormButton">Create New Author</button>
	<div id="AuthorDataForm" style="display:none">
	<form>
		<div>Name:</div>
		<div><input id="newAuthorName" type="text"/></div>
		<div>Biography:</div>
		<div><textarea id="newAuthorBio" cols="60"></textarea></div>		
		<div><input id="newAuthorSubmit" type="button" value="Submit" onclick="submitAuthor()"/></div>
	</form>
	</div>
</div>

<script type="text/javascript">

	function ToggleNewAuthorView(){
		var l = document.getElementById("AuthorDataForm");
		if(l.style.display=="none"){
			l.style.display="block";
		} else {
			l.style.display="none";
		}	
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
	
</script>