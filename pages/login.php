<div id="LoginDiv" class="container">
	<button id="LoginButton"onclick="toggleView()">Administrator login</button>
	<div id="LoginBox" style="display:none">
		<div id="LoginFrame">
			<div id="LoginContainer">
			<form>
				Username:</br>
				<input id="username" type="text"/></br>
				Password:</br>
				<input id="password" type="password"/></br>
				<input type="button" value="Login" style="float:right;margin:6px" onclick="submitLogin()">
			</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	window.addEventListener('click', function(e){
		if(!document.getElementById("LoginDiv").contains(e.target)){
			toggleView();
			}
		});

	function enterPress(e){
		if (e.keyCode == 13){
			submitLogin();
		}
	}
	
	function toggleView(){
		var l = document.getElementById("LoginBox");
		if(l.style.display=="none"){
			l.style.display="block";
			document.addEventListener("keypress", enterPress);
		} else {
			l.style.display="none";
			document.removeEventListener("keypress", enterPress);
		}	
	}

	function submitLogin(){
		var username = document.getElementById("username").value;
		var password = document.getElementById("password").value;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					location.reload(true);
				} else {
					alert("Login failed. Make sure username and password are correct, or try again later.");
					return false;
				}
			}
		}
		xmlhttp.open("POST", "pages/loginLogic.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("username="+username+"&password="+password);
	}
</script>