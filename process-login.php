<?php
//Start session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>

<title>Index: Login</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>

</head>

<body>
<div style="margin:0 auto; width:300px;">
	<?php
if( isset($_SESSION['INVALID']) && is_array($_SESSION['INVALID']) && count($_SESSION['INVALID']) >0 ) {
echo '<ul class="err">';
foreach($_SESSION['INVALID'] as $msg) {
echo '<li>',$msg,'</li>';
}
echo '</ul>';
unset($_SESSION['INVALID']);
}
?>
</div>
<form action="login.php" method="post" name="login">

	<div style="width:100%;">

	<?php
	include_once("index.php")
	?>
</form>
			
	</div>
</body>
</html>

