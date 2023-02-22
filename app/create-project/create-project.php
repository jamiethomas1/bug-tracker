<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/projController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php-scripts/randomString.php');

if (empty($_POST['name'])) {
    die("Project name is required.");
}

$projName = htmlspecialchars($_POST['name']);
$userID = $_SESSION['user_id'];
$orgID = $_SESSION['org_id'];
$projID = getRandomString();

$dbh = new ProjController();

$dbh->setProject($projName, $userID, $orgID, $projID);
header("Location: ../project/?org_id=$orgID&proj_id=$projID");
exit;