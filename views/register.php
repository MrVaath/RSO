<?php
if ($user==NULL or $user['id']==NULL) {
  if (isset($_POST['submitR'])) {
    global $db;

    if ($_POST['username_R'] == '' || $_POST['password_R'] == '' || $_POST['password_confirm_R'] == '' || $_POST['name_R'] == '' || $_POST['surname_R'] == '' || $_POST['address_R'] == '' || $_POST['nip_R'] == '' || $_POST['pesel_R'] == '') {
      alert ("All fields are required");
    } else {
      if ($_POST['password_R'] == $_POST['password_confirm_R']) {
        if ($db->isUsernameAvailable($_POST['username_R'])) {
          $db->register($username_R, $password_R, $name_R, $surname_R, $address_R, $nip_R, $pesel_R);
          redirectJS('login');
        }
      } else {
        alert('Wrong password confirm');
      }
    }
  }
  echo '
  <div class="container">
  <div class="row main">
    <div class="panel-heading">
	    <div class="panel-title text-center">
	      <h1 class="title">New account</h1>
	        <hr />
	    </div>
	  </div>
    <div class="main-login main-center">
			<form class="form-horizontal" method="post" action="register">
				
        <div class="form-group">
					<div class="cols-sm-10">
						<input type="text" class="form-control" name="username_R" id="username_R"  placeholder="Username"/>
					</div>
				</div>

				<div class="form-group">
					<div class="cols-sm-10">
						<input type="password" class="form-control" name="password_R" id="password_R"  placeholder="Password"/>
					</div>
				</div>

				<div class="form-group">
					<div class="cols-sm-10">
						<input type="password" class="form-control" name="password_confirm_R" id="password_confirm_R"  placeholder="Confirm password"/>
					</div>
				</div>

				<div class="form-group">
					<div class="cols-sm-10">
						<input type="text" class="form-control" name="name_R" id="name_R"  placeholder="Name"/>
					</div>
				</div>

				<div class="form-group">
					<div class="cols-sm-10">
						<input type="text" class="form-control" name="surname_R" id="surname_R"  placeholder="Surname"/>
					</div>
				</div>

				<div class="form-group">
					<div class="cols-sm-10">
						<input type="text" class="form-control" name="address_R" id="address_R"  placeholder="Address"/>
					</div>
				</div>

				<div class="form-group">
					<div class="cols-sm-10">
						<input type="text" class="form-control" name="nip_R" id="nip_R"  placeholder="NIP"/>
					</div>
				</div>

				<div class="form-group">
					<div class="cols-sm-10">
						<input type="text" class="form-control" name="pesel_R" id="pesel_R"  placeholder="Pesel"/>
					</div>
				</div>

				<div class="form-group ">
					<button type="submit" name="submitR" class="btn btn-primary btn-lg btn-block register-button">Sign up for free!</button>
				</div>
				<div class="login-register">
				  <a href="login">or sign in</a>
				</div>
			</form>
		</div>
  </div>
</div>
  ';
} else {
  redirectJS('home');
}
?>
