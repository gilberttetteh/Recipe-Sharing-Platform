<?php
session_start();
$_SESSION['signout'] = "You have successfully signed out.";
session_write_close();
session_unset();
session_destroy();
header("Location: ../view/login.php");
exit;