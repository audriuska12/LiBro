<?php session_start()?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="http://localhost/LiBro/styles.css">
<title>Books</title>
</head>
<body>
    <div id="container">
        <?php include "pages/header.php";?>
        <div id="content">
        <?php if(isset($_SESSION["token"])){
            include "pages/books/bookAdminOptions.php";
        }?>
        	<div id="bookList">
        		<div id="bookLoadSpinner" class="loader"></div>
        	</div>
		</div>
        <?php include "pages/footer.php";?>
    </div>
    <script type="text/javascript">

	var bookList;

	function getBookNode(book){
		var container = document.createElement("div");
		container.className = "SingleEntryContainer";
		var imgDiv = document.createElement("img");
		imgDiv.className = "SingleEntryImage";
		imgDiv.src = "http://localhost/LiBro/images/cover-placeholder.gif";
		container.appendChild(imgDiv);
		var bookDiv = document.createElement("div");
		bookDiv.className = "SingleEntryData";
		var titleDiv = document.createTextNode(book.title);
		bookDiv.appendChild(titleDiv);
		var breakDiv = document.createElement("br");
		bookDiv.appendChild(breakDiv);
		if(book.authorName != null && book.authorName != "" && book.authorID != null){
			var authorDiv = document.createElement("a");
			authorDiv.text = "Author: " + book.authorName;
			authorDiv.href = "http://localhost/LiBro/authors/" + book.authorID;
			bookDiv.appendChild(authorDiv);
			breakDiv = document.createElement("br");
			bookDiv.appendChild(breakDiv);
		}
		if(book.seriesName != null && book.seriesName != "" && book.seriesID != null){
			var seriesDiv = document.createElement("a");
			seriesDiv.text = "Series: " + ((book.seriesName != null) ? book.seriesName : "N\\A");
			if(book.seriesID != null) seriesDiv.href = "http://localhost/LiBro/series/" + book.seriesID;
			bookDiv.appendChild(seriesDiv);
			breakDiv = document.createElement("br");
			bookDiv.appendChild(breakDiv);
		}
		var publishedDiv = document.createTextNode("Published: " + book.published);
		bookDiv.appendChild(publishedDiv);
		breakDiv = document.createElement("br");
		bookDiv.appendChild(breakDiv);
		if(book.description != null && book.description != ""){
			var descDiv = document.createTextNode(book.description);
			bookDiv.appendChild(descDiv);
		}
		container.appendChild(bookDiv);
		return container;
	}

	function getLongBookEntryNode(book, reznum){
		var bookDiv = document.createElement("p");
		bookDiv.id = "bookInfo" + reznum;
		var titleDiv = document.createElement("a");
		titleDiv.href = "http://localhost/LiBro/books/" + book.id;
		titleDiv.text = book.title;
		bookDiv.appendChild(titleDiv);
		var breakDiv = document.createElement("br");
		bookDiv.appendChild(breakDiv);
		if(book.authorName != null && book.authorName != "" && book.authorID != null){
			var authorDiv = document.createElement("a");
			authorDiv.text = "Author: " + book.authorName;
			authorDiv.href = "http://localhost/LiBro/authors/" + book.authorID;
			bookDiv.appendChild(authorDiv);
			breakDiv = document.createElement("br");
			bookDiv.appendChild(breakDiv);
		}
		if(book.seriesName != null && book.seriesName != "" && book.seriesID != null){
			var seriesDiv = document.createElement("a");
			seriesDiv.text = "Series: " + ((book.seriesName != null) ? book.seriesName : "N\\A");
			if(book.seriesID != null) seriesDiv.href = "http://localhost/LiBro/series/" + book.seriesID;
			bookDiv.appendChild(seriesDiv);
			breakDiv = document.createElement("br");
			bookDiv.appendChild(breakDiv);
		}
		var publishedDiv = document.createTextNode("Published: " + book.published);
		bookDiv.appendChild(publishedDiv);
		breakDiv = document.createElement("br");
		bookDiv.appendChild(breakDiv);
		if(book.description != null && book.description != ""){
			var descDiv = document.createTextNode(book.description);
			bookDiv.appendChild(descDiv);
			if (book.description.length > 200){
				var collapserDiv = document.createElement("a");
				collapserDiv.className = "collapser";
				collapserDiv.addEventListener("click", function(){collapseDesc(reznum)});
				collapserDiv.text = " Show less";
				bookDiv.appendChild(collapserDiv);
			}
		}
		return bookDiv;
	}
    
	function getShortBookEntryNode(book, reznum){
		var bookDiv = document.createElement("p");
		bookDiv.id = "bookInfo" + reznum;
		var titleDiv = document.createElement("a");
		titleDiv.href = "http://localhost/LiBro/books/" + book.id;
		titleDiv.text = book.title;
		bookDiv.appendChild(titleDiv);
		var breakDiv = document.createElement("br");
		bookDiv.appendChild(breakDiv);
		if(book.authorName != null && book.authorName != "" && book.authorID != null){
			var authorDiv = document.createElement("a");
			authorDiv.text = "Author: " + book.authorName;
			authorDiv.href = "http://localhost/LiBro/authors/" + book.authorID;
			bookDiv.appendChild(authorDiv);
			breakDiv = document.createElement("br");
			bookDiv.appendChild(breakDiv);
		}
		if(book.seriesName != null && book.seriesName != "" && book.seriesID != null){
			var seriesDiv = document.createElement("a");
			seriesDiv.text = "Series: " + ((book.seriesName != null) ? book.seriesName : "N\\A");
			if(book.seriesID != null) seriesDiv.href = "http://localhost/LiBro/series/" + book.seriesID;
			bookDiv.appendChild(seriesDiv);
			breakDiv = document.createElement("br");
			bookDiv.appendChild(breakDiv);
		}
		if(book.description != null && book.description != ""){
			var descDiv = document.createTextNode((book.description != null) ? (book.description.substring(0, 200)) : (""));
			bookDiv.appendChild(descDiv);
			if (book.description.length > 200){
				var expanderDiv = document.createElement("a");
				expanderDiv.className = "expander";
				expanderDiv.addEventListener("click", function(){expandDesc(reznum)});
				expanderDiv.text = "... Show more";
				bookDiv.appendChild(expanderDiv);
			}
		}
		return bookDiv;
	}

	function expandDesc(id){
		document.getElementById("bookInfo" + id).replaceWith(getLongBookEntryNode(bookList[id], id));
	}

	function collapseDesc(id){
		document.getElementById("bookInfo" + id).replaceWith(getShortBookEntryNode(bookList[id], id));
	}

	function getBookList(){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					var rez = JSON.parse(this.responseText);
					var lst = document.getElementById("bookList");
					if(Array.isArray(rez)){
						bookList = rez;
						document.getElementById("bookLoadSpinner").style.display="none";
						for(book in rez){
							lst.appendChild(getShortBookEntryNode(rez[book], book));
						}
					} else {
						bookList = [rez];
						document.getElementById("bookLoadSpinner").style.display="none";
						lst.appendChild(getBookNode(rez));
						document.name = rez.title;
					}
				} else {
					return false;
				}
			}
		}
		xmlhttp.open("GET", <?php if(isset($_GET["id"])){
		    if($id = filter_var($_GET["id"], FILTER_VALIDATE_INT)){
		        echo "\"http://localhost/LiBro/api/books/$id\"";
		    } else {
		        echo "\"http://localhost/LiBro/api/books/\"";
		    }
		} else if (isset($_GET["author"])){
		    if($author = filter_var($_GET["author"], FILTER_VALIDATE_INT)){
		        echo "\"http://localhost/LiBro/api/authors/$author/books/\"";
		    } else {
		        echo "\"http://localhost/LiBro/api/books/\"";
		    }
		} else if (isset($_GET["series"])){
		    if($series = filter_var($_GET["series"], FILTER_VALIDATE_INT)){
		        echo "\"http://localhost/LiBro/api/series/$series/books/\"";
		    } else {
		        echo "\"http://localhost/LiBro/api/books/\"";
		    }
		} else{
		    echo "\"http://localhost/LiBro/api/books/\"";
		}?>, true);
		xmlhttp.send();
	}

	getBookList();
    </script>
</body>
</html>