<?
    session_start();
    
    require('config.php');
	
	if($_SESSION['adm_login'] != NULL and $_SESSION['adm_pass'] != NULL){
		if(preg_match('|^[A-Z0-9]+$|i', $_SESSION['adm_login'])){
			$users = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `settings` WHERE `login`='".$_SESSION['adm_login']."';"));
		
			if($_SESSION['adm_pass'] == $users['pass']){
			}else{
				header('Location: logout.php');
				die();
			}
		}else{
			header('Location: logout.php');
			die();
		}
	}else{
		header('Location: login.php');
        die();
	}
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($_POST['change'] == "yes"){
            if($_POST['pass'] != NULL and $_POST['pass1'] != NULL and $_POST['pass2']){
                if(md5($_POST['pass']) == $users['pass']){
                    if($_POST['pass1'] == $_POST['pass2']){
                        mysqli_query($con, 'UPDATE `settings` SET `pass`="'.md5($_POST['pass1']).'" WHERE `login`="'.$_SESSION['adm_login'].'";');
                        $_SESSION['adm_pass'] = md5($_POST['pass1']);
                        $succ = "Пароль успешно изменён!";
                    }else{
                        $err = "Пароли не совподают!";
                    }
                }else{
                    $err = "Старый пароль неверный! Попробуйте ещё раз!";
                }
            }else{
                $err = "Все поля должны быть заполнены!";
            }
        }
        
        if($_POST['id'] == "add" and $users['admin'] == 1){
            if($_POST['login'] != "" and $_POST['pass'] != "" and $_POST['prava'] != ""){
                if($_POST['prava'] >= 0 and $_POST['prava'] <= 1){
                    if(preg_match('|^[A-Z0-9]+$|i', $_POST['login'])){
                        if(strlen($_POST['login']) >= 4 and strlen($_POST['login']) <= 16){
                            $user2 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `settings` WHERE `login`='".$_POST['login']."';"));
                            
                            if($user2['id'] == ""){
                                mysqli_query($con, 'INSERT INTO `settings` SET `login`="'.$_POST['login'].'", `pass`="'.md5($_POST['pass']).'", `admin`="'.$_POST['prava'].'";');
                                $succ = "Пользователь успешно добавлен!";
                            }else{
                                $err = "Такой логин уже занят!";
                            }
                        }else{
                            $err = "Логин должен быть больше 3 и меньше 17 символов!";
                        }
                    }else{
                        $err = "Логин может содержать только латиницу и цифры!";
                    }
                }else{
                    $err = "Выберите права пользователя!";
                }
            }else{
                $err = "Все поля должны быть заполнены!";
            }
        }
        
        if(preg_match('|^[0-9]+$|i', $_POST['id']) and $users['admin'] == 1){
	        $usr = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `settings` WHERE `id`='".$_POST['id']."';"));
	       
	        if($usr['id'] != NULL){
	            if($_POST['login'] != $usr['login']){
	                if(preg_match('|^[A-Z0-9]+$|i', $_POST['login'])){
	                    if(strlen($_POST['login']) >= 4 and strlen($_POST['login']) <= 16){
	                        $user2 = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `settings` WHERE `login`='".$_POST['login']."';"));
                            
                            if($user2['id'] == ""){
                                mysqli_query($con, 'UPDATE `settings` SET `login`="'.$_POST['login'].'" WHERE `id`="'.$_POST['id'].'";');
	                            $succ = "Пользователь успешно изменён!";
                            }else{
                                $err = "Такой логин уже занят!";
                            }
	                    }else{
	                        $err = "Логин должен быть больше 3 и меньше 17 символов!";
	                    }
	                }else{
	                    $err = "Логин может содержать только латиницу и цифры!";
	                }
	            }
	           
	            if($_POST['pass'] != ""){
	                mysqli_query($con, 'UPDATE `settings` SET `pass`="'.md5($_POST['pass']).'" WHERE `id`="'.$_POST['id'].'";');
	                $succ = "Пользователь успешно изменён!";
	            }
	            
	            if($_POST['prava'] != $usr['admin']){
	                if($_POST['prava'] >= 0 and $_POST['prava'] <= 1){
	                    mysqli_query($con, 'UPDATE `settings` SET `admin`="'.$_POST['prava'].'" WHERE `id`="'.$_POST['id'].'";');
	                    $succ = "Пользователь успешно изменён!";
	                }else{
	                    $err = "Выберите права пользователя!";
	                }
	            }
	        }
	    }
    }
?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>Admin Panel</title>

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css">
		
		<link rel="stylesheet" href="assets/vendor/codemirror/lib/codemirror.css" />
		<link rel="stylesheet" href="assets/vendor/codemirror/theme/monokai.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/select2/select2.css" />
		<link rel="stylesheet" href="assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

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
		<section class="body">

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="index.php" class="logo">
					    <img src="assets/images/logo-default.png" width="75" height="35">
					</a>
					<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>
			
				<!-- start: search & user box -->
				<div class="header-right">
			
			
					<span class="separator"></span>
					
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<img src="assets/images/!logged-user.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
							</figure>
							<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
								<span class="name"><?=htmlspecialchars($_SESSION['adm_login'])?></span>
								<span class="role"><? if($users['admin'] == 1){ ?>Администратор<? }else{ ?>Пользователь<? } ?></span>
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="logout.php"><i class="fa fa-power-off"></i> Выйти</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">
				
					<div class="sidebar-header">
						<div class="sidebar-title">
							Панель
						</div>
						<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>
				
					<div class="nano">
						<div class="nano-content">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-main">
									<li class="nav">
										<a href="index.php">
											<i class="fa fa-comment" aria-hidden="true"></i>
											<span>Потенциальные</span>
										</a>
									</li>
									
									<li class="nav">
										<a href="sent.php">
											<i class="fa fa-user-plus" aria-hidden="true"></i>
											<span>Отправленные</span>
										</a>
									</li>
                                  
                                  	<li class="nav">
										<a href="works.php">
											<i class="fa fa-user" aria-hidden="true"></i>
											<span>В работе</span>
										</a>
									</li>
									
									<? if($users['admin'] == 1){ ?>
									<li class="nav-active">
										<a href="settings.php">
											<i class="fa fa-users" aria-hidden="true"></i>
											<span>Пользователи</span>
										</a>
									</li>
                                    <? }else{ ?>
                                    <li class="nav-active">
										<a href="settings.php">
											<i class="fa fa-cog" aria-hidden="true"></i>
											<span>Настройки</span>
										</a>
									</li>
                                    <? } ?>
                                    
								</ul>
							</nav>
				
							<hr class="separator" />
				
							
				
							<hr class="separator" />
				
						</div>
				
					</div>
				
				</aside>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
				    <? if($users['admin'] == 1){ ?>
					<header class="page-header">
						<h2>Пользователи</h2>
					
						<div style="margin-right:20px" class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="index.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Пользователи</span></li>
							</ol>
						</div>
					</header>
					<? }else{ ?>
					<header class="page-header">
						<h2>Настройки</h2>
					
						<div style="margin-right:20px" class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="index.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Настройки</span></li>
							</ol>
						</div>
					</header>
					<? } ?>
					
					<!-- start: page -->
					        <?if($err != ""){echo '<div class="alert alert-danger">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<strong>Ошибка!</strong> '.$err.'
									</div>';}?>
							<?if($succ != ""){echo '<div class="alert alert-success">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										'.$succ.'
									</div>';}?>
									
					<? if($users['admin'] == 1){ ?>
					<!-- start: page -->
                    	<section class="panel">
							<div class="panel-body">
							    <div id="sar" class="col-md-1" style="padding-left: 0px; float: left; display: none;">
   								    <a href="javascript:comm('del');" onclick="return confirm('Вы действительно хотите удалить выбранных работников?');" class="btn btn-default" style="color: #0088cc; padding: 5px 12px;"><i style="font-size: 2rem" class="fa fa-trash"></i></a>
   								</div>
							    <div style="float: right;">
							        <a class="mb-1 mt-1 mr-1 modal-with-move-anim ws-normal" href="#modalAnim2"><i style="font-size: 2rem" class="fa fa-user-plus"></i></a>
							    </div>
							    <br /><br />
							    <style>
                                .wrapper1, .wrapper2{width: 100%; border: none 0px RED; overflow-x: scroll; overflow-y:hidden; }
                                .wrapper1{height: 100%; }
                                .wrapper2{height: 100%; }
                                .div1 {width: 920px; height: 1px; }
                                .div2 {width: 1455px; height: 100%; overflow: auto;}
							    </style>
								<table class="table table-bordered table-striped mb-none" id="exp">
									<thead>
										<tr>
										    <th width="50"><div class="col-sm-2 checkbox-custom"><input type="checkbox" id="checkboxExample3" onclick="javascript:allch();"><label for="checkboxExample3"></label></div></th>
											<th width="50">ID</th>
											<th width="100">Логин</th>
											<th width="100">Права</th>
											<th width="1">Действия</th>
										</tr>
									</thead>
									<tbody>
									    <?
									    $logs = mysqli_query($con, "SELECT * FROM `settings` ORDER BY `settings`.`id` ASC;");
                                        while ($result = mysqli_fetch_assoc($logs)) {
                                        ?>
                                            <? if($result['admin'] == 1){ ?>
                                            <tr class='warning'>
                                            <? }else{ ?>
                                            <tr class='GradeX'>
                                            <? } ?>
                                                <td><div class="col-sm-2 checkbox-custom"><input type="checkbox" id="ch<?=$result['id']?>" onclick="dll(this);"><label for="ch<?=$result['id']?>"></label></div></td>
                                                <td><?=$result['id']?></td>
                                                <td><?=$result['login']?></td>
			                                    <td>
			                                    <?
			                                    if($result['admin'] == 1){
			                                        echo "Администратор";
			                                    }else{
			                                        echo "Пользователь";
			                                    }
			                                    ?>
			                                    </td>
			                                    <td class='actions'>
			                                    <a href="#modalAnim" onclick="document.getElementById('id').value = '<?=$result['id']?>'; document.getElementById('login').value = '<?=$result['login']?>'; gg(<?=$result['admin']?>);" data-toggle="tooltip" data-trigger="hover" data-original-title="Редактировать" class="on-default pencil mb-1 mt-1 mr-1 modal-with-move-anim ws-normal"><i class="fa fa-pencil"></i></a>
												<a href="del_usr.php?id=<?=$result['id']?>" onclick="return confirm('Вы действительно хотите удалить пользователя?');" data-toggle="tooltip" data-trigger="hover" data-original-title="Удалить" class="on-default trash"><i class="fa fa-trash"></i></a>
			                                    </td>
			                                </tr>
                                        <? } ?>
									</tbody>
								</table>
								</div>
								</div>
							</div>
						</section>
						
						<script>
						    $(document).ready(function() {
		                        $('#exp').dataTable( {
		                                "lengthMenu": [[-1, 10, 25, 50, 100], ["Все", 10, 25, 50, 100]]
		                        } );
		                        
                                $(function(){
                                    $(".wrapper1").scroll(function(){
                                        $(".wrapper2")
                                            .scrollLeft($(".wrapper1").scrollLeft());
                                    });
                                    $(".wrapper2").scroll(function(){
                                        $(".wrapper1")
                                            .scrollLeft($(".wrapper2").scrollLeft());
                                    });
                                });
		                    } );
		                </script>
					<!-- end: page -->
					
			
			<script>
			                <?
                            $logsd = mysqli_query($con, "SELECT * FROM `settings`;");
                            $l = "";
                            while ($result = mysqli_fetch_assoc($logsd)) {
                                if($l == ""){
                                    $l = $result['id'];
                                }else{
                                    $l = $l.", ".$result['id'];
                                }
                            }
                            echo "var loga = [$l];";
                            ?>
			
			                function allch(){
                                for(var i = 0; i < loga.length; i++){
                                    try{
						            if(document.getElementById('checkboxExample3').checked == true){
						                document.getElementById('ch'+loga[i]).checked = true;
						                document.getElementById('sar').style = 'padding-left: 0px; float: left; display: inline-block;';
						                document.getElementById('sar2').style = 'padding-left: 0px; float: left; width: 48px; display: inline-block;';
						            }else{
						                document.getElementById('ch'+loga[i]).checked = false;
						                document.getElementById('sar').style = 'padding-left: 0px; float: left; display: none;';
						                document.getElementById('sar2').style = 'padding-left: 0px; float: left; display: none;';
						            }
                                    }catch(e){
                                    }
                                }
                            }
                            
                            function tre(){
                                var ret = [];
                                for(var i = 0; i < loga.length; i++){
                                    try{
						            if(document.getElementById('ch'+loga[i]).checked == true){
						                ret.push(loga[i]);
						            }
						            }catch(e){
						            }
                                }
                                return ret;
                            }
                            
                            function dll(t){
							    if(t.checked == true){
								    document.getElementById('sar').style = 'padding-left: 0px; float: left; display: inline-block;';
							    }else{
							        if(tre() == ""){
									    document.getElementById('sar').style = 'padding-left: 0px; float: left; display: none;';
								    }
							    }
						    }
						    
                            function b64DecodeUnicode(str) {
                                // Going backwards: from bytestream, to percent-encoding, to original string.
                                return decodeURIComponent(atob(str).split('').map(function(c) {
                                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                                }).join(''));
                            }
                            
                            function gg(s){
                                document.getElementById('s0').removeAttribute('selected');
                                document.getElementById('s1').removeAttribute('selected');
                                
                                document.getElementById('s'+s).setAttribute('selected', 'selected');
                            }
                            
                            function comm(com){
						        var m = 'no';
						        var m2 = [];
						        for(var i = 0; i < loga.length; i++){
						            try{
						            if(document.getElementById('ch'+loga[i]).checked == true){
						                m2.push(loga[i]);
						                var m = 'yes';
						            }
						            }catch(e){
						            }
						        }
						        
						        if(m == 'no'){
						            alert('Выберите хотя бы 1 пользователя!');
						        }else{
						            $.ajax({
                                        type: 'GET',
                                        url: 'del_usr.php?m=yes&id='+m2,
                                        data: {},
                                        success: function(data) {
                                            if(data == 'ok'){
						                        window.location.href=window.location.href;
                                            }
                                        }
                                    });
						        }
						    }
						    
						    function comm2(com){
						        var m = 'no';
						        var m2 = [];
						        for(var i = 0; i < loga.length; i++){
						            try{
						            if(document.getElementById('ch'+loga[i]).checked == true){
						                m2.push(loga[i]);
						                var m = 'yes';
						            }
						            }catch(e){
						            }
						        }
						        
						        if(m == 'no'){
						            alert('Выберите хотя бы 1 пользователя!');
						        }else{
						            document.getElementById('id2').value = m2;
						        }
						    }
			</script>
			      <? }else{ ?>
					
					
					    <div class="row">
							<div class="col-xs-12">
								<section class="panel">
									<header class="panel-heading">
										<h2 class="panel-title">Изменение пароля</h2>
									</header>
									<div class="panel-body">
										<form class="form-horizontal form-bordered" method="POST">
										<input type="hidden" name="change" value="yes">
									<div class="form-group">
								        <label class="col-md-3 control-label" for="textareaDefault">Введите текущий пароль</label>
									    <div class="col-md-6">
									        <input class="form-control" type="password" name="pass" value="">
									    </div>
									</div>
									<div class="form-group">
									    <label class="col-md-3 control-label" for="textareaDefault">Введите новый пароль</label>
									    <div class="col-md-6">
									        <input class="form-control" type="text" name="pass1" value="">
									    </div>
									</div>
									<div class="form-group">
									    <label class="col-md-3 control-label" for="textareaDefault">Повторите новый пароль</label>
									    <div class="col-md-6">
									        <input class="form-control" type="text" name="pass2" value="">
									    </div>
									</div>
										    <div class="form-group">
												<button type="button" onclick="submit();" class="mb-xs mt-xs mr-xs btn btn-primary btn-block">Изменить</button>
											</div>
										</form>
									</div>
								</section>
							</div>
						</div>
					<!-- end: page -->
					
					<? } ?>
					
				</section>
			</div>

        <div id="modalAnim2" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
                <section class="panel">
	                <header class="panel-heading">
					    <h2 class="panel-title">Добавление пользователя:</h2>
	                </header>
	                <form method="POST">
	                    <input type="hidden" name="id" value="add">
	                    <div class="panel-body" style="border-radius: 0px;">
					        <div class="modal-wrapper">
						        <div class="modal-text">
							        <div class="form-group">
										<label class="col-md-3 control-label" for="textareaDefault">Логин</label>
									    <div class="col-md-12">
									        <input class="form-control" type="text" name="login" value="">
									    </div>
									</div>
									<div class="form-group">
										<label class="col-md-12 control-label" for="textareaDefault">Пароль</label>
									    <div class="col-md-12">
									        <input class="form-control" type="text" name="pass" value="">
									    </div>
									</div>
									<div class="form-group col-md-12">
										<label class="col-md-3 control-label" for="textareaDefault" style="padding-left: 0px;">Права</label>
									    <select class="form-control col-mb-6" name="prava">
									        <option value="0" selected>Пользователь</option>
											<option value="1">Администратор</option>
										</select>
									</div>
									<div class="form-group"></div>
						        </div>
					        </div>
	                    </div>
	                    <footer class="panel-footer">
					        <div class="row">
						        <div class="col-md-12 text-right">
						            <button class="btn btn-primary modal-confirm" onclick="submit();">Добавить</button>
				                    <button class="btn btn-default modal-dismiss">Отмена</button>
						        </div>
					        </div>
	                    </footer>
	                </form>
	            </section>
			</div>
			
			<div id="modalAnim" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
                <section class="panel">
	                <header class="panel-heading">
					    <h2 class="panel-title">Редактирование работника:</h2>
	                </header>
	                <form method="POST">
	                    <input type="hidden" name="id" value="" id="id">
	                    <div class="panel-body" style="border-radius: 0px;">
					        <div class="modal-wrapper">
						        <div class="modal-text">
							        <div class="form-group">
										<label class="col-md-3 control-label" for="textareaDefault">Логин</label>
									    <div class="col-md-12">
									        <input class="form-control" type="text" name="login" value="" id="login">
									    </div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="textareaDefault">Пароль</label>
									    <div class="col-md-12">
									        <input class="form-control" type="text" name="pass" value="" id="pass" placeholder="(Оставьте поле пустым если не хотите изменить)">
									    </div>
									</div>
									<div class="form-group col-md-12">
										<label class="col-md-3 control-label" for="textareaDefault" style="padding-left: 0px;">Права</label>
									    <select class="form-control col-mb-6" name="prava">
									        <option value="0" id="s0">Пользователь</option>
											<option value="1" id="s1">Администратор</option>
										</select>
									</div>
									<div class="form-group"></div>
						        </div>
					        </div>
	                    </div>
	                    <footer class="panel-footer">
					        <div class="row">
						        <div class="col-md-12 text-right">
						            <button class="btn btn-primary modal-confirm" onclick="submit();">Редактировать</button>
				                    <button class="btn btn-default modal-dismiss">Отмена</button>
						        </div>
					        </div>
	                    </footer>
	                </form>
	            </section>
			</div>
			
		</section>

		<!-- Vendor -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<script src="assets/vendor/codemirror/lib/codemirror.js"></script>
		<script src="assets/vendor/codemirror/addon/selection/active-line.js"></script>
		<script src="assets/vendor/codemirror/addon/edit/matchbrackets.js"></script>
		<script src="assets/vendor/codemirror/mode/javascript/javascript.js"></script>
		<script src="assets/vendor/codemirror/mode/xml/xml.js"></script>
		<script src="assets/vendor/codemirror/mode/htmlmixed/htmlmixed.js"></script>
		<script src="assets/vendor/codemirror/mode/css/css.js"></script>
		<script src="assets/vendor/ios7-switch/ios7-switch.js"></script>
		
		<!-- Specific Page Vendor -->
		<script src="assets/vendor/select2/select2.js"></script>
		<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
		<script src="assets/vendor/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>


		<!-- Examples -->
		<script src="assets/javascripts/tables/examples.datatables.editable.js"></script>
		<script src="assets/javascripts/ui-elements/examples.modals.js"></script>
	</body>
</html>