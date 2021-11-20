<?php

class ContactsDatabase
{
    private $con;
    private string $dbhost = "localhost";
    private string $dbuser = "root";
    private string $dbpass = "";
    private string $dbname = "directorio_personal";
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
        $contact_name,
        $contact_lastname,
        $contact_number,
        $contact_email,
        $contact_address,
        $contact_image
    ) {
        $sql = "INSERT INTO `contacts` (create_time, contact_nickname, contact_name, contact_lastname, contact_number, contact_email, contact_address, contact_image) VALUES (NOW(),
        '$contact_nickname', '$contact_name', '$contact_lastname', '$contact_number', '$contact_email', '$contact_address', '$contact_image')";
        $res = mysqli_query($this->con, $sql);
        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function read()
    {
        $sql = "SELECT * FROM contacts";
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
        $contact_name,
        $contact_lastname,
        $contact_number,
        $contact_email,
        $contact_address,
        $contact_image,
        $contact_id
    ) {
        $sql = "UPDATE contacts SET update_time=NOW(), contact_nickname='$contact_nickname', contact_name='$contact_name',
        contact_lastname='$contact_lastname', contact_number='$contact_number', contact_email='$contact_email',
        contact_address='$contact_address', contact_image='$contact_image' WHERE contact_id=$contact_id";
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
}
