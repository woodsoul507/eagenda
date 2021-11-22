<?php
session_start();
if (isset($_SESSION['user'])) {
    header("location: contacts/contacts_list.php");
} else {
    header("location: login_user.php");
}
