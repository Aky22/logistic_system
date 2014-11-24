<?php
    session_start();
    if(isset($_POST['submit']))
    {
        $varname = array('username', 'pass');
        $messagename = array('Username', 'Passworld');
        
        require_once('include/config.inc.php');
        require_once('include/functions.php');
        require_once('include/my_mysqli.php');
        if(checkexist($varname, $messagename) == 'true')
        {
            $username = $_POST['username'];
            $pass = $_POST['pass'];
            $remember = $_POST['remember'];
            if($remember == 1)
            {
                setcookie('username', $username, time() + (86400 * 30), '/');
                setcookie('pass', $pass, time() + (86400 * 30), '/');
            }
            
            $db = new my_mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            $sql = "SELECT * FROM `users` WHERE `azon` = '?' AND `jelszo` = sha1('?')";
            $result = $db->select_one_row($sql, $params = array($username, $pass));
            $_SESSION['username'] = $result['azon'];
        }
        else
        {
            echo 'hiba';
        }
    }
?>