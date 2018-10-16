<div id="EditBookDiv" class="DataForm">
	<button id="EditBookButton" onclick="ToggleEditBookView()" class="DataFormButton">Edit Book</button>
	<div id="BookDataForm" style="display:none">
	<form>
		<div>Title:</div>
		<div><input id="editBookTitle" type="text"/></div>
		<div>Author:</div>
		<div><select id="editBookAuthor"><option value="0" selected>N/A</option></select></div>
		<div>Series:</div>
		<div><select id="editBookSeries"><option value="0" selected>N/A</option></select></div>
		<div>Published on:</div>
		<div><input id="editBookPublished" type="date" required/></div>
		<div>Description:</div>
		<div><textarea id="editBookDescription" cols="60"></textarea></div>
		<div><input id="editBookSubmit" type="button" value="Submit" onclick="submitBookEdit()"/></div>
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
					var lst = document.getElementById("editBookAuthor");
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
					var lst = document.getElementById("editBookSeries");
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

	function ToggleEditBookView(){
		var l = document.getElementById("BookDataForm");
		if(l.style.display=="none"){
			loadBook();
			l.style.display="block";
		} else {
			l.style.display="none";
		}	
	}

	function submitBookEdit(){
		var bookTitle = document.getElementById("editBookTitle").value;
		var bookDescription = document.getElementById("editBookDescription").value;
		var bookPublished = document.getElementById("editBookPublished").value;
		var bookAuthor = document.getElementById("editBookAuthor").value;
		var bookSeries = document.getElementById("editBookSeries").value;
		var book = {};
		book.title = bookTitle;
		book.description = bookDescription;
		book.published = bookPublished;
		if (bookAuthor != 0){
			book.authorID = bookAuthor;
		}
		if (bookSeries != 0){
			book.seriesID = bookSeries;
		}
		var bookData = JSON.stringify(book);
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					location.reload();
				} else {
					alert("Book could not be edited. Make sure data is valid, or try again later.");
					return false;
				}
			}
		}
		xmlhttp.open("PUT", "http://localhost/LiBro/api/books/<?php echo $_GET['id']?>", true);
		xmlhttp.setRequestHeader("Content-type", "application/json");
		xmlhttp.setRequestHeader("Authorization", localStorage.getItem("AuthToken"));
		xmlhttp.send(bookData);
		return false;
	}

	function loadBook(){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					var rez = JSON.parse(this.responseText);
					document.getElementById("editBookTitle").value = rez.title;
					document.getElementById("editBookDescription").value = rez.description;
					document.getElementById("editBookPublished").value = rez.published;
					document.getElementById("editBookAuthor").value = (rez.authorID != null) ? (rez.authorID) : (0);
					document.getElementById("editBookSeries").value = (rez.seriesID != null) ? (rez.seriesID) : (0);
					}
				} else {
					return false;
				}
		}
		xmlhttp.open("GET", "http://localhost/LiBro/api/books/<?php echo $_GET['id']?>", true);
		xmlhttp.send();
	}
	
	getAuthorList();
	getSeriesList();
	
</script>