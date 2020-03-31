<?php
$bd_host = "localhost"; //DB address
$bd_user = "";  //DB user
$bd_password = "";  //DB password
$bd_base = "";   //DB name
$con = mysqli_connect($bd_host, $bd_user, $bd_password, $bd_base);

mysqli_query($con, "set names 'utf8'");
mysqli_query ($con, "set character_set_client='utf8'");
mysqli_query ($con, "set character_set_results='utf8'");
mysqli_query ($con, "set collation_connection='utf8_general_ci'");

date_default_timezone_set('Europe/Moscow');
?>