<?php
session_start();
if(!isset($_SESSION['member'])){
	header("Location: index.php");
}
?>

<?php
$output = shell_exec('sudo sh /home/pi/video.sh');
?>
<html>
<title>RPI: Capture</title>
<head>
<link href="assets/css/bootstrap.css" rel="stylesheet"/>
<link href="assets/css/font-awesome.css" rel="stylesheet"/>
<link rel="icon" href="assets/img/favicon.ico">
</head>
<style>
body {
    background-color: #eee;
}
</style>
<body>

<div style="width:100%;">
<center><img src="http://www.roxara.com/index_files/image545.gif" alt="Loading..." style="width:400px;height: 500px" ></center>
<br>
<center>
<script type="text/javascript">   
function Redirect() 
{  
window.location="http://localhost/raspberry/home.html"; 
}
document.write("Recording..., you shall be re-directed to home page after recording.\n Please note: You need to convert the video from h264 to MP4 yourself!"); 
setTimeout('Redirect()', 10000);
</script>
<center>
</div>
</body>
</html>

