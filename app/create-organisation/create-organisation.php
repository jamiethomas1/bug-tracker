<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/orgController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php-scripts/randomString.php');

if (empty($_POST['name'])) {
    die("Organisation name is required.");
}

$orgName = htmlspecialchars($_POST['name']);
$userID = $_SESSION['user_id'];
$orgID = getRandomString();

$dbh = new OrgController();

$dbh->setOrganisation($orgName, $userID, $orgID);
header("Location: ../index.php");
exit;