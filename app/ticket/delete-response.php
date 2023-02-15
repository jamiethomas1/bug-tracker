<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/ticketController.class.php');

$orgID = $_SESSION['org_id'];
$projID = $_SESSION['proj_id'];
$ticketID = $_SESSION['ticket_id'];

$responseID = $_GET['response_id'];

$dbh = new TicketController();

$dbh->deleteResponse($responseID);
header("Location: ../ticket/?org_id=" . $orgID . "&proj_id=" . $projID . "&ticket_id=" . $ticketID);
exit;