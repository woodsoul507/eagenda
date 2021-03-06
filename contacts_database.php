<?php

require_once realpath(__DIR__ . "/vendor/autoload.php");

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('HOST', $_ENV['DB_HOST']);
define('USER', $_ENV['DB_USER']);
define('PASS', $_ENV['DB_PASS']);
define('NAME', $_ENV['DB_NAME']);

class ContactsDatabase
{
    private $con;
    private string $dbhost = HOST;
    private string $dbuser = USER;
    private string $dbpass = PASS;
    private string $dbname = NAME;
    function __construct()
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
        $contact_nickname,
        $contact_firstname,
        $contact_lastname,
        $contact_number,
        $contact_email,
        $contact_birthday,
        $contact_address,
        $contact_image,
        $user
    ) {
        $sql = "INSERT INTO `contacts` (create_time, contact_nickname, contact_firstname, contact_lastname,
        contact_number, contact_email, contact_birthday, contact_address, contact_image, contact_user_id)
        VALUES (NOW(), '$contact_nickname', '$contact_firstname', '$contact_lastname', '$contact_number',
        '$contact_email', '$contact_birthday', '$contact_address', '$contact_image', '$user->user_id')";
        $res = mysqli_query($this->con, $sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function read($user)
    {
        $sql = "SELECT * FROM contacts WHERE contact_user_id = '$user->user_id'";
        $res = mysqli_query($this->con, $sql);
        return $res;
    }

    public function single_record($contact_id)
    {
        $sql = "SELECT * FROM contacts where contact_id='$contact_id'";
        $res = mysqli_query($this->con, $sql);
        $return = mysqli_fetch_object($res);
        return $return;
    }

    public function update(
        $contact_nickname,
        $contact_firstname,
        $contact_lastname,
        $contact_number,
        $contact_email,
        $contact_birthday,
        $contact_address,
        $contact_image,
        $contact_id
    ) {
        $sql = "UPDATE contacts SET update_time=NOW(), contact_nickname='$contact_nickname', contact_firstname='$contact_firstname',
        contact_lastname='$contact_lastname', contact_number='$contact_number', contact_email='$contact_email',
        contact_address='$contact_address', contact_image='$contact_image'";
        if (strcmp($contact_birthday, "") != 0) {
            $sql = $sql . ", contact_birthday='$contact_birthday' WHERE contact_id=$contact_id";
        } else {
            $sql = $sql . " WHERE contact_id=$contact_id";
        }
        $res = mysqli_query($this->con, $sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($contact_id)
    {
        $sql = "DELETE FROM contacts WHERE contact_id=$contact_id";
        $res = mysqli_query($this->con, $sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function updateAuth($id)
    {
        $user = $_SESSION['user'];
        $user_id = intval($user->user_id);
        $sql = "SELECT COUNT(*) as isAuth FROM contacts WHERE contact_id = '$id' AND contact_user_id = '$user_id'";
        $res = mysqli_query($this->con, $sql);
        $resArray = mysqli_fetch_array($res);
        if ($resArray['isAuth'] > 0) {
            return true;
        } else {
            return false;
        }
    }
}
