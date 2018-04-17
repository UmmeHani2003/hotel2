<?php

ob_start();
session_start();

require '../lib/phpPasswordHashing/passwordLib.php';
require 'DB.php';
require 'dao/CustomerDAO.php';
require 'models/Customer.php';
require 'handlers/CustomerHandler.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitBtn"])) {

    $errors_ = null;

    if (!empty($_POST["newPassword"])) {
        if (strlen($_POST["newPassword"]) < 4)
            $errors_ .= displayAlert("At least 4 characters is required.", "info");
    }

    if (!empty($errors_)) {
        echo $errors_;
    } else {
        $c = new Customer();
        $c->setId($_POST["cid"]);
        $c->setFullName($_POST["fullName"]);
        $c->setPhone($_POST["phone"]);
        $c->setEmail($_POST["email"]);
        $c->setPassword($_POST["newPassword"]);

        $cHandler = new CustomerHandler();
        $cHandler->updateCustomer($c);
        echo displayAlert($cHandler->getExecutionFeedback(), "success");

        if (isset($_SESSION["username"])) {
            $_SESSION["username"] = $cHandler->getUsername($_POST["email"]);
        }
        if (isset($_SESSION["phoneNumber"])) {
            $_SESSION["phoneNumber"] = $_POST["phone"];
        }
    }

}

function displayAlert($msg, $type)
{
    return '<div class="alert alert-' . $type . '" role="alert">' . $msg . '</div>';
}

/**
 * if password field is not empty
 * then validate it
 */