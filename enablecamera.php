<?php
session_start();
if(!isset($_SESSION['member'])){
	header("Location: index.php");
}
?>

<?php
$myfile = fopen("config.txt", "w") or die("Unable to open file!");
$txt = "# uncomment to force a HDMI mode rather than DVI. This can make audio work in";
fwrite($myfile, $txt);
$txt = "# DMT (computer monitor) modes\n";
fwrite($myfile, $txt);
/* Comments for the BASH File */
$txt = "#hdmi_drive=2\n";
fwrite($myfile, $txt);
$txt = "# uncomment to increase signal to HDMI, if you have interference, blanking, or\n";
fwrite($myfile, $txt);
$txt = "# no display\n";
fwrite($myfile, $txt);
$txt = "#config_hdmi_boost=4\n";
fwrite($myfile, $txt);
$txt = "# uncomment for composite PAL\n";
fwrite($myfile, $txt);
$txt = "#sdtv_mode=2\n";
fwrite($myfile, $txt);
$txt = "#uncomment to overclock the arm. 700 MHz is the default.\n";
fwrite($myfile, $txt);
$txt = "#arm_freq=800\n";
fwrite($myfile, $txt);
/* End comments, variables to enable and disable camera, simply change start_x to 1 or 0*/
$txt = "start_x=1\n";
fwrite($myfile, $txt);
$txt = "gpu_mem=128";
fclose($myfile);
header("location: enablecamera.html");
?>