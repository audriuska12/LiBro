<?php session_start()?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="http://localhost/LiBro/styles.css">
<title>Authors</title>
</head>
<body>
    <div id="container">
        <?php include "pages/header.php";?>
        <div id="content">
        <?php if(isset($_SESSION["token"])){
            include "pages/authors/authorAdminOptions.php";
        }?>
        	<div id="authorList">
        		<div id="authorLoadSpinner" class="loader"></div>
        	</div>
		</div>
        <?php include "pages/footer.php";?>
    </div>
    <script type="text/javascript">

	var authorList;

	function getAuthorNode(author){
		var container = document.createElement("div");
		container.className = "SingleEntryContainer";
		var imgDiv = document.createElement("img");
		imgDiv.className = "SingleEntryImage";
		imgDiv.src = "http://localhost/LiBro/images/cover-placeholder.gif";
		container.appendChild(imgDiv);
		var authorDiv = document.createElement("div");
		authorDiv.className = "SingleEntryData";
		var nameDiv = document.createTextNode(author.name);
		authorDiv.appendChild(nameDiv);
		var breakDiv = document.createElement("br");
		authorDiv.appendChild(breakDiv);
		if(author.bio != null && author.bio != ""){
			var bioDiv = document.createTextNode("Biography: " + author.bio);
			authorDiv.appendChild(bioDiv);
			breakDiv = document.createElement("br");
			authorDiv.appendChild(breakDiv);
		}
		var seriesDiv = document.createElement("a");
		seriesDiv.href = "http://localhost/LiBro/authors/" + author.id + "/series";
		seriesDiv.text = "Series";
		authorDiv.appendChild(seriesDiv);
		breakDiv = document.createElement("br");
		authorDiv.appendChild(breakDiv);
		var bookDiv = document.createElement("a");
		bookDiv.href = "http://localhost/LiBro/authors/" + author.id + "/books";
		bookDiv.text = "Books";
		authorDiv.appendChild(bookDiv);
		container.appendChild(authorDiv);
		return container;
	}

	function getLongAuthorEntryNode(author, reznum){
		var authorDiv = document.createElement("div");
		authorDiv.id = "authorInfo" + reznum;
		var nameDiv = document.createElement("a");
		nameDiv.href = "http://localhost/LiBro/authors/" + author.id;
		nameDiv.text = author.name;
		authorDiv.appendChild(nameDiv);
		var breakDiv = document.createElement("br");
		authorDiv.appendChild(breakDiv);
		if(author.bio != null && author.bio != ""){
			var bioDiv = document.createTextNode("Bio: " + author.bio);
			authorDiv.appendChild(bioDiv);
			if (author.bio.length > 200){
				var collapserDiv = document.createElement("a");
				collapserDiv.className = "collapser";
				collapserDiv.addEventListener("click", function(){collapseDesc(reznum)});
				collapserDiv.text = " Show less";
				authorDiv.appendChild(collapserDiv);
			}
			breakDiv = document.createElement("br");
			authorDiv.appendChild(breakDiv);
		}
		var seriesDiv = document.createElement("a");
		seriesDiv.href = "http://localhost/LiBro/authors/" + author.id + "/series";
		seriesDiv.text = "Series";
		authorDiv.appendChild(seriesDiv);
		breakDiv = document.createElement("br");
		authorDiv.appendChild(breakDiv);
		var bookDiv = document.createElement("a");
		bookDiv.href = "http://localhost/LiBro/authors/" + author.id + "/books";
		bookDiv.text = "Books";
		authorDiv.appendChild(bookDiv);
		breakDiv = document.createElement("br");
		authorDiv.appendChild(breakDiv);
		breakDiv = document.createElement("br");
		authorDiv.appendChild(breakDiv);
		return authorDiv;
	}
    
	function getShortAuthorEntryNode(author, reznum){
		var authorDiv = document.createElement("div");
		authorDiv.id = "authorInfo" + reznum;
		var nameDiv = document.createElement("a");
		nameDiv.href = "http://localhost/LiBro/authors/" + author.id;
		nameDiv.text = author.name;
		authorDiv.appendChild(nameDiv);
		var breakDiv = document.createElement("br");
		authorDiv.appendChild(breakDiv);
		if(author.bio != null && author.bio != ""){
			var bioDiv = document.createTextNode("Bio: " + author.bio.substring(0,197));
			authorDiv.appendChild(bioDiv);
			if (author.bio.length > 200){
				var expanderDiv = document.createElement("a");
				expanderDiv.className = "expander";
				expanderDiv.addEventListener("click", function(){expandDesc(reznum)});
				expanderDiv.text = "... Show more";
				authorDiv.appendChild(expanderDiv);
			}
			breakDiv = document.createElement("br");
			authorDiv.appendChild(breakDiv);
		}
		var seriesDiv = document.createElement("a");
		seriesDiv.href = "http://localhost/LiBro/authors/" + author.id + "/series";
		seriesDiv.text = "Series";
		authorDiv.appendChild(seriesDiv);
		breakDiv = document.createElement("br");
		authorDiv.appendChild(breakDiv);
		var bookDiv = document.createElement("a");
		bookDiv.href = "http://localhost/LiBro/authors/" + author.id + "/books";
		bookDiv.text = "Books";
		authorDiv.appendChild(bookDiv);
		breakDiv = document.createElement("br");
		authorDiv.appendChild(breakDiv);
		breakDiv = document.createElement("br");
		authorDiv.appendChild(breakDiv);
		return authorDiv;
	}

	function expandDesc(id){
		document.getElementById("authorInfo" + id).replaceWith(getLongAuthorEntryNode(authorList[id], id));
	}

	function collapseDesc(id){
		document.getElementById("authorInfo" + id).replaceWith(getShortAuthorEntryNode(authorList[id], id));
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
							lst.appendChild(getShortAuthorEntryNode(rez[author], author));
						}
					} else {
						authorList = [rez];
						document.getElementById("authorLoadSpinner").style.display="none";
						lst.appendChild(getAuthorNode(rez));
						document.title = rez.name;
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