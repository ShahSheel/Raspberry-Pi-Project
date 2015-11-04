<?php
//Start session
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

			 <head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
	<link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME ICONS  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
		 <link rel="icon" href="assets/img/favicon.ico">
	</head>
			<div class="border start">
				<form action="login.php" method="post" name="login">
				<div class="navbar navbar-inverse set-radius-zero">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".headtitle-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.html">
					<b>RPI:<b> 
					<br>
					&nbsp&nbsp&nbsp&nbsp <b>Control Panel </b>
                </a>

            </div>

            <div class="left-div">
                <div class="user-settings-wrapper">
                    <ul class="nav">

               
            </div>
        </div>
        </div>
    </div>
    <!-- LOGO HEADER END-->
   
    <!-- MENU SECTION END-->
   
	<div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="page-head-line">Please Login To Enter </h4>

                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                     <label>Enter Username: </label>
                        <input name = "username" type="text" class="form-control" />
                        <label>Enter Password :  </label>
                        <input  name = "pass" type="password" class="form-control" />
                        <hr />
                        <button type = "submit" href="home.html" class="btn btn-info"><span class="glyphicon glyphicon-user"></span> &nbsp;Log Me In </button>&nbsp;
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info">
                        When you login into this site you are able access some features such as:
                        <br />
                       
                        <ul>
                            <li>
                                Taking images from your raspberry pi
                            </li>
                            <li>
                               Viewing these pictures
                            </li>
                            <li>
                                Enabling and Disabling your camera
                            </li>
                            <li>
                                Much much more!
                            </li>
                        </ul>
                       
                    </div>
                    
                </div>

            </div>
        </div>
    </div>

    <!-- CONTENT-WRAPPER SECTION END-->
    
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.11.1.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>

				</form>
			</div>
			
	</div>
</body>
</html>

