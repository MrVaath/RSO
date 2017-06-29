<?php
if ($user==NULL or $user['id']==NULL) {
  echo '
    <div class="well well-sm">
      <div class="panel-heading">
        <div class="panel-title text-center">
          <h1 class="title">Distributed Operating Systems</h1>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row main">
        <div class="panel-heading">
          <div class="panel-title text-center">
            <h1 class="title">Sign in</h1>
              <hr />
          </div>
        </div>
        <div class="main-login main-center">
          <form class="form-horizontal" method="post" action="login">
            
            <div class="form-group">
              <div class="cols-sm-10">
                <input type="text" class="form-control" name="username" id="username"  placeholder="Username"/>
              </div>
            </div>

            <div class="form-group">
              <div class="cols-sm-10">
                <input type="password" class="form-control" name="password" id="password"  placeholder="Password"/>
              </div>
            </div>

            <div class="form-group ">
              <button type="submit" class="btn btn-primary btn-lg btn-block login-button">Sign in</button>
            </div>
            <div class="login-register">
              <a href="/register">or create an account</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  ';
} else {
  redirectJS('home');
}
