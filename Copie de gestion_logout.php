<?php
session_start();
session_destroy();
header("Location: gestion.php");
exit();
?>


