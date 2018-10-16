<button id="LogoutButton"onclick="logout()">Log Out</button>

<script type="text/javascript">

	function updateToken(){
		var tok = localStorage.getItem("AuthToken");
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					localStorage.setItem("AuthToken", this.responseText);
				} else {
					return false;
				}
			}
		}
		xmlhttp.open("POST", "http://localhost/LiBro/api/auth", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.send("token="+tok);
	}
	
	function logout(){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					localStorage.removeItem("AuthToken");
					location.reload(true);
				} else {
					return false;
				}
			}
		}
		xmlhttp.open("POST", "http://localhost/LiBro/pages/logoutLogic.php", true);
		xmlhttp.send();
	}

	updateToken();
	setInterval(updateToken, 600000);
</script>