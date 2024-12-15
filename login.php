<?php
session_start();
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

$koneksi = new mysqli("localhost", "root", "", "webinventory");

if (isset($_POST['login'])) {
	$username = mysqli_real_escape_string($koneksi, $_POST['username']);
	$password = md5($_POST['password']);

	$sql = $koneksi->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
	$ketemu = $sql->num_rows;
	$data = $sql->fetch_assoc();

	if ($ketemu >= 1) {
		$_SESSION['username'] = $data['username'];
		$_SESSION['level'] = $data['level'];
		header("location:index.php");
		exit();
	} else {
		echo '<center><div class="alert alert-danger">Upss...!!! Login gagal. Silakan Coba Kembali</div></center>';
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="favicon.png" type="image/x-icon">
	<title>Login Sistem Inventory</title>

	<!-- Bootstrap -->
	<link href="login/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			background: linear-gradient(135deg, #d12424 0%, #101010 100%);
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

		.container {
			width: 100%;
			padding: 15px;
		}

		.row {
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
		}

		.login {
			background: rgb(31 18 18 / 95%);
			width: 100%;
			max-width: 400px;
			padding: 40px;
			border-radius: 20px;
			box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
		}

		.login h2 {
			color: #d12424;
			text-align: center;
			font-size: 28px;
			margin-bottom: 30px;
			font-weight: 600;
		}

		/* Logo styling */
		.login-logo {
			text-align: center;
			margin-bottom: 30px;
		}

		.login-logo img {
			width: 100px;
			/* Adjust the width of the logo */
			height: auto;
		}

		.form-group {
			margin-bottom: 25px;
			position: relative;
		}

		.form-control {
			height: 50px;
			padding: 10px 20px;
			font-size: 16px;
			color: #333;
			background: #f8f9fa;
			border: 2px solid transparent;
			border-radius: 10px;
			transition: all 0.3s ease;
		}

		.form-control:focus {
			outline: none;
			border-color: #d12424;
			background: white;
			box-shadow: 0 0 0 3px rgba(209, 36, 36, 0.1);
		}

		.btn-primary {
			background: #d12424;
			border: none;
			height: 50px;
			border-radius: 10px;
			font-size: 18px;
			font-weight: 600;
			letter-spacing: 2px;
			text-transform: uppercase;
			transition: all 0.5s ease;
		}

		.btn-primary:hover {
			background: #b01e1e;
			transform: translateY(-2px);
			box-shadow: 0 5px 15px rgba(209, 36, 36, 0.3);
		}

		.alert {
			border-radius: 10px;
			margin-top: 20px;
			padding: 15px;
			text-align: center;
			font-size: 14px;
		}

		.alert-danger {
			background: #ffe6e6;
			color: #d12424;
			border: 1px solid #ffcccc;
		}

		/* Input icons */
		.form-group i {
			position: absolute;
			left: 15px;
			top: 17px;
			color: #d12424;
		}

		.form-control {
			padding-left: 45px;
		}

		/* Animation */
		@keyframes fadeIn {
			from {
				opacity: 0;
				transform: translateY(-20px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.login {
			animation: fadeIn 0.5s ease-out;
		}
	</style>
</head>

<body>

	<div class="container">
		<div class="row">
			<div class="center">
				<div class="login">

					<!-- Logo Section -->
					<div class="login-logo">
						<img src="Samco.png" alt="Logo"> <!-- Replace 'logo.png' with the actual logo file -->
					</div>

					<form role="form" action="" method="post">
						<h2> Log In</h2>
						<br>
						<div class="form-group">
							<input type="text" name="username" class="form-control" placeholder="Username" required
								autofocus />
						</div>
						<div class="form-group">
							<input type="password" name="password" class="form-control" placeholder="Password" required
								autofocus />
						</div>
						<div class="form-group">
							<input type="submit" name="login" class="btn btn-primary btn-block" value="Log in" />
						</div>
						<br>
					</form>

				</div>

			</div>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>