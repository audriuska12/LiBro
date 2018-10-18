<?php session_start()?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="http://localhost/LiBro/styles.css">
<title>Series</title>
</head>
<body>
    <div id="container">
        <?php include "pages/header.php";?>
        <div id="content">
        <?php if(isset($_SESSION["token"])){
            include "pages/series/seriesAdminOptions.php";
        }?>
        	<div id="seriesList">
        		<div id="seriesLoadSpinner" class="loader"></div>
        	</div>
		</div>
        <?php include "pages/footer.php";?>
    </div>
    <script type="text/javascript">

	var seriesList;

	function getSeriesNode(series){
		console.log(series);
		var container = document.createElement("div");
		container.className = "SingleEntryContainer";
		var imgDiv = document.createElement("img");
		imgDiv.className = "SingleEntryImage";
		imgDiv.src = "http://localhost/LiBro/images/cover-placeholder.gif";
		container.appendChild(imgDiv);
		var seriesDiv = document.createElement("div");
		seriesDiv.className = "SingleEntryData";
		var titleDiv = document.createTextNode(series.name);
		seriesDiv.appendChild(titleDiv);
		var breakDiv = document.createElement("br");
		seriesDiv.appendChild(breakDiv);
		if(series.authorName != null && series.authorName != "" && series.authorID != null){
			var authorDiv = document.createElement("a");
			authorDiv.text = "Author: " + (series.authorName);
			authorDiv.href = "http://localhost/LiBro/authors/" + series.authorID;
			seriesDiv.appendChild(authorDiv);
			breakDiv = document.createElement("br");
			seriesDiv.appendChild(breakDiv);
		}
		if(series.description != null && series.description != ""){
			var descDiv = document.createTextNode(series.description);
			seriesDiv.appendChild(descDiv);
			breakDiv = document.createElement("br");
			seriesDiv.appendChild(breakDiv);
		}
		var bookDiv = document.createElement("a");
		bookDiv.href = "http://localhost/LiBro/series/" + series.id + "/books";
		bookDiv.text = "Books";
		seriesDiv.appendChild(bookDiv);
		container.appendChild(seriesDiv);
		return container;
	}

	function getLongSeriesEntryNode(series, reznum){
		var seriesDiv = document.createElement("div");
		seriesDiv.id = "seriesInfo" + reznum;
		var titleDiv = document.createElement("a");
		titleDiv.href = "http://localhost/LiBro/series/" + series.id;
		titleDiv.text = series.name;
		seriesDiv.appendChild(titleDiv);
		var breakDiv = document.createElement("br");
		seriesDiv.appendChild(breakDiv);
		if(series.authorName != null && series.authorName != "" && series.authorID != null){
			var authorDiv = document.createElement("a");
			authorDiv.text = "Author: " + (series.authorName);
			authorDiv.href = "http://localhost/LiBro/authors/" + series.authorID;
			seriesDiv.appendChild(authorDiv);
			breakDiv = document.createElement("br");
			seriesDiv.appendChild(breakDiv);
		}
		if(series.description != null && series.description != ""){
			var descDiv = document.createTextNode(series.description);
			seriesDiv.appendChild(descDiv);
			if (series.description.length > 200){
				var collapserDiv = document.createElement("a");
				collapserDiv.className = "collapser";
				collapserDiv.addEventListener("click", function(){collapseDesc(reznum)});
				collapserDiv.text = " Show less";
				seriesDiv.appendChild(collapserDiv);
			}
			breakDiv = document.createElement("br");
			seriesDiv.appendChild(breakDiv);
		}
		var bookDiv = document.createElement("a");
		bookDiv.href = "http://localhost/LiBro/series/" + series.id + "/books";
		bookDiv.text = "Books";
		seriesDiv.appendChild(bookDiv);
		breakDiv = document.createElement("br");
		seriesDiv.appendChild(breakDiv);
		breakDiv = document.createElement("br");
		seriesDiv.appendChild(breakDiv);
		return seriesDiv;
	}
    
	function getShortSeriesEntryNode(series, reznum){
		var seriesDiv = document.createElement("div");
		seriesDiv.id = "seriesInfo" + reznum;
		var titleDiv = document.createElement("a");
		titleDiv.href = "http://localhost/LiBro/series/" + series.id;
		titleDiv.text = series.name;
		seriesDiv.appendChild(titleDiv);
		var breakDiv = document.createElement("br");
		seriesDiv.appendChild(breakDiv);
		if(series.authorName != null && series.authorName != "" && series.authorID != null){
			var authorDiv = document.createElement("a");
			authorDiv.text = "Author: " + (series.authorName);
			authorDiv.href = "http://localhost/LiBro/authors/" + series.authorID;
			seriesDiv.appendChild(authorDiv);
			breakDiv = document.createElement("br");
			seriesDiv.appendChild(breakDiv);
		}
		if(series.description != null && series.description != ""){
			var descDiv = document.createTextNode(series.description.substring(0,200));
			seriesDiv.appendChild(descDiv);
			if (series.description.length > 200){
				var expanderDiv = document.createElement("a");
				expanderDiv.className = "expander";
				expanderDiv.addEventListener("click", function(){expandDesc(reznum)});
				expanderDiv.text = "... Show more";
				seriesDiv.appendChild(expanderDiv);
			}
			breakDiv = document.createElement("br");
			seriesDiv.appendChild(breakDiv);
		}
		var bookDiv = document.createElement("a");
		bookDiv.href = "http://localhost/LiBro/series/" + series.id + "/books";
		bookDiv.text = "Books";
		seriesDiv.appendChild(bookDiv);
		breakDiv = document.createElement("br");
		seriesDiv.appendChild(breakDiv);
		breakDiv = document.createElement("br");
		seriesDiv.appendChild(breakDiv);
		return seriesDiv;
	}

	function expandDesc(id){
		document.getElementById("seriesInfo" + id).replaceWith(getLongSeriesEntryNode(seriesList[id], id));
	}

	function collapseDesc(id){
		document.getElementById("seriesInfo" + id).replaceWith(getShortSeriesEntryNode(seriesList[id], id));
	}

	function getSeriesList(){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					var rez = JSON.parse(this.responseText);
					var lst = document.getElementById("seriesList");
					if(Array.isArray(rez)){
						seriesList = rez;
						document.getElementById("seriesLoadSpinner").style.display="none";
						for(series in rez){
							lst.appendChild(getShortSeriesEntryNode(rez[series], series));
						}
					} else {
						seriesList = [rez];
						document.getElementById("seriesLoadSpinner").style.display="none";
						lst.appendChild(getSeriesNode(rez));
						document.title = rez.name;
					}
				} else if (this.status == 404){
					document.getElementById("content").innerHTML = "Series not found!";
				} else {
					return false;
				}
			}
		}
		xmlhttp.open("GET", <?php if(isset($_GET["id"])){
		    if($id = filter_var($_GET["id"], FILTER_VALIDATE_INT)){
		        echo "\"http://localhost/LiBro/api/series/$id\"";
		    } else {
		        echo "\"http://localhost/LiBro/api/series/\"";
		    }
		} else if (isset($_GET["author"])){
		    if($author = filter_var($_GET["author"], FILTER_VALIDATE_INT)){
		        echo "\"http://localhost/LiBro/api/authors/$author/series/\"";
		    } else {
		        echo "\"http://localhost/LiBro/api/series/\"";
		    }
		} else{
		    echo "\"http://localhost/LiBro/api/series/\"";
		}?>, true);
		xmlhttp.send();
	}

	getSeriesList();
    </script>
</body>
</html>