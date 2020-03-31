<?
	session_start();

    require('config.php');

	if($_SESSION['adm_login'] != NULL and $_SESSION['adm_pass'] != NULL){
		if(preg_match('|^[A-Z0-9]+$|i', $_SESSION['adm_login'])){
			$users = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `settings` WHERE `login`='".$_SESSION['adm_login']."';"));
		
			if($_SESSION['adm_pass'] == $users['pass']){
				header('Location: index.php');
				die();
			}else{
				header('Location: logout.php');
				die();
			}
		}else{
			header('Location: logout.php');
			die();
		}
	}
	
	if($_POST['login'] != NULL and $_POST['pass'] != NULL){
		if(preg_match('|^[A-Z0-9]+$|i', $_POST['login'])){
			$users = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `settings` WHERE `login`='".$_POST['login']."';"));
			
			if(md5($_POST['pass']) == $users['pass']){
				$_SESSION['adm_login'] = $_POST['login'];
				$_SESSION['adm_pass'] = md5($_POST['pass']);
				header('Location: index.php');
				die();
			}else{
				$err = 1;
			}
		}else{
			$err = 1;
		}
	}
?>
<!DOCTYPE html>
<html class="fixed">
	<head>
        
		<!-- Basic -->
		<meta charset="UTF-8">
        <title>Admin Log In</title>
        
		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />

		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>
	</head>
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<a href="" class="logo pull-left">
					
				</a>

				<div class="panel panel-sign">
					<div class="panel-title-sign mt-xl text-right">
						<h2 class="title text-uppercase text-weight-bold m-none"><i class="fa fa-user mr-xs"></i> Войти</h2>
					</div>
					<div class="panel-body">
					    <? if($err==1){echo '<p style="color:red"><b>Неправильный логин или пароль! Попробуйте ещё раз!</b></p>';}; ?>
						<form action="" method="post">
							<div class="form-group mb-lg">
								<label>Логин</label>
								<div class="input-group input-group-icon">
									<input name="login" type="text" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="form-group mb-lg">
								<div class="clearfix">
									<label class="pull-left">Пароль</label>
								</div>
								<div class="input-group input-group-icon">
									<input name="pass" type="password" class="form-control input-lg" />
									<span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
								</div>
							</div>

							<div class="row">
								<div style="margin-left:290px" class="col-sm-4 text-right">
									<button id="ryiaf" type="submit" class="btn btn-primary hidden-xs" onclick="document.getElementById('ryiaf').innerHTML='Загрузка..';">Подвердить</button>
									<button type="submit" class="btn btn-primary btn-block btn-lg visible-xs mt-lg">Подтвердить</button>
								</div>
							</div>

						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-md mb-md">&copy; Copyright 2018. All Rights Reserved.</p>
			</div>
		</section>
		<!-- end: page -->

		<!-- Vendor -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>

	</body>
</html>