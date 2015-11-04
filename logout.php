<?Php
	
session_start();
if(!isset($_SESSION['member'])){
	header("Location: index.php");
}
    session_destroy ();
	
	header("location: index.php");
	
?>

