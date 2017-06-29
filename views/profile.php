<?php
if ($user==NULL or $user['id']==NULL) {
  redirectJS('login');
} else {
  echo '
    <div class="well well-sm">
      <div class="panel-heading">
        <div class="panel-title text-center">
          <figure class="account">';
            avatar(strtolower($user['username'])); echo '
          </figure>
          <h1 class="title">
            '.$user['name'].' '.$user['surname'].'
          </h1>
          <h4 style="font-style: italic;">
            ';
            if ($user['isAdmin']==true) {
              echo '(admin account)';
            } else {
              echo '(user account)';
            }
            echo'
          </h4>
        </div>
      </div>
    </div>

    <form action="upload" method="post" enctype="multipart/form-data">
      <div class="container"><br>
			  <div class="panel panel-default">
				  <div class="panel-heading"><strong>Change your photo</strong></div>
				  <div class="panel-body">
						<div class="btn btn-default file-preview-input">
							<input type="file" name="file" id="file" class="inputfile" accept="image/jpg"/>
						</div>
						<button type="submit" name="submitPh" class="btn btn-labeled btn-primary">Upload</button>
          </div>
        </div>
      </div>
    </form>

    <script>
      var file = document.getElementById("file");
      file.onchange = function(){
        if(file.files.length > 0) {
          document.getElementById("filename").innerHTML = file.files[0].name;
        }
      };
    </script>';
}
?>