<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/projController.class.php');

if (empty($_POST['name'])) {
    die("Project name is required.");
}

$projName = htmlspecialchars($_POST['name']);

$dbh = new ProjController();

$dbh->setProject($projName, $_SESSION['user_id'], $_SESSION['org_id']);
header("Location: ../organisation/?org_id=" . $_SESSION['org_id']);
exit;