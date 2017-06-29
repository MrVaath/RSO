<?php
if ($user==NULL or $user['id']==NULL or $user['isAdmin']==false) {
  redirectJS('home');
}

global $db;

if (isset($_POST['submit_accept'])) {
  $db->queueAccept();
  redirectJS('admin');
}

if (isset($_POST['submit_decline'])) {
  $db->queueDelete();
  alert('Post has been deleted');
}

?>

<div class="well well-sm">
  <div class="panel-heading">
    <div class="panel-title text-center">
      <h1 class="title">Posts</h1>
    </div>
  </div>
</div>

<div class="container">
  <?php
  global $db;
  $db->queueGet();
  ?>
</div><br><br>