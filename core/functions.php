<?php
        require_once('database.php');

        function session_check()
        {
                if(!isset($_COOKIE['MYSID'])) {
                        $token=md5(rand(0,1000000000));
                        setcookie('MYSID', $token);
                        $user=array('id'=>NULL,'name'=>"anonymous", 'surname'=>"user");
                        redis_set_json($token, $user,0);
                }
                else
                        $token=$_COOKIE['MYSID'];

                if (isset($_POST['username']) and isset($_POST['password']))
                        return authorize($_POST['username'],$_POST['password'],$token);
                else
                        return authorize(NULL,NULL,$token);
        }

        function authorize($username, $password, $token)
        {
                global $db;
                if ($username != NULL && $password != NULL && $username != '' && $password != '')
                {
                        $db_password = $db->getUserPassword($username);
                        $db_id = $db->findIDbyUsername($username);
                        $db_user = $db->fetchUserData($db_id);

                        if ($db_password != NULL && $password == $db_password && $db_id != NULL) {
                                $user = (array) $db_user;
                        } else {
                                $user=array('id'=>NULL,'name'=>"anonymous", 'surname'=>"user");
                        }
                        redis_set_json($token,$user,'0');
                }
                        return redis_get_json($token);
        }

        function logout($user)
        {
                $token=$_COOKIE['MYSID'];
                $user=array('id'=>NULL,'name'=>"anonymous", 'surname'=>"user");
                redis_set_json($token,$user,'0');
                return $user;
        }

        function redis_set_json($key, $val, $expire)
        {
                $redisClient = new Redis();
                $redisClient->connect( '192.168.225.129', 6379 );
                $value=str_replace('\\u0000UserService\\u0000_', '', json_encode($val));
                if ($expire > 0)
                        $redisClient->setex($key, $expire, $value );
                else
                        $redisClient->set($key, $value);
                $redisClient->close();
        }

        function redis_get_json($key)
        {
                $redisClient = new Redis();
                $redisClient->connect( '192.168.225.129', 6379 );
                $ret=json_decode($redisClient->get($key),true);
                $redisClient->close();
                return $ret;
        }

        function redirectJS($url) {
                echo '<script>window.location.replace("'.$url.'");</script>';
        }

        function alert($msg) {
                echo '<script type="text/javascript">alert("' . $msg . '")</script>';
        }

        function avatar($name) {
                if (file_exists('uploads/'.$name.'.jpg')) {
                        echo'<img class="rounded" src="uploads/'.$name.'.jpg" />';
                } else {
                        echo'<img class="rounded" src="uploads/default_avatar.jpg">';
                }
        }

        function posts_cache() {
                global $db;
                $posts = $db->fetchPosts();
                redis_set_json('posts',(array) $posts, '0');
        }
?>