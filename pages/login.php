<div id="LoginDiv">
	<button id="LoginButton"onclick="toggleLoginView()">Administrator login</button>
	<div id="LoginBox" style="display:none">
		<div id="LoginFrame">
			<div id="LoginContainer">
			<form>
				<div>Username:</div>
				<div><input id="username" type="text"/></div>
				<div>Password:</div>
				<div><input id="password" type="password"/></div>
				<div><input id="LoginSubmit" type="button" value="Login" onclick="submitLogin()"></div>
			</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	window.addEventListener('click', function(e){
		if(!document.getElementById("LoginDiv").contains(e.target)){
			var l = document.getElementById("LoginBox");
			if (l.style.display != "none"){
					toggleLoginView();
				}
			}
		});

	function enterPress(e){
		if (e.keyCode == 13){
			submitLogin();
		}
	}
	
	function toggleLoginView(){
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
					localStorage.setItem("AuthToken", this.responseText);
					location.reload(true);
				} else {
					console.log(this);
					alert("Login failed. Make sure username and password are correct, or try again later.");
					return false;
				}
			}
		}
		xmlhttp.open("POST", "http://localhost/LiBro/pages/loginLogic.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("username="+username+"&password="+password);
	}
</script>