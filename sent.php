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
	
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($_POST['id'] == "red"){
            if($_POST['status'] != "" and $_POST['id2'] != ""){
                if($_POST['status'] >= 0 and $_POST['status'] <= 3){
                    if (preg_match("|^[0-9,]+$|i", $_POST['id2'])){
                        $exp = explode(",", $_POST['id2']);
                        foreach ($exp as $key => $value){
                            $usr = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `sent` WHERE `id`='".$value."';"));
	                        if($users['login'] != $usr['login']){
	                            if($users['admin'] != 1){
	                                header('Location: index.php');
                                    die();
	                            }
	                        }
                            mysqli_query($con, 'UPDATE `sent` SET `data_red`="'.date("d.m.Y H:i:s").'", `status`="'.$_POST['status'].'" WHERE `id`="'.$value.'";');
                            if($_POST['status'] == 2){
                                $uss = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `sent` WHERE `id`='".$value."';"));
                                mysqli_query($con, 'INSERT INTO `works` SET `data`="'.$uss['data'].'", `data_red`="'.date("d.m.Y H:i:s").'", `mail`="'.$uss['mail'].'", `name`="'.$uss['name'].'", `tel`="'.$uss['tel'].'", `country`="'.$uss['country'].'", `city`="'.$uss['city'].'", `comment`="'.$uss['comment'].'", `login`="'.$uss['login'].'";');
                                mysqli_query($con, "DELETE FROM `sent` WHERE `id`='".$value."';");
                            }
                        }
                        
                        $succ = "Работники успешно изменены!";
                    }else{
                        $err = "Выберите хотя бы 1 работника!";
                    }
                }else{
                    $err = "Выберите статус!";
                }
            }else{
                $err = "Все поля должны быть заполнены!";
            }
        }
        
        if(preg_match('|^[0-9]+$|i', $_POST['id'])){
	        $usr = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `sent` WHERE `id`='".$_POST['id']."';"));
	        
	        if($usr['id'] != NULL){
	            if($users['login'] != $usr['login']){
	                if($users['admin'] != 1){
	                    header('Location: index.php');
                        die();
	                }
	            }
	            
	            if($_POST['mail'] != base64_decode($usr['mail'])){
	                mysqli_query($con, 'UPDATE `sent` SET `data_red`="'.date("d.m.Y H:i:s").'", `mail`="'.base64_encode($_POST['mail']).'" WHERE `id`="'.$_POST['id'].'";');
	                $succ = "Работник успешно изменён!";
	            }
	           
	            if($_POST['name'] != base64_decode($usr['name'])){
	                mysqli_query($con, 'UPDATE `sent` SET `data_red`="'.date("d.m.Y H:i:s").'", `name`="'.base64_encode($_POST['name']).'" WHERE `id`="'.$_POST['id'].'";');
	                $succ = "Работник успешно изменён!";
	            }
	            
	            if($_POST['tel'] != base64_decode($usr['tel'])){
	                mysqli_query($con, 'UPDATE `sent` SET `data_red`="'.date("d.m.Y H:i:s").'", `tel`="'.base64_encode($_POST['tel']).'" WHERE `id`="'.$_POST['id'].'";');
	                $succ = "Работник успешно изменён!";
	            }
	            
	            if($_POST['country'] != base64_decode($usr['country'])){
	                mysqli_query($con, 'UPDATE `sent` SET `data_red`="'.date("d.m.Y H:i:s").'", `country`="'.base64_encode($_POST['country']).'" WHERE `id`="'.$_POST['id'].'";');
	                $succ = "Работник успешно изменён!";
	            }
	            
	            if($_POST['city'] != base64_decode($usr['city'])){
	                mysqli_query($con, 'UPDATE `sent` SET `data_red`="'.date("d.m.Y H:i:s").'", `city`="'.base64_encode($_POST['city']).'" WHERE `id`="'.$_POST['id'].'";');
	                $succ = "Работник успешно изменён!";
	            }
	            
	            if($_POST['comm'] != base64_decode($usr['comment'])){
	                mysqli_query($con, 'UPDATE `sent` SET `data_red`="'.date("d.m.Y H:i:s").'", `comment`="'.base64_encode($_POST['comm']).'" WHERE `id`="'.$_POST['id'].'";');
	                $succ = "Работник успешно изменён!";
	            }
	            
	            if($_POST['status'] != $usr['status']){
	                if($_POST['status'] >= 0 and $_POST['status'] <= 3){
	                    mysqli_query($con, 'UPDATE `sent` SET `data_red`="'.date("d.m.Y H:i:s").'", `status`="'.$_POST['status'].'" WHERE `id`="'.$_POST['id'].'";');
	                    
	                    if($_POST['status'] == 2){
                            mysqli_query($con, 'INSERT INTO `works` SET `data`="'.$usr['data'].'", `data_red`="'.date("d.m.Y H:i:s").'", `mail`="'.base64_encode($_POST['mail']).'", `name`="'.base64_encode($_POST['name']).'", `tel`="'.base64_encode($_POST['tel']).'", `country`="'.base64_encode($_POST['country']).'", `city`="'.base64_encode($_POST['city']).'", `comment`="'.base64_encode($_POST['comm']).'", `login`="'.$usr['login'].'";');
                            mysqli_query($con, "DELETE FROM `sent` WHERE `id`='".$_POST['id']."';");
	                    }
                        
	                    $succ = "Работник успешно изменён!";
	                }else{
	                    $err = "Выберите статус!";
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
									
									<li class="nav-active">
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
									<li class="nav">
										<a href="settings.php">
											<i class="fa fa-users" aria-hidden="true"></i>
											<span>Пользователи</span>
										</a>
									</li>
                                    <? }else{ ?>
                                    <li class="nav">
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
					<header class="page-header">
						<h2>Отправленные контракты</h2>
					
						<div style="margin-right:20px" class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="index.php">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Отправленные контракты</span></li>
							</ol>
						</div>
					</header>
                    
                            <?if($err != ""){echo '<div class="alert alert-danger">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<strong>Ошибка!</strong> '.$err.'
									</div>';}?>
							<?if($succ != ""){echo '<div class="alert alert-success">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										'.$succ.'
									</div>';}?>
                    
                    <div class="row">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-12 col-lg-6 col-xl-3">
									<section class="panel panel-featured-left panel-featured-primary">
										<div class="panel-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-primary">
														<i class="fa fa-comment"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Потенциальных работников</h4>
														<div class="info">
															<? if($users['admin'] == 1){ ?>
															<strong class="amount"><?=mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM potential"))[0]?></strong>
															<? }else{ ?>
															<strong class="amount"><?=mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM potential WHERE login='".$users['login']."'"))[0] + mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM potential WHERE login=''"))[0]?></strong>
															<? } ?>
														</div>
													</div>
													<div class="summary-footer">
														<a class="text-muted text-uppercase" href="index.php">(посмотреть)</a>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-md-12 col-lg-6 col-xl-3">
									<section class="panel panel-featured-left panel-featured-quartenary">
										<div class="panel-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-quartenary">
														<i class="fa fa-user-plus"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Отправленных контрактов</h4>
														<div class="info">
														    <? if($users['admin'] == 1){ ?>
															<strong class="amount"><?=mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM sent"))[0]?></strong>
															<? }else{ ?>
															<strong class="amount"><?=mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM sent WHERE login='".$users['login']."'"))[0]?></strong>
															<? } ?>
														</div>
													</div>
													<div class="summary-footer">
														<a class="text-muted text-uppercase" href="sent.php">(посмотреть)</a>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-md-12 col-lg-6 col-xl-3">
									<section class="panel panel-featured-left panel-featured-success">
										<div class="panel-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-success">
														<i class="fa fa-user"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Работников в работе</h4>
														<div class="info">
														    <? if($users['admin'] == 1){ ?>
															<strong class="amount"><?=mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM works"))[0]?></strong>
															<? }else{ ?>
															<strong class="amount"><?=mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM works WHERE login='".$users['login']."'"))[0]?></strong>
															<? } ?>
														</div>
													</div>
													<div class="summary-footer">
														<a class="text-muted text-uppercase" href="works.php">(посмотреть)</a>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>
						</div>
					</div>
                    
                    <!-- start: page -->
                    	<section class="panel">
							<div class="panel-body">
							    <div id="sar2" class="col-md-1" style="padding-left: 0px; float: left; width: 48px; display: none;">
   								    <a href="#modalAnim3" onclick="comm2('red');" class="btn btn-default mb-1 mt-1 mr-1 modal-with-move-anim ws-normal" style="color: #0088cc; padding: 5px 12px;"><i style="font-size: 2rem" class="fa fa-pencil"></i></a>
   								</div>
   								<? if($users['admin'] == 1){ ?>
							    <div id="sar" class="col-md-1" style="padding-left: 0px; float: left; display: none;">
   								    <a href="javascript:comm('del');" onclick="return confirm('Вы действительно хотите удалить выбранных работников?');" class="btn btn-default" style="color: #0088cc; padding: 5px 12px;"><i style="font-size: 2rem" class="fa fa-trash"></i></a>
   								</div>
   								<? } ?>
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
											<th width="100">Дата добавления</th>
											<th width="100">Дата редактирования</th>
											<th width="150">Почта</th>
											<th width="150">Имя</th>
											<th width="150">Телефон</th>
											<th width="150">Страна</th>
											<th width="150">Город</th>
											<th width="150">Статус</th>
											<th width="150">Комментарий</th>
											<th width="1">Действия</th>
										</tr>
									</thead>
									<tbody>
									    <?
									    if($users['admin'] == 1){
									        $logs = mysqli_query($con, "SELECT * FROM `sent` ORDER BY `sent`.`id` ASC;");
									    }else{
									        $logs = mysqli_query($con, "SELECT * FROM `sent` WHERE `login`='".$users['login']."' ORDER BY `sent`.`id` ASC;");
									    }
                                        while ($result = mysqli_fetch_assoc($logs)) {
                                        ?>
                                            <tr class='GradeX'>
                                                <td><div class="col-sm-2 checkbox-custom"><input type="checkbox" id="ch<?=$result['id']?>" onclick="dll(this);"><label for="ch<?=$result['id']?>"></label></div></td>
                                                <td><?=$result['id']?></td>
			                                    <td><?=$result['data']?></td>
			                                    <td><?=$result['data_red']?></td>
			                                    <td><?=base64_decode($result['mail'])?></td>
			                                    <td><?=base64_decode($result['name'])?></td>
			                                    <td><?=base64_decode($result['tel'])?></td>
			                                    <td><?=base64_decode($result['country'])?></td>
			                                    <td><?=base64_decode($result['city'])?></td>
			                                    <td>
			                                    <?
			                                    if($result['status'] == 2){
			                                        echo "Подписал";
			                                    }else if($result['status'] == 1){
			                                        echo "Отказался";
			                                    }else if($result['status'] == 3){
			                                        echo "Получил";
			                                    }else{
			                                        echo "Отправлен";
			                                    }
			                                    ?>
			                                    </td>
			                                    <td><?=base64_decode($result['comment'])?>
			                                    <? if($users['admin'] == 1){ ?>
			                                    <br />Зарезервировал - <?=$result['login']?>
			                                    <? } ?>
			                                    </td>
			                                    <td class='actions'>
			                                    <a href="#modalAnim" onclick="document.getElementById('id').value = '<?=$result['id']?>'; document.getElementById('data').value = '<?=$result['data']?>'; document.getElementById('data2').value = '<?=$result['data_red']?>'; document.getElementById('mail').value = b64DecodeUnicode('<?=$result['mail']?>'); document.getElementById('name').value = b64DecodeUnicode('<?=$result['name']?>'); document.getElementById('tel').value = b64DecodeUnicode('<?=$result['tel']?>'); document.getElementById('country').value = b64DecodeUnicode('<?=$result['country']?>'); document.getElementById('city').value = b64DecodeUnicode('<?=$result['city']?>'); document.getElementById('comm').value = b64DecodeUnicode('<?=$result['comment']?>'); gg(<?=$result['status']?>);" data-toggle="tooltip" data-trigger="hover" data-original-title="Редактировать" class="on-default pencil mb-1 mt-1 mr-1 modal-with-move-anim ws-normal"><i class="fa fa-pencil"></i></a>
												<? if($users['admin'] == 1){ ?>
												<a href="del_sent.php?id=<?=$result['id']?>" onclick="return confirm('Вы действительно хотите удалить работника?');" data-toggle="tooltip" data-trigger="hover" data-original-title="Удалить" class="on-default trash"><i class="fa fa-trash"></i></a>
												<? } ?>
			                                    </td>
			                                </tr>
                                        <? } ?>
									</tbody>
								</table>
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
				</section>
			</div>
			
			<script>
			                <?
                            $logsd = mysqli_query($con, "SELECT * FROM `sent`;");
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
						                <? if($users['admin'] == 1){ ?>
						                document.getElementById('sar').style = 'padding-left: 0px; float: left; display: inline-block;';
						                <? } ?>
						                document.getElementById('sar2').style = 'padding-left: 0px; float: left; width: 48px; display: inline-block;';
						            }else{
						                document.getElementById('ch'+loga[i]).checked = false;
						                <? if($users['admin'] == 1){ ?>
						                document.getElementById('sar').style = 'padding-left: 0px; float: left; display: none;';
						                <? } ?>
						                document.getElementById('sar2').style = 'padding-left: 0px; float: left; width: 48px; display: none;';
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
							        <? if($users['admin'] == 1){ ?>
								    document.getElementById('sar').style = 'padding-left: 0px; float: left; display: inline-block;';
								    <? } ?>
								    document.getElementById('sar2').style = 'padding-left: 0px; float: left; width: 48px; display: inline-block;';
							    }else{
							        if(tre() == ""){
							            <? if($users['admin'] == 1){ ?>
									    document.getElementById('sar').style = 'padding-left: 0px; float: left; display: none;';
									    <? } ?>
									    document.getElementById('sar2').style = 'padding-left: 0px; float: left; width: 48px; display: none;';
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
                                document.getElementById('s2').removeAttribute('selected');
                                document.getElementById('s3').removeAttribute('selected');
                                
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
						            alert('Выберите хотя бы 1 работника!');
						        }else{
						            $.ajax({
                                        type: 'GET',
                                        url: 'del_sent.php?m=yes&id='+m2,
                                        data: {},
                                        success: function(data) {
                                            if(data == 'ok'){
						                        location.reload();
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
						            alert('Выберите хотя бы 1 работника!');
						        }else{
						            document.getElementById('id2').value = m2;
						        }
						    }
			</script>
			
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
										<label class="col-md-12 control-label" for="textareaDefault">Дата добавления</label>
									    <div class="col-md-12">
									        <input class="form-control" type="text" name="data" value="" id="data" disabled>
									    </div>
									</div>
									<div class="form-group">
										<label class="col-md-12 control-label" for="textareaDefault">Дата редактирования</label>
									    <div class="col-md-12">
									        <input class="form-control" type="text" name="data" value="" id="data2" disabled>
									    </div>
									</div>
							        <div class="form-group">
										<label class="col-md-3 control-label" for="textareaDefault">Почта</label>
									    <div class="col-md-12">
									        <input class="form-control" type="text" name="mail" value="" id="mail">
									    </div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="textareaDefault">Имя</label>
									    <div class="col-md-12">
									        <input class="form-control" type="text" name="name" value="" id="name">
									    </div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="textareaDefault">Телефон</label>
									    <div class="col-md-12">
									        <input class="form-control" type="text" name="tel" value="" id="tel">
									    </div>
									</div>
									<div class="form-group">
										<label class="col-md-12 control-label" for="textareaDefault">Страна, город</label>
									    <div class="col-md-6">
									        <input class="form-control" type="text" name="country" value="" placeholder="Страна" id="country">
									    </div>
									    <div class="col-md-6">
									        <input class="form-control" type="text" name="city" value="" placeholder="Город" id="city">
									    </div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="textareaDefault">Комментарий</label>
									    <div class="col-md-12">
									        <input class="form-control" type="text" name="comm" value="" id="comm">
									    </div>
									</div>
									<div class="form-group col-md-12">
										<label class="col-md-3 control-label" for="textareaDefault" style="padding-left: 0px;">Статус</label>
									    <select class="form-control col-mb-6" name="status">
									        <option value="0" id="s0">Отправлен</option>
											<option value="1" id="s1">Отказался</option>
											<option value="2" id="s2">Подписал</option>
											<option value="3" id="s3">Получил</option>
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
			
			<div id="modalAnim3" class="zoom-anim-dialog modal-block modal-block-primary mfp-hide">
                <section class="panel">
	                <header class="panel-heading">
					    <h2 class="panel-title">Редактирование работников:</h2>
	                </header>
	                <form method="POST">
	                    <input type="hidden" name="id" value="red">
	                    <input type="hidden" name="id2" value="" id="id2">
	                    <div class="panel-body" style="border-radius: 0px;">
					        <div class="modal-wrapper">
						        <div class="modal-text">
									<div class="form-group col-md-12">
										<label class="col-md-3 control-label" for="textareaDefault" style="padding-left: 0px;">Статус</label>
									    <select class="form-control col-mb-6" name="status">
									        <option value="0" selected>Отправлен</option>
											<option value="1">Отказался</option>
											<option value="2">Подписал</option>
											<option value="3">Получил</option>
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