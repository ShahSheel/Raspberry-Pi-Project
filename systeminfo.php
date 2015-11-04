<?php
if(!isset($_SESSION['member'])){
	header("Location: index.php");
}
?>
<?php
$USEvcgencmd = 0;
$DURATION_start=microtime(true);
?>
<head>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<title><?php echo $_SERVER['SERVER_NAME']; ?> - Informations</title>
<meta HTTP-EQUIV=Refresh CONTENT='10'>

</head>
<body>

<?php
$buttonUSEvcgencmd = isset($_POST["USEvcgencmd"]) ? $_POST["USEvcgencmd"] : "";
if (!isset($vcgencmd)) { $vcgencmd=0; }

if (!empty($buttonUSEvcgencmd)) {
	if ($buttonUSEvcgencmd == "true") { $_SESSION['vcgencmd'] = 1; }
	if ($buttonUSEvcgencmd == "false") { $_SESSION['vcgencmd'] = 0; }
}

if (isset($_SESSION['vcgencmd']) AND $_SESSION['vcgencmd'] == 1) {
	$USEvcgencmd = 1;
} elseif (isset($_SESSION['vcgencmd']) AND $_SESSION['vcgencmd'] == 0) {
	$USEvcgencmd = 0;
}

$RESET = isset($_POST["RESET"]) ? $_POST["RESET"] : "";               
if (!empty($RESET)) {
	if (isset($_SESSION['max_cputemp'])) { unset($_SESSION['max_cputemp']); }
	if (isset($_SESSION['min_cputemp'])) { unset($_SESSION['min_cputemp']); }
	if (isset($_SESSION['max_cpufreq'])) { unset($_SESSION['max_cpufreq']); }
	if (isset($_SESSION['min_cpufreq'])) { unset($_SESSION['min_cpufreq']); }
	if (isset($_SESSION['max_gputemp'])) { unset($_SESSION['max_gputemp']); }
	if (isset($_SESSION['min_gputemp'])) { unset($_SESSION['min_gputemp']); }
}

exec("cat /sys/class/thermal/thermal_zone0/temp",$cputemp);
exec("cat /sys/devices/system/cpu/cpu0/cpufreq/scaling_cur_freq",$cpufreq);
$cputemp = $cputemp[0] / 1000;
$cpufreq = $cpufreq[0] / 1000;

if ($USEvcgencmd == 1) {
	@exec("sudo /opt/vc/bin/vcgencmd measure_temp | sed -e \"s/temp=//\" -e \"s/'C//g\"",$gputemp);
	if (!empty($gputemp[0]) AND !preg_match("/VCHI initialization failed/",$gputemp[0])) { $vcgencmd = 1; }

	
	$CMD = 'sudo /opt/vc/bin/vcgencmd';
	
	if ($vcgencmd == 1) {
		# current active volts
		$VoltTypes = "core sdram_c sdram_i sdram_p";
		$volt_list = array();
		foreach (explode(" ",$VoltTypes) AS $vt) {
			$volt_list["$vt"] = exec("".$CMD." measure_volts ".$vt." | sed -e \"s/volt=//\"");
		}
	
		# current active settings
		exec("".$CMD." get_config int",$config_integers);
		$config_integer = array();
		foreach ($config_integers AS $integer) {
			preg_match('°(.*)=(.*)°',$integer,$int);
			if (isset($int[1]) AND !empty($int[1])) { $config_integer["$int[1]"] = $int[2]; }
		}
		
		# current active frequency's
		$ClockTypes = "arm core h264 isp v3d uart pwm emmc pixel vec hdmi dpi";
		$clock_list = array();
		foreach (explode(" ",$ClockTypes) AS $ct) {
			$clock_list["$ct"] = exec("".$CMD." measure_clock ".$ct." | cut -d'=' -f2");
		}
		
		# current codec enabled
		$CodecTypes = "H264 MPG2 WVC1 MPG4 MJPG WMV9";
		$codec_list = array();
		foreach (explode(" ",$CodecTypes) AS $ct) {
			$codec_list["$ct"] = exec("".$CMD." codec_enabled ".$ct." | cut -d'=' -f2");
		}
	
		# current mem split
		$MemTypes = "arm gpu";
		$mem_list = array();
		foreach (explode(" ",$MemTypes) AS $mt) {
			$mem_list["$mt"] = exec("".$CMD." get_mem ".$mt." | cut -d'=' -f2");
		}
	}
}

// max cpu
if (!isset($_SESSION['max_cputemp'])) {
	$_SESSION['max_cputemp'] = $cputemp;
} elseif ($_SESSION['max_cputemp'] < $cputemp) {
	$_SESSION['max_cputemp'] = $cputemp;
}
if (!isset($_SESSION['max_cpufreq'])) {
	$_SESSION['max_cpufreq'] = $cpufreq;
} elseif ($_SESSION['max_cpufreq'] < $cpufreq) {
	$_SESSION['max_cpufreq'] = $cpufreq;
}
// min cpu
if (!isset($_SESSION['min_cputemp'])) {
	$_SESSION['min_cputemp'] = $cputemp;
} elseif ($cputemp < $_SESSION['min_cputemp']) {
	$_SESSION['min_cputemp'] = $cputemp;
}
if (!isset($_SESSION['min_cpufreq'])) {
	$_SESSION['min_cpufreq'] = $cpufreq;
} elseif ($cpufreq < $_SESSION['min_cpufreq']) {
	$_SESSION['min_cpufreq'] = $cpufreq;
}

if (isset($vcgencmd) AND $vcgencmd == 1) {
	// max gpu
	if (!isset($_SESSION['max_gputemp'])) {
		$_SESSION['max_gputemp'] = $gputemp[0];
	} elseif ($_SESSION['max_gputemp'] < $gputemp[0]) {
		$_SESSION['max_gputemp'] = $gputemp[0];
	}
	// min gpu
	if (!isset($_SESSION['min_gputemp'])) {
		$_SESSION['min_gputemp'] = $gputemp[0];
	} elseif ($gputemp[0] < $_SESSION['min_gputemp']) {
		$_SESSION['min_gputemp'] = $gputemp[0];
	}
}

?>


<table>
 <tr>
  <td>
   <table border="1" cellpadding="5" cellspacing="0">
    <tr align="center" valign="middle"><td colspan="3"><b>CPU Temperature</b></td></tr>
    <tr align="center" valign="middle"><td>Current</td> <td>Max</td> <td>Min</td></tr>
    <tr>
     <td><?php echo $cputemp; ?> &deg;C</td>
     <td><?php echo $_SESSION['max_cputemp']; ?> &deg;C</td>
     <td><?php echo $_SESSION['min_cputemp']; ?> &deg;C</td>
    </tr>
   </table>
  </td>
  <td>
   <table border="1" cellpadding="5" cellspacing="0">
    <tr align="center" valign="middle"><td colspan="3"><b>CPU Frequence</b></td></tr>
    <tr align="center" valign="middle"><td>Current</td> <td>Max</td> <td>Min</td></tr>
    <tr>
     <td><?php echo $cpufreq; ?> MHz</td>
     <td><?php echo $_SESSION['max_cpufreq']; ?> MHz</td>
     <td><?php echo $_SESSION['min_cpufreq']; ?> MHz</td>
    </tr>
   </table>
  </td>
<?php
if ($vcgencmd == 1) {
?>
  <td>
   <table border="1" cellpadding="5" cellspacing="0">
    <tr align="center" valign="middle"><td colspan="3"><b>GPU Temperature</b></td></tr>
    <tr align="center" valign="middle"><td>Current</td> <td>Max</td> <td>Min</td></tr>
    <tr>
     <td><?php echo $gputemp[0]; ?> &deg;C</td>
     <td><?php echo $_SESSION['max_gputemp']; ?> &deg;C</td>
     <td><?php echo $_SESSION['min_gputemp']; ?> &deg;C</td>
    </tr>
   </table>
  </td>
 </tr>
<?php
}
?>
</table>

<?php
if ($vcgencmd == 1) {
	// arrays: $config_integer $volt_list $clock_list $codec_list $mem_list
	echo "<div id='vcgencmd'>\n";
	echo " <table border='0' cellpadding='2' cellspacing='0'>\n";
	echo "  <tr>\n";
	echo "   <td>\n";
	echo "    <table border='1' cellspacing='0'>\n";
	echo "     <tr align='center' valign='middle'><td colspan='2'><b>config.txt integers:</b></td></tr>\n";
	foreach ($config_integer AS $type => $int) {
		echo "     <tr><td> ".$type." </td> <td> ".$int." </td></tr>\n";
	}
	echo "    </table>\n";
	echo "   </td>\n";
	echo "   <td>\n";
	echo "    <table border='1' cellspacing='0'>\n";
	echo "     <tr align='center' valign='middle'><td colspan='2'><b>Frequences:</b></td></tr>\n";
	foreach ($clock_list AS $type => $int) {
		echo "     <tr><td> ".$type." </td> <td> ".$int." </td></tr>\n";
	}
	echo "    </table>\n";
	echo "   </td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "   <td>\n";
	echo "    <table border='1' cellspacing='0'>\n";
	echo "     <tr align='center' valign='middle'><td colspan='2'><b>Codecs:</b></td></tr>\n";
	foreach ($codec_list AS $type => $int) {
		echo "     <tr><td> ".$type." </td> <td> ".$int." </td></tr>\n";
	}
	echo "    </table>\n";
	echo "   </td>\n";
	echo "   <td>\n";
	echo "    <table border='1' cellspacing='0'>\n";
	echo "     <tr align='center' valign='middle'><td colspan='2'><b>Volts:</b></td></tr>\n";
	foreach ($volt_list AS $type => $int) {
		echo "     <tr><td> ".$type." </td> <td> ".$int." </td></tr>\n";
	}
	echo "    </table>\n";
	echo "   </td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "   <td>\n";
	echo "    <table border='1' cellspacing='0'>\n";
	echo "     <tr align='center' valign='middle'><td colspan='2'> <b>Mem-Split:</b> </td></tr>\n";
	foreach ($mem_list AS $type => $int) {
		echo "    <tr><td> ".$type." </td> <td> ".$int." </td></tr>\n";
	}
	echo "    </table>\n";
	echo "   </td>\n";
	echo "  </tr>\n";
	echo " </table>\n";
	echo "</div>\n";
}
?>

<span class="style1">Kernel Information:</span>
<?php echo php_uname(); ?><br/>

<span class="style1">Uptime:</span> 
<?php system("uptime"); ?>

<span class="style1">Memory Usage (MB):</span> 
<?php system("free -m"); ?>

</pre>
<p>
<?php
echo "<span class='style1'>CPU Load:</span><br/>\n";
define("TEMP_PATH","/tmp/");

class CPULoad {
	function check_load() {
		$fd = fopen("/proc/stat","r");
		if ($fd) {
			$statinfo = explode("\n",fgets($fd, 1024));
			fclose($fd);
			foreach($statinfo as $line) {
				$info = explode(" ",$line);
				//echo "<pre>"; var_dump($info); echo "</pre>";
				if($info[0]=="cpu") {
					array_shift($info);  // pop off "cpu"
					if(!$info[0]) array_shift($info); // pop off blank space (if any)
					$this->user = $info[0];
					$this->nice = $info[1];
					$this->system = $info[2];
					$this->idle = $info[3];
//					$this->print_current();
					return;
				}
			}
		}
	}
	function store_load() {
		$this->last_user = $this->user;
		$this->last_nice = $this->nice;
		$this->last_system = $this->system;
		$this->last_idle = $this->idle;
	}
	function save_load() {
		$this->store_load();
		$fp = @fopen(TEMP_PATH."cpuinfo.tmp","w");
		if ($fp) {
			fwrite($fp,time()."\n");
			fwrite($fp,$this->last_user." ".$this->last_nice." ".$this->last_system." ".$this->last_idle."\n");
			fwrite($fp,$this->load["user"]." ".$this->load["nice"]." ".$this->load["system"]." ".$this->load["idle"]." ".$this->load["cpu"]."\n");
			fclose($fp);
		}
	}
	function load_load() {
		$fp = @fopen(TEMP_PATH."cpuinfo.tmp","r");
		if ($fp) {
			$lines = explode("\n",fread($fp,1024));
			$this->lasttime = $lines[0];
			list($this->last_user,$this->last_nice,$this->last_system,$this->last_idle) = explode(" ",$lines[1]);
			list($this->load["user"],$this->load["nice"],$this->load["system"],$this->load["idle"],$this->load["cpu"]) = explode(" ",$lines[2]);
			fclose($fp);
		} else {
			$this->lasttime = time() - 60;
			$this->last_user = $this->last_nice = $this->last_system = $this->last_idle = 0;
			$this->user = $this->nice = $this->system = $this->idle = 0;
		}
	}
	function calculate_load() {
		//$this->print_current();
		$d_user = $this->user - $this->last_user;
		$d_nice = $this->nice - $this->last_nice;
		$d_system = $this->system - $this->last_system;
		$d_idle = $this->idle - $this->last_idle;
		//printf("Delta - User: %f  Nice: %f  System: %f  Idle: %f<br/>",$d_user,$d_nice,$d_system,$d_idle);
		$total=$d_user+$d_nice+$d_system+$d_idle;
		if ($total<1) $total=1;
		$scale = 100.0/$total;
		$cpu_load = ($d_user+$d_nice+$d_system)*$scale;
		$this->load["user"] = $d_user*$scale;
		$this->load["nice"] = $d_nice*$scale;
		$this->load["system"] = $d_system*$scale;
		$this->load["idle"] = $d_idle*$scale;
		$this->load["cpu"] = ($d_user+$d_nice+$d_system)*$scale;
	}
	function print_current() {
		printf("Current load tickers - User: %f  Nice: %f  System: %f  Idle: %f<br/>",
			$this->user,
			$this->nice,
			$this->system,
			$this->idle
		);
	}
	function print_load() {
		printf("User: %.1f%%  Nice: %.1f%%  System: %.1f%%  Idle: %.1f%%  Load: %.1f%%<br/>",
			$this->load["user"],
			$this->load["nice"],
			$this->load["system"],
			$this->load["idle"],
			$this->load["cpu"]
		);
	}
	function get_load($fastest_sample=4) {
		$this->load_load();
		$this->cached = (time()-$this->lasttime);
		if ($this->cached>=$fastest_sample) {
			$this->check_load(); 
			$this->calculate_load();
			$this->save_load();
		}
	}
}

$cpuload = new CPULoad();
$cpuload->get_load();
$cpuload->print_load();
$CPULOAD = round($cpuload->load["cpu"],3);

echo "<br/>The average CPU load is: ".$CPULOAD."%\n";
echo "<div class='progress'>\n";
echo "<div style='width: ".$CPULOAD."%; background-color: rgb(0, 204, 0);' id='serviceload'>\n";
echo " </div>\n";
echo "</div>\n";
?>
</pre>
</blockquote>
