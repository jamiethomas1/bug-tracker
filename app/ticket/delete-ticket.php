<?php

session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/ticketController.class.php');

$orgID = $_SESSION['org_id'];
$projID = $_SESSION['proj_id'];

$ticketID = $_GET['ticket_id'];

$dbh = new TicketController();

$dbh->delete(Delete::TICKET, $ticketID);
header("Location: ../project/?org_id=" . $orgID . "&proj_id=" . $projID);
exit;