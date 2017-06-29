<?php
$uri = $_SERVER["REQUEST_URI"];

include "layout/header.php";

switch ($uri) {
    case "/":
        include "views/home.php";
        break;
    case "/login":
        include "views/login.php";
        break;
    case "/home":
        include "views/home.php";
        break;
    case "/register":
        include "views/register.php";
        break;
    case "/logout":
        include "views/logout.php";
        break;
    case "/profile":
        include "views/profile.php";
        break;
    case "/admin":
        include "views/admin.php";
        break;
    case "/upload":
        include "views/upload.php";
        break;
    case "/add":
        include "views/add.php";
        break;
    default:
        include "views/404.php";
        break;
}

include "layout/footer.php";
?>
