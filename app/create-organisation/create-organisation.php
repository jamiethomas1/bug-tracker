<?php

session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/classes/orgController.class.php';

if (empty($_POST['name'])) {
    die("Organisation name is required.");
}

$orgName = htmlspecialchars($_POST['name']);

$dbh = new OrgController();

$dbh->setOrganisation($orgName, $_SESSION['user_id']);
header("Location: ../index.php");
exit;