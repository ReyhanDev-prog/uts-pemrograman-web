<?php
session_start();
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
header("Location: /project_cv/Admin/login.php");
exit;