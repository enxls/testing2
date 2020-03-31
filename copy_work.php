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
	
	if (preg_match("|^[0-9]+$|i", $_GET['id'])){
        $us = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `works` WHERE `id`='".$_GET['id']."';"));
        
        if($users['login'] != $us['login']){
	        if($users['admin'] != 1){
	            header('Location: index.php');
                die();
	        }
	    }
        
        if($us['data'] != ""){
            mysqli_query($con, 'INSERT INTO `works` SET `data`="'.$us['data'].'", `data_red`="'.date("d.m.Y H:i:s").'", `mail`="'.$us['mail'].'", `name`="'.$us['name'].'", `tel`="'.$us['tel'].'", `country`="'.$us['country'].'", `city`="'.$us['city'].'", `comment`="'.$us['comment'].'", `bank`="'.$us['bank'].'", `bic`="'.$us['bic'].'", `iban`="'.$us['iban'].'", `login`="'.$us['login'].'";');
        }
        
        header('Location: works.php');
    }
?>