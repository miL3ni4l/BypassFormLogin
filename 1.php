<?php
$conn = mysqli_connect("localhost", "root", "sqlidb") or exit(); //connection to database

if ($conn->connect_error) { //check connection
    die("Connection failed: " . $conn->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
	error_log(print_r($_REQUEST, true)); //show post data in "Run with built-in web server"

	$username = $_POST["user"];
	$password = $_POST["pass"];

	$result = mysqli_query($conn,"SELECT *  FROM user WHERE user='" . $username . "' AND pass = '". $password."'");
	$row = mysqli_fetch_array($result);

	// START PATCH FOR SQLI
	// $stmt_login = mysqli_prepare($conn, "SELECT * from `user` WHERE `user`= ? and `pass`= ?");
	// mysqli_stmt_bind_param($stmt_login, "ss", $username, $password);
	// mysqli_stmt_execute($stmt_login);
	// $row = mysqli_stmt_fetch($stmt_login);
	// END PATCH FOR SQLI

	if($row) {
		$_SESSION['sqli'] = array($username,$password); // if data true then set session
	    echo "<script>alert('Congratulations! you have logged in!')</script>";
	} else {
	    echo "<script>alert('invalid login')</script>"; // else show error
	}
}

if (isset($_GET['logout'])) { // if get ?logout then unset session
	unset($_SESSION['sqli']);
	header('Location: ?');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<style type="text/css">
	body {
		padding-top: 150px;
	}
	.form-style-6{
		font: 95% Arial, Helvetica, sans-serif;
		max-width: 400px;
		margin: 10px auto;
		padding: 16px;
		background: #F7F7F7;
	}
	.form-style-6 h1{
		background: #43D1AF;
		padding: 20px 0;
		font-size: 140%;
		font-weight: 300;
		text-align: center;
		color: #fff;
		margin: -16px -16px 16px -16px;
	}
	.form-style-6 input[type="text"],
	.form-style-6 input[type="password"],
	.form-style-6 input[type="date"],
	.form-style-6 input[type="datetime"],
	.form-style-6 input[type="email"],
	.form-style-6 input[type="number"],
	.form-style-6 input[type="search"],
	.form-style-6 input[type="time"],
	.form-style-6 input[type="url"],
	.form-style-6 textarea,
	.form-style-6 select 
	{
		-webkit-transition: all 0.30s ease-in-out;
		-moz-transition: all 0.30s ease-in-out;
		-ms-transition: all 0.30s ease-in-out;
		-o-transition: all 0.30s ease-in-out;
		outline: none;
		box-sizing: border-box;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		width: 100%;
		background: #fff;
		margin-bottom: 4%;
		border: 1px solid #ccc;
		padding: 3%;
		color: #555;
		font: 95% Arial, Helvetica, sans-serif;
	}
	.form-style-6 input[type="text"]:focus,
	.form-style-6 input[type="password"]:focus,
	.form-style-6 input[type="date"]:focus,
	.form-style-6 input[type="datetime"]:focus,
	.form-style-6 input[type="email"]:focus,
	.form-style-6 input[type="number"]:focus,
	.form-style-6 input[type="search"]:focus,
	.form-style-6 input[type="time"]:focus,
	.form-style-6 input[type="url"]:focus,
	.form-style-6 textarea:focus,
	.form-style-6 select:focus
	{
		box-shadow: 0 0 5px #43D1AF;
		padding: 3%;
		border: 1px solid #43D1AF;
	}

	.form-style-6 input[type="submit"],
	.form-style-6 input[type="button"]{
		box-sizing: border-box;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		width: 100%;
		padding: 3%;
		background: #43D1AF;
		border-bottom: 2px solid #30C29E;
		border-top-style: none;
		border-right-style: none;
		border-left-style: none;	
		color: #fff;
	}
	.form-style-6 input[type="submit"]:hover,
	.form-style-6 input[type="button"]:hover{
		background: #2EBC99;
	}
	</style>
	<?php
	if (!isset($_SESSION['sqli'])) { // if session not set then show login form
		echo '
	<div class="form-style-6">
	<h1>Login Now</h1>
		<form action="" method="POST">
			<input type="text" name="user" placeholder="username" />
			<input type="password" name="pass" placeholder="*****" />
			<input type="submit" value="Login" />
		</form>
	</div>
	';
	}

	if (isset($_SESSION['sqli'])) { // if session set then show welcome page
		echo '
	<div class="form-style-6">
	<h1>Welcome To Administrator Page</h1>
		<form action="" method="GET">
		 	<input type="submit" value="Logout" name="logout" />
		</form>
	</div>
	';	}
	?>
</body>
</html>
