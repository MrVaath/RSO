<?php
if ($user==NULL or $user['id']==NULL) {
  redirectJS('login');
} else {
  echo '

  <div class="container">';
    posts_cache(); 
    echo'
  </div><br><br>
  ';
}
?>
