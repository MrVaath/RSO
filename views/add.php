<?php
if ($user==NULL or $user['id']==NULL) {
  redirectJS('home');
}

if (isset($_POST['submitP'])) {
  global $db;

  $author = $user['username'];

  if($_POST['title'] == '' || $_POST['description'] == '') {
    alert ("All fields are required");
  } else {
    $db->addPost($author, $title, $description);
  }
}

?>

<div class="well well-sm">
  <div class="panel-heading">
    <div class="panel-title text-center">
      <h1 class="title">Add post</h1>
      <h7>and wait until the administrator accepts it</h7>
    </div>
  </div>
</div>

<div class="container">
  <form method="post" action="add">
    <div class="row main">
      <div class="main-login main-center-2">
        <div class="form-group">
          <input type="text" class="form-control" name="title" id="title"  placeholder="Title"/>
        </div>
    
        <div class="form-group">
          <textarea class="form-control" rows="5" name="description" id="description"  placeholder="Description"></textarea>
        </div>
        
        <button type="submit" name="submitP" class="btn btn-primary btn-lg">Add</button>
      </div>
    </div>
  </form>
</div>