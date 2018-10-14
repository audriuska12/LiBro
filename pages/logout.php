<button onclick="logout()">Logout</button>

<script type="text/javascript">
	function logout(){
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function(){
			if(this.readyState == 4){
				if(this.status == 200){
					location.reload(true);
				} else {
					return false;
				}
			}
		}
		xmlhttp.open("POST", "pages/logoutLogic.php", true);
		xmlhttp.send();
	}
</script>