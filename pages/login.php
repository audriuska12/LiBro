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
				<input type="button" value="Login" style="float:right" onclick="submitLogin()">
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
	
	function toggleView(){
		var l = document.getElementById("LoginBox");
		if(l.style.display=="none"){
			l.style.display="block";
		} else {
			l.style.display="none";
		}	
	}

	function submitLogin(){
		var username = document.getElementById("username").value;
		var password = document.getElementById("password").value;
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			console.log(this);
			if(this.readyState == 4){
				if(this.status == 200){
					alert("Response" + this.responseText);
					return false;
				} else {
					return false;
				}
			}
		}
		xmlhttp.open("POST", "pages/loginLogic.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("username="+username+"&password="+password);
	}
</script>