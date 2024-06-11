<?php
include("admin/conn.php");
session_start();

if (isset($_SESSION['username'])) {
	header('Location: admin/dashboard.php');
	exit();
}

if (isset($_POST['submit'])) {
	$username = mysqli_real_escape_string($db, $_POST['username']);
	$password = $_POST['password'];


	$q1 = mysqli_query($db, "SELECT * FROM `user` WHERE `username` = '$username'");
	if (mysqli_num_rows($q1) > 0) {
		$paassword2 = '';
		foreach ($q1 as $q) {
			$paassword2 = $q['password'];
		}
		if (password_verify($password, $paassword2)) {
			$_SESSION['username'] = $username;
			header('Location: admin/dashboard.php');
			exit();
		} else {
			$error = true;
		}
	} else {
		$error1 = true;
	}

	// echo $username;
	// echo $password;
	// die();



	// $query = "SELECT * FROM `user` where username='$username' AND password='$password'";
	// $sql = mysqli_query($db, $query);
	// $data = mysqli_fetch_assoc($sql);

	// if (empty($username)) {
	//     header("Location:auth.php?error=Name is required&$data");
	// } else if (empty($password)) {
	//     header("Location:auth.php?error=Password is required&$data");
	// } else if ($data) {
	//     header("Location:dashboard.php");
	// } else {
	//     header("Location:auth.php?error=Incorrect username or password&$data");
	// }
}
?>


<!DOCTYPE html>

<html>

<head>
	<title>Amanah Deco. | Login</title>
	<link rel="shortcut icon" href="favicon.png">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="css/stylelogin.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
	<script src="../js/jquery-3.7.1.js"></script>
</head>

<body>
	<div class="container-fluid">
		<div class="row ">
			<!-- IMAGE CONTAINER BEGIN -->
			<div class="col-lg-6 col-md-6 d-none d-md-block infinity-image-container">
				<img src="images/canva2.png" alt="">
			</div>
			<!-- IMAGE CONTAINER END -->

			<!-- FORM CONTAINER BEGIN -->
			<div class="col-lg-6 col-md-6 infinity-form-container">
				<div class="col-lg-9 col-md-12 col-sm-9 col-xs-12 infinity-form">
					<!-- Company Logo -->
					<div class="text-center mb-3 mt-5">
						<img src="images/user.svg" width="150px">
					</div>
					<div class="text-center mb-4">
						<h4>Login into your account</h4>
					</div>
					<!-- Form -->
					<form class="px-3" action="" method="post">
						<!-- Input Box -->
						<?php if (isset($error)) { ?>
							<div class="alert alert-danger" role="alert">
								Incorrect Username or Password
							</div>
						<?php } else if (isset($error1)) { ?>
							<div class="alert alert-danger" role="alert">
								Isi Semua bidang
							</div>
						<?php } ?>
						<div class="form-input">

							<span><i class="fa fa-user"></i></span>
							<!-- <input type="text" name="" placeholder="Email Address" tabindex="10"required> -->
							<input type="text" name="username" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Username" require>

						</div>
						<div class="form-input">
							<span><i class="fa fa-lock"></i></span>
							<input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" require>
						</div>
						<div class="row mb-3">
							<!-- Remember Checkbox -->
							<div class="col-auto d-flex align-items-center">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="cb1">
									<label class="custom-control-label text-white" for="cb1">Remember me</label>
								</div>
							</div>
						</div>
						<!-- Login Button -->
						<div class="mb-3">
							<button type="submit" name="submit" class="btn btn-block">Login</button>
						</div>
						<!-- <div class="text-right ">
			        <a href="reset.html" class="forget-link">Forgot password?</a>
			      </div> -->
						<div class="text-center mb-2">
							<div class="text-center mb-2 text-white">or login with</div>

							<!-- Facebook Button -->
							<a href="" class="btn btn-social btn-facebook">facebook</a>

							<!-- Google Button -->
							<a href="" class="btn btn-social btn-google">google</a>

							<!-- Twitter Button -->
							<a href="" class="btn btn-social btn-twitter">twitter</a>
						</div>
						<!-- <div class="text-center mb-5 text-white">Don't have an account? 
							<a class="register-link" href="register.html">Register here</a>
			     	</div> -->
					</form>
				</div>
			</div>
			<!-- FORM CONTAINER END -->
		</div>
	</div>
	<script src="../js/bootstrap.bundle.min.js"></script>
</body>

</html>