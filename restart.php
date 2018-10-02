<?php
if(!isset($_SESSION['member'])){
	header("Location: index.php");
}

?>
<?php
if (isset($_POST['RESTART']))
{
exec('sudo reboot');
header("location: index.php");
}
?>
