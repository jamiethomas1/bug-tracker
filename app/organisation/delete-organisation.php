<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/orgController.class.php');

$orgID = $_SESSION['org_id'];

$dbh = new OrgController();

$dbh->delete(Delete::ORGANISATION, $orgID);
header("Location: /");
exit;