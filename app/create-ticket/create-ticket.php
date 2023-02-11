<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/ticketController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/php-scripts/randomString.php');

if (empty($_POST['name'])) {
    die("Ticket name is required.");
}

if (empty($_POST['body'])) {
    die("Ticket description is required.");
}

$ticketName = htmlspecialchars($_POST['name']);
$ownerID = $_SESSION['user_id'];
$orgID = $_SESSION['org_id'];
$projID = $_SESSION['proj_id'];
$ticketID = getRandomString();
$ticketBody = htmlspecialchars($_POST['body']);

$dbh = new TicketController();

$dbh->createTicket($ticketName, $ownerID, $projID, $ticketID, $ticketBody);
header("Location: ../project/?org_id=" . $orgID . "&proj_id=" . $projID);
exit;