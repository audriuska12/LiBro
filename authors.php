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
        <?php if(isset($_SESSION["token"])){
            include "pages/authorAdminOptions.php";
        }?>
        	<div id="authorList">
        		<div id="authorLoadSpinner" class="loader"></div>
        	</div>
		</div>
        <?php include "pages/footer.php";?>
    </div>
    <script type="text/javascript">

	var authorList;

	function getAuthorHTML(author){
		var name = author.name;
		var id = author.id;
		var bio = (author.bio) ? author.bio : "";
		return "<div>" + name + ((bio != "") ? ("</br>Biography: " + bio) : "") + "</br><a href=\"http://localhost/LiBro/authors/" + id + "/books\">Books</a></br><a href=\"http://localhost/LiBro/authors/" + id + "/series\">Series</a></div>";
	}

	function getLongAuthorEntryHTML(author, reznum){
		var name = author.name;
		var id = author.id;
		var bio = (author.bio) ? (author.bio + "<a class=\"collapser\" onclick=\"collapseDesc(" + reznum + ")\"> Show less</a>") : "";
		return "<div id=\"authorInfo" + reznum + "\"><a href=\"http://localhost/LiBro/authors/" + id +"\">" + name + "</a>" + ((bio != "") ? ("</br>Biography: " + bio) : "") + "</br><a href=\"http://localhost/LiBro/authors/" + id + "/books\">Books</a></br><a href=\"http://localhost/LiBro/authors/" + id + "/series\">Series</a></div>";
	}
    
	function getShortAuthorEntryHTML(author, reznum){
		var name = author.name;
		var id = author.id;
		var bio = (author.bio) ? ((author.bio.length <= 200)? author.bio : (author.bio.substring(0,197) + "... <a class=\"expander\" onclick=\"expandDesc(" + reznum + ")\"> Show more</a>")) : "";
		return "<div id=\"authorInfo" + reznum + "\"><a href=\"http://localhost/LiBro/authors/" + id +"\">" + name + "</a>" + ((bio != "") ? ("</br>Biography: " + bio) : "") + "</br><a href=\"http://localhost/LiBro/authors/" + id + "/books\">Books</a></br><a href=\"http://localhost/LiBro/authors/" + id + "/series\">Series</a></div>";
	}

	function expandDesc(id){
		document.getElementById("authorInfo" + id).outerHTML = getLongAuthorEntryHTML(authorList[id], id);
	}

	function collapseDesc(id){
		document.getElementById("authorInfo" + id).outerHTML = getShortAuthorEntryHTML(authorList[id], id);
	}

	function getAuthorList(){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					var rez = JSON.parse(this.responseText);
					var lst = document.getElementById("authorList");
					if(Array.isArray(rez)){
						authorList = rez;
						document.getElementById("authorLoadSpinner").style.display="none";
						for(author in rez){
							lst.innerHTML += getShortAuthorEntryHTML(rez[author], author) + "</br>";
						}
					} else {
						authorList = [rez];
						document.getElementById("authorLoadSpinner").style.display="none";
						lst.innerHTML += getAuthorHTML(rez, 0);
					}
				} else {
					return false;
				}
			}
		}
		xmlhttp.open("GET", <?php if(isset($_GET["id"])){
		    if($id = filter_var($_GET["id"], FILTER_VALIDATE_INT)){
		        echo "\"http://localhost/LiBro/api/authors/$id\"";
		    } else {
		        echo "\"http://localhost/LiBro/api/authors/\"";
		    }
		} else {
		    echo "\"http://localhost/LiBro/api/authors/\"";
		}?>, true);
		xmlhttp.send();
	}

	getAuthorList();
    </script>
</body>
</html>