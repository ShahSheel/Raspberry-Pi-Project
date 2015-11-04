

<?php
//Start session
session_start();
 
//Include database connection details
require_once('connection.php');
 
//Array to store validation errors
$error_arr = array();
 
//Validation error flag
$errflag = false;
 
//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
$str = @trim($str);
if(get_magic_quotes_gpc()) {
$str = stripslashes($str);
}
return mysql_real_escape_string($str);
}
 
//Sanitize the POST values
$username = clean($_POST['username']);
$password = clean($_POST['pass']);
 
//Input Validations
if($username == '') {
$error_arr[] = 'Username missing';
$errflag = true;
exec('sudo python /var/www/wrong.py');
}
if($password == '') {
$error_arr[] = 'Password missing';
$errflag = true;
exec('sudo python /var/www/wrong.py');

}
 
//If there are input validations, redirect back to the login form
if($errflag) {
$_SESSION['INVALID'] = $error_arr;
header("location: process-login.php");
exit();
}
 
//Create query
$qry="SELECT * FROM member WHERE username='$username' AND password='".md5($_POST['pass'])."'";
$result=mysql_query($qry);
 
//Check whether the query was successful or not
if($result) {
if(mysql_num_rows($result) > 0) {
//Login Successful
session_regenerate_id();
$member = mysql_fetch_assoc($result);
$_SESSION['SESS_MEMBER_ID'] = $member['mem_id'];
$_SESSION['SESS_FIRST_NAME'] = $member['username'];
$_SESSION['SESS_LAST_NAME'] = $member['password'];
$_SESSION['member'] = 'member';
header("location: home.html");
exec('sudo python /var/www/on.py');

exit();
}else {
//Login failed
$error_arr[] = 'user name and password not found';

$errflag = true;
if($errflag) {
$_SESSION['INVALID'] = $error_arr;
session_write_close();
header("location: login.php");
exec('sudo python /var/www/wrong.py');
exit();
}
}
}else {
die("Query failed");
}
?>