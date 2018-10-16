<button id="authorDelete" class="deleteButton" onclick="deleteAuthor()">Delete Author</button>

<script type="text/javascript">
	function deleteAuthor(){
		if(!confirm("Are you sure you want to delete? This cannot be undone!")) return false;
		var id = <?php echo filter_var($_GET["id"], FILTER_VALIDATE_INT);?>;
		if(id){
    		var xmlhttp = new XMLHttpRequest();
    		xmlhttp.onreadystatechange = function(){
    			if(this.readyState == 4){
    				if(this.status == 200){
    					location.href = "http://localhost/LiBro/authors/";
    				} else {
    					alert("Author could not be deleted.");
    					return false;
    				}
    			}
    		}
    		xmlhttp.open("DELETE", "http://localhost/LiBro/api/authors/" + id, true);
    		xmlhttp.setRequestHeader("Authorization", localStorage.getItem("AuthToken"));
    		xmlhttp.send();
    		return false;
		} else {
			alert("Invalid deletion ID.");
		}
	}
</script>