<?php
include_once "check_security_token.php";
session_destroy();//détruit la session
header("location: ../../raminaplaya/login.php");
?>