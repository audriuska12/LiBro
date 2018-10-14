<?php session_start()?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="http://localhost/LiBro/styles.css">
<title>LiBro</title>
</head>
<body>
    <div id="container">
        <?php include "pages/header.php";?>
        <div id="content">
        	<div id="bookList">
        		
        	</div>
		</div>
        <?php include "pages/footer.php";?>
    </div>
    <script type="text/javascript">

	var bookList;

	function getBookHTML(book, reznum){
		var title = book.title;
		var desc = book.description; 
		return "<div>" + title + "</br>" + desc + "</div></br>";
	}

	function getLongBookEntryHTML(book, reznum){
		var title = book.title;
		var desc = book.description + "<a class=\"collapser\" onclick=\"collapseDesc(" + reznum + ")\"> Show less</a>"; 
		return "<div id=\"bookInfo" + reznum + "\">" + title + "</br>" + desc + "</br></div>";
	}
    
	function getShortBookEntryHTML(book, reznum){
		var title = book.title;
		var desc = (book.description.length <= 200) ? book.description : (book.description.substring(0,197) + "... <a class=\"expander\" onclick=\"expandDesc(" + reznum + ")\"> Show more</a>"); 
		return "<div id=\"bookInfo" + reznum + "\">" + title + "</br>" + desc + "</br></div>";
	}

	function expandDesc(id){
		document.getElementById("bookInfo" + id).outerHTML = getLongBookEntryHTML(bookList[id], id);
	}

	function collapseDesc(id){
		document.getElementById("bookInfo" + id).outerHTML = getShortBookEntryHTML(bookList[id], id);
	}
    
    var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function(){
		if(this.readyState == 4){
			if(this.status == 200){
				var rez = JSON.parse(this.responseText);
				var lst = document.getElementById("bookList");
				if(Array.isArray(rez)){
					bookList = rez;
					for(book in rez){
						lst.innerHTML += getShortBookEntryHTML(rez[book], book) + "</br>";
					}
				} else {
					bookList = [rez];
					lst.innerHTML += getBookHtml(rez, 0);
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
	} else {
	    echo "\"http://localhost/LiBro/api/books/\"";
	}?>, true);
	xmlhttp.send();
    </script>
</body>
</html>