<?php

require_once realpath(__DIR__ . "/vendor/autoload.php");

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('HOST', $_ENV['DB_HOST']);
define('USER', $_ENV['DB_USER']);
define('PASS', $_ENV['DB_PASS']);
define('NAME', $_ENV['DB_NAME']);

class UsersDatabase
{
    private $con;
    private string $dbhost = HOST;
    private string $dbuser = USER;
    private string $dbpass = PASS;
    private string $dbname = NAME;
    public function __construct()
    {
        $this->connect_db();
    }
    public function connect_db()
    {
        $this->con = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        if (mysqli_connect_error()) {
            die("Server can not connect to Database " . mysqli_connect_error() . mysqli_connect_errno());
        }
    }

    public function sanitize($var)
    {
        $return = mysqli_real_escape_string($this->con, $var);
        return $return;
    }

    public function create(
        $user_nickname,
        $user_firstname,
        $user_lastname,
        $user_password,
        $user_birthday,
        $user_number,
        $user_email,
        $user_address,
        $user_image
    ) {
        $md5password = md5($user_password);
        $sql = "INSERT INTO `users` (create_time, user_nickname, user_firstname, user_lastname,
        user_password, user_birthday, user_number, user_email, user_address, user_image) VALUES (NOW(),
        '$user_nickname', '$user_firstname', '$user_lastname', '$md5password', '$user_birthday',
        '$user_number', '$user_email', '$user_address', '$user_image')";
        $res = mysqli_query($this->con, $sql);
        if ($res) {
            $this->startSession($user_email, $md5password);
            return true;
        } else {
            return false;
        }
    }

    public function read()
    {
        $sql = "SELECT * FROM users";
        $res = mysqli_query($this->con, $sql);
        return $res;
    }

    public function single_record($user_id)
    {
        $sql = "SELECT * FROM users where user_id='$user_id'";
        $res = mysqli_query($this->con, $sql);
        $return = mysqli_fetch_object($res);
        return $return;
    }

    public function update(
        $user_nickname,
        $user_firstname,
        $user_lastname,
        $user_birthday,
        $user_number,
        $user_email,
        $user_address,
        $user_image,
        $user_id
    ) {
        $sql = "UPDATE users SET update_time=NOW(), user_nickname='$user_nickname',
        user_firstname='$user_firstname', user_lastname='$user_lastname', user_number='$user_number',
        user_email='$user_email', user_address='$user_address', user_image='$user_image'";
        if (strcmp($user_birthday, "") != 0) {
            $sql = $sql . ", user_birthday='$user_birthday' WHERE user_id=$user_id";
        } else {
            $sql = $sql . " WHERE user_id=$user_id";
        }
        $res = mysqli_query($this->con, $sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($user_id)
    {
        $sql = "DELETE FROM users WHERE user_id=$user_id";
        $res = mysqli_query($this->con, $sql);
        if ($res) {
            session_start();
            session_destroy();
            return true;
        } else {
            return false;
        }
    }

    public function login(
        $user_password,
        $user_email
    ) {
        $md5password = md5($user_password);
        $sql = "SELECT COUNT(*) AS hasAccount FROM users WHERE user_email = '$user_email' AND user_password = '$md5password'";
        $res = mysqli_query($this->con, $sql);
        $resArray = mysqli_fetch_array($res);

        if ($resArray['hasAccount'] > 0) {
            $this->startSession($user_email, $md5password);
            return true;
        } else {
            return false;
        }
    }

    private function startSession($user_email, $md5password)
    {
        session_start();
        $sql = "SELECT * FROM users WHERE user_email = '$user_email' AND user_password = '$md5password'";
        $user = mysqli_query($this->con, $sql);
        $user_object = mysqli_fetch_object($user);
        $_SESSION['user'] = $user_object;
    }
}
