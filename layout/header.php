<?php
require_once('./core/functions.php');
$user=session_check();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>RSO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="layout/bootstrap.min.css" rel="stylesheet">
    <link href="layout/flat-ui.min.css" rel="stylesheet">
    <link href="layout/style.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-inverse" role="navigation">
      <div class="collapse navbar-collapse">
        <div class="navbar-header">
          <a href="/" class="navbar-brand">RSO_1</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
          <li class="active"><a href="add">Add post</a></li>
          <?php
            if ($user['isAdmin']==true) {
              echo '
              <li><a href="admin">All posts</a></li>
              ';
            }
          ?>
          <?php
            if ($user['id']!=NULL) { echo '
              <li><a href="profile">Account</a></li>
              <li><a href="logout">Sign out</a></li>
              ';
            } else {
              echo '
                <li><a href="register">Register</a></li>
              ';
            }
          ?>
        </ul>
      </div>
    </nav>