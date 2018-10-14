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
		<div><input id="NewBookSubmit" type="button" value="Submit" onclick="submitBook()"/></div>
	</form>
	</div>
</div>

<script type="text/javascript">

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
		alert(title+description+published);
		return false;
	}
</script>