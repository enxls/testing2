<?
    session_start();
    $_SESSION['adm_login'] = '';
    $_SESSION['adm_pass'] = '';
    header('Location: login.php');
?>