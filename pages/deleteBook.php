<button id="bookDelete" class="deleteButton" onclick="deleteBook()">Delete book</button>

<script type="text/javascript">
	function deleteBook(){
		if(!confirm("Are you sure you want to delete? This cannot be undone!")) return false;
		var id = <?php echo filter_var($_GET["id"], FILTER_VALIDATE_INT);?>;
		if(id){
    		var xmlhttp = new XMLHttpRequest();
    		xmlhttp.onreadystatechange = function(){
    			if(this.readyState == 4){
    				if(this.status == 200){
    					location.href = "http://localhost/LiBro/books/";
    				} else {
        				console.log
    					alert("Book could not be deleted. Make sure data is valid, or try again later.");
    					return false;
    				}
    			}
    		}
    		xmlhttp.open("DELETE", "http://localhost/LiBro/api/books/" + id, true);
    		xmlhttp.setRequestHeader("Authorization", localStorage.getItem("AuthToken"));
    		xmlhttp.send();
    		return false;
		} else {
			alert("Invalid deletion ID.");
		}
	}
</script>