<?
    session_start();
    
    require('config.php');
    
    if($_SESSION['adm_login'] != NULL and $_SESSION['adm_pass'] != NULL){
		if(preg_match('|^[A-Z0-9]+$|i', $_SESSION['adm_login'])){
			$users = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `settings` WHERE `login`='".$_SESSION['adm_login']."';"));
		
			if($_SESSION['adm_pass'] == $users['pass']){
			    if($users['admin'] != 1){
			        header('Location: logout.php');
				    die();
			    }
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
	
	if (preg_match("|^[0-9,]+$|i", $_GET['id'])){
        $exp = explode(",", $_GET['id']);
        foreach ($exp as $key => $value){
            mysqli_query($con, "DELETE FROM `sent` WHERE `id`='".$value."';");
        }
        
        if($_GET['m'] == "yes"){
            echo 'ok';
        }else{
            header('Location: sent.php');
        }
    }
?>