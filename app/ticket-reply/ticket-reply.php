<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/ticketController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php-scripts/randomString.php');

if (empty($_POST['name'])) {
    die("Response title is required.");
}

if (empty($_POST['body'])) {
    die("Response body is required.");
}

$orgID = $_SESSION['org_id'];
$projID = $_SESSION['proj_id'];

$ticketID = $_SESSION['ticket_id'];
$responseID = getRandomString();
$userID = $_SESSION['user_id'];
$ticketName = htmlspecialchars($_POST['name']);
$responseBody = htmlspecialchars($_POST['body']);

$dbh = new TicketController();

$dbh->addResponse($ticketID, $responseID, $userID, $ticketName, $responseBody);
header("Location: ../ticket/?org_id=" . $orgID . "&proj_id=" . $projID . "&ticket_id=" . $ticketID);
exit;