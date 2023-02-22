<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/userController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/orgController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/projController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/ticketController.class.php');

session_start();

// Check if logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: /login/");
}

$dbh = new UserController();
$user = $dbh->getUserByID($_SESSION["user_id"]);

$ticketHandle = new TicketController();

$ticketObj = $ticketHandle->getTicketByID($_SESSION['ticket_id']);
$ticketName = $ticketObj['name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous" defer></script>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="js/validation.js" defer></script>
    <title>Reply to ticket</title>
</head>
<body>
    <form action="/ticket-reply/ticket-reply.php" id="ticket-reply" method="post">
        <div>
            <input type="text" name="name" class="form-control" id="name" value="Re: <?= $ticketName ?>">
        </div>
        <div>
            <input type="text" name="body" class="form-control" id="body" placeholder="Reply Body">
        </div>
        <div>
            <input type="submit" class="btn btn-primary">
        </div>
    </form>
</body>
</html>