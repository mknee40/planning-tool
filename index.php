<?php session_start();  define("IN_APP", true);
require("config.php");

if(isset($_SESSION["agency"])){
	header("location: plan2.php");
}
	if(isset($_POST['email'])){
	
		$email = $_POST['email'];
		$password = $_POST['password'];

		/*
			Check credentials and create session. 
		*/
		if(isset($logins[$email]))
		{
			if($logins[$email]["password"] == $password){
					foreach($logins[$email] as $key=>$session){
						if($session == "password") continue;
						$_SESSION[$key] = $session;
					}
					$_SESSION["agency"] = $email;
					header("location: plan2.php");
			}
			else {
				$message = "Password Incorrect.";
			}
		}
		else{
			$message = "Username not recognised.";
		}


	}
	
function setLoginCookie($email){
	setcookie("r1planningcookie", $email, time() + (86400 * 30), "/"); // 86400 = 1 day
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Radiumone Planning</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

		<link href="css/basestyle.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body id="homepage">

    <div class="container">

      <form class="form-signin" action="index.php" method="post">
		<?php if(isset($message)) : ?>
			<div style="color:red;text-align:center;font-size:18px;font-weight:bold;background:white;padding: 0.3em 0;"><?php echo $message; ?></div>
		<?php endif; ?>
        <label for="inputEmail" class="sr-only">Username</label>
        <input type="text" id="inputEmail" name="email" class="form-control" placeholder="Username" required autofocus>
		<br />
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">LOGIN</button>
      </form>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
