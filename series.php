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
            include "pages/seriesAdminOptions.php";
        }?>
        	<div id="seriesList">
        		<div id="seriesLoadSpinner" class="loader"></div>
        	</div>
		</div>
        <?php include "pages/footer.php";?>
    </div>
    <script type="text/javascript">

	var seriesList;

	function getSeriesHTML(series){
		var id = series.id;
		var name = series.name;
		var desc = series.description ? series.description : "";
		var author = (series.authorName != null) ? series.authorName : "N\\A";
		return "<div>" + name + "</br>" + ((series.authorID != null) ? ("<a href=\"http://localhost/LiBro/authors/" + series.authorID + "\">") : "") + "Author: " + author + "</a></br>Description:" + desc + "</br><a href=\"http://localhost/LiBro/series/" + id + "/books\">Books</a></div>";
	}

	function getLongSeriesEntryHTML(series, reznum){
		var id = series.id;
		var name = series.name;
		var desc = seroes.description ? (series.description + "<a class=\"collapser\" onclick=\"collapseDesc(" + reznum + ")\"> Show less</a>") : "";
		var author = (series.authorName != null) ? series.authorName : "N\\A";
		return "<div id=\"seriesInfo" + reznum + "\"><a href=\"http://localhost/LiBro/series/" + id + "\"\>" + name + "</a></br>" + ((series.authorID != null) ? ("<a href=\"http://localhost/LiBro/authors/" + series.authorID + "\">") : "") + "Author: " + author + "</a></br>Description: " + desc + "</br><a href=\"http://localhost/LiBro/series/" + id + "/books\">Books</a></div>";
	}
    
	function getShortSeriesEntryHTML(series, reznum){
		var id = series.id;
		var name = series.name;
		var desc = series.description ? ((series.description.length <= 200) ? series.description : (series.description.substring(0,197) + "... <a class=\"expander\" onclick=\"expandDesc(" + reznum + ")\"> Show more</a>")) : "";
		var author = (series.authorName != null) ? series.authorName : "N\\A";
		return "<div id=\"seriesInfo" + reznum + "\"><a href=\"http://localhost/LiBro/series/" + id + "\"\>" + name + "</a></br>" + ((series.authorID != null) ? ("<a href=\"http://localhost/LiBro/authors/" + series.authorID + "\">") : "") + "Author: " + author + "</a></br>Description: " + desc + "</br><a href=\"http://localhost/LiBro/series/" + id + "/books\">Books</a></div>";
	}

	function expandDesc(id){
		document.getElementById("seriesInfo" + id).outerHTML = getLongSeriesEntryHTML(bookList[id], id);
	}

	function collapseDesc(id){
		document.getElementById("seriesInfo" + id).outerHTML = getShortSeriesEntryHTML(bookList[id], id);
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
							lst.innerHTML += getShortSeriesEntryHTML(rez[series], series) + "</br>";
						}
					} else {
						seriesList = [rez];
						document.getElementById("seriesLoadSpinner").style.display="none";
						lst.innerHTML += getSeriesHTML(rez);
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