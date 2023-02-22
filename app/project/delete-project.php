<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/projController.class.php');

$orgID = $_SESSION['org_id'];
$projID = $_SESSION['proj_id'];

$dbh = new ProjController();

$dbh->deleteProject($projID);
header("Location: ../organisation/?org_id=" . $orgID);
exit;