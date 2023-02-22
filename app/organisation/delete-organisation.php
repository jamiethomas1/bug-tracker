<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/orgController.class.php');

$orgID = $_SESSION['org_id'];

$dbh = new OrgController();

$dbh->deleteOrganisation($orgID);
header("Location: /");
exit;