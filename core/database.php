<?php
  include('./classes/user.php');
  include(__DIR__ .'/../amqp/config.php');
  use PhpAmqpLib\Connection\AMQPConnection;
  use PhpAmqpLib\Message\AMQPMessage;

  class Database {

    public $c;

    public function disconnect() {
      mysqli_close($this->c);
    }

    public function connectMaster() {
      include ('db_config.php');
      $this->c = new mysqli($servername_master, $username_master, $password_master, $dbname_master);
      
      if ($this->c->connect_error) {
        die("Failed to connect with db: " . $c->connect_error);
      }
    }

    public function connectAny() {
      include ('db_config.php');
      $this->c = new mysqli($servername_slave, $username_slave, $password_slave, $dbname_slave);
      
      if ($this->c->connect_error) {
        var_dump('Cant connect to slave, trying to connect master');
        $this->c = new mysqli($servername_master, $username_master, $password_master, $dbname_master);
        
        if ($this->c->connect_error) {
          die("Failed to connect with db: " . $c->connect_error);
        }
      }
    }

    public function findIDbyUsername($username) {
      
      $this->connectAny();
      $query = "SELECT id FROM user WHERE username = '" . $username . "'";
      $result = $this->c->query($query);
      
      if ($result->num_rows > 0) {
          $id = $result->fetch_assoc()['id'];
      } else {
          return null;
      }

      $this->disconnect();
      return $id;
    }

    public function getUserPassword($username) {
      
      $this->connectAny();
      $query = "SELECT password FROM user WHERE username = '" . $username . "'";
      $result = $this->c->query($query);
      
      if ($result->num_rows>0) {
          $password = $result->fetch_assoc()['password'];
      } else {
          return null;
      }

      $this->disconnect();
      return $password;
    }

    public function fetchUserData($id) {
      
      $this->connectAny();
      $query = "SELECT * FROM user WHERE id = '" . $id . "'";
      $result = $this->c->query($query);

      if ($result->num_rows==1) {
          $data = array();

          while($single = $result->fetch_assoc()){
             $data[] = $single;
          }

          $user = new UserService($data[0]['id'],
                                  $data[0]['username'],
                                  $data[0]['name'],
                                  $data[0]['surname'],
                                  $data[0]['nip'],
                                  $data[0]['pesel'],
                                  $data[0]['address'],
                                  $data[0]['isAdmin']);
      } else {
          return null;
      }

      $this->disconnect();
      return $user;
    }

    public function addPost($author, $title, $description) {
      
      $this->connectMaster();
      $title = $_POST['title'];
      $description = $_POST['description'];
      $query = "INSERT INTO post(author, title, description) VALUES ('$author', '$title', '$description')";
      $result = $this->c->query($query);

      if ($result === TRUE) {
        alert("Post add");
        global $db;
        $db->queueAdd();
      } else {
        alert ("Error");
      }

      $this->disconnect();
    }

    public function fetchPosts() {
      
      $this->connectAny();
      $query = "SELECT * FROM post WHERE isAccept = '1' ORDER BY id DESC LIMIT 10";
      $result = $this->c->query($query);

      $posts = array();
      
      if ($result && $result->num_rows > 0) {
       while ($row = $result->fetch_assoc()) {
         array_push($posts, $row);
         echo '
         <div class="qa-message-list">
    	      <div class="message-item">
				      <div class="message-inner">
					      <div class="message-head clearfix">
						      <div class="avatar pull-left">
                    <figure>';
                      avatar($row['author']); echo '
                    </figure>
                  </div>
							    <div class="user-detail">
								    <h4 class="handle">'. $row['title'].'@'.$row['author'].'</h4>
								    <div class="post-meta">
									    <div class="asker-meta"></div>
								    </div>
							    </div>
						    </div>
						    <div class="qa-message-content">'.$row['description'].'</div>
					    </div>
            </div>
			    </div>
         ';
       }
      }

      $this->disconnect();
      return $posts;
    }

    public function register($username_R, $password_R, $name_R, $surname_R, $address_R, $nip_R, $pesel_R) {
      
      $this->connectMaster();
      $username_R = $_POST['username_R'];
      $password_R = $_POST['password_R'];
      $name_R = $_POST['name_R'];
      $surname_R = $_POST['surname_R'];
      $address_R = $_POST['address_R'];
      $nip_R = $_POST['nip_R'];
      $pesel_R = $_POST['pesel_R'];
      $query = "INSERT INTO user(username, password, name, surname, address, nip, pesel) VALUES ('$username_R', '$password_R', '$name_R', '$surname_R', '$address_R', '$nip_R', '$pesel_R')";
      $result = $this->c->query($query);

      if ($result === TRUE) {
        alert("Registration successful");
      } else {
        alert ("Error");
      }

      $this->disconnect();
    }

    public function isUsernameAvailable($username) {
      
      $this->connectAny();
      $query = "SELECT id FROM user WHERE username = '" . $username . "'";
      $result = $this->c->query($query);

      if ($result->num_rows > 0) {
        alert("This username is already taken");
        return false;
      } else {
        return true;
      }

      $this->disconnect();
    }

    public function queueAdd() {
      
      $this->connectAny();
      $query_max_id = "SELECT max(id) FROM post";
      $result_max_id = $this->c->query($query_max_id);
      $max_id = $result_max_id->fetch_assoc();
      $id = $max_id['max(id)'];
      $query = "SELECT * FROM post WHERE id = '$id'";
      $result = $this->c->query($query);

      if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $post = json_encode($row);
        $props = array('content_type' => 'application/json', 'delivery_mode' => 2);

        $exchange = 'rso_test';
        $queue = 'rso_queue';
        $conn = new AMQPConnection(HOST, PORT, USER, PASS, VHOST);
        $ch = $conn->channel();
        $ch->queue_declare($queue, false, true, false, false);
        $ch->exchange_declare($exchange, 'direct', false, true, false);
        $ch->queue_bind($queue, $exchange);
        $toSend = new AMQPMessage($post, $props);
        $ch->basic_publish($toSend, $exchange);
        $ch->close();
        $conn->close();
      }

      $this->disconnect();
    }

    function queueGet() {
      
      $exchange = 'rso_test';
      $queue = 'rso_queue';
      $conn = new AMQPConnection(HOST, PORT, USER, PASS, VHOST);
      $ch = $conn->channel();
      $ch->queue_declare($queue, false, true, false, false);
      $ch->exchange_declare($exchange, 'direct', false, true, false);
      $ch->queue_bind($queue, $exchange);
      $msg = $ch->basic_get($queue);
      
      if ($msg !== NULL) {
        $ch->basic_recover($msg->delivery_info['delivery_tag']);
        $first_post = json_decode($msg->body, true);
        echo '
        <div class="qa-message-list">
          <form method="post">
    	      <div class="message-item">
				      <div class="message-inner">
					      <div class="message-head clearfix">
						      <div class="avatar pull-left">
                    <figure>';
                      avatar($first_post['author']); echo '
                    </figure>
                  </div>
							    <div class="user-detail">
								    <h4 class="handle">'. $first_post['title'].'@'.$first_post['author'].'</h4>
								    <div class="post-meta">
									    <div class="asker-meta"></div>
								    </div>
							    </div>
						    </div>
						    <div class="qa-message-content">'.$first_post['description'].'</div>
					    </div>
            </div><br><br>
            <button class="btn btn-block btn-lg btn-warning" type="submit" name="submit_accept" id="submit_accept">Accept</button>
            <button class="btn btn-block btn-lg btn-danger" type="submit" name="submit_decline" id="submit_decline">Decline</button>
          </form>
        </div>
        ';
        return $msg->body;
      } else {
        echo '
        <div class="well well-sm">
          <div class="panel-heading">
            <div class="panel-title text-center">
              <h1 class="title">No posts</h1>
            </div>
          </div>
        </div>';

        return false;
      }

      $ch->close();
      $conn->close();
    }

    public function queueDelete() {
      
      $exchange = 'rso_test';
      $queue = 'rso_queue';
      $conn = new AMQPConnection(HOST, PORT, USER, PASS, VHOST);
      $ch = $conn->channel();
      $ch->queue_declare($queue, false, true, false, false);
      $ch->exchange_declare($exchange, 'direct', false, true, false);
      $ch->queue_bind($queue, $exchange);
      $msg = $ch->basic_get($queue);
      
      if ($msg !== NULL) {
        $ch->basic_ack($msg->delivery_info['delivery_tag']);
        return true;
      } else {
        return false;
      }

      $ch->close();
      $conn->close();
    }

    public function queueAccept() {
      
      $exchange = 'rso_test';
      $queue = 'rso_queue';
      $conn = new AMQPConnection(HOST, PORT, USER, PASS, VHOST);
      $ch = $conn->channel();
      $ch->queue_declare($queue, false, true, false, false);
      $ch->exchange_declare($exchange, 'direct', false, true, false);
      $ch->queue_bind($queue, $exchange);
      $msg = $ch->basic_get($queue);
      
      if ($msg !== NULL) {
        $first_post = json_decode($msg->body, true);
        $id = $first_post['id'];

        $this->connectMaster();

        $query = "UPDATE post SET isAccept=1 WHERE id=$id";
        $result = $this->c->query($query);

        $this->disconnect();

        if ($result === TRUE) {
          $ch->basic_ack($msg->delivery_info['delivery_tag']);
          alert("Post is already accepted");
        } else {
          alert ("Error");
        }

        return true;
      } else {
        return false;
      }

      $ch->close();
      $conn->close();
    }
  }

  $db = new Database();
?>