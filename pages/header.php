<div id="header">
<div id="HomeDiv"><a href="http://localhost/LiBro/index">LiBro</a></div>
<div id="NavDiv"><?php include "navlinks.php";?></div>
<div id="UserDiv"><?php if(!isset($_SESSION["token"])){include "login.php";} else include "logout.php"?></div>
</div>