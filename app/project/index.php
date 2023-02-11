<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/userController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/orgController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/projController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/ticketController.class.php');

session_start();

if (isset($_SESSION["user_id"])) {
    $dbh = new UserController();
    $user = $dbh->getUserByID($_SESSION["user_id"]);

    $orgHandle = new OrgController();
    $projHandle = new ProjController();
    if (isset($_GET['org_id'])) {
        $orgObj = $orgHandle->getOrganisationByID($_GET["org_id"]);
        if (!$orgObj || $orgObj['ownerID'] !== $user['userID']) {
            header("Location: ../");
        }
        if (isset($_GET['proj_id'])) {
            $projObj = $projHandle->getProjectByID($_GET['proj_id']);
            if (!$projObj || $projObj['orgID'] !== $orgObj['orgID']) {
                header("Location: ../organisation/?org_id=" . $orgObj['orgID']);
            }
        }
    } else {
        header("Location: ../");
    }
    if ($projObj && $orgObj) {
        $orgName = $orgObj['name'];
        $projName = $projObj['name'];
        $_SESSION['proj_id'] = $projObj['projID'];
    }

    $ticketHandle = new TicketController();
    $ticketList = $ticketHandle->getTickets($projObj['orgID']);

} else {
    header("Location: /login/");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous" defer></script>
    <title>My Organisation</title> <!-- This can be updated to be the name of the organisation from the PHP code. -->
</head>
<body>
    <div class="container">
        <h1 class="display-1"><?= $projName ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/"><?= $orgName ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $projName ?></li>
            </ol>
        </nav>
        <div class="card">
            <!-- If user has organisation(s) then display a list, otherwise display default -->
            <h3 class="card-title display-3">Tickets</h1>
            <?php if (empty($ticketList)): ?>
                <div class="card-body">
                    <p class="card-text">Tickets allow you to report bugs and track their progress. <a href="/create-ticket/">Create one now</a>.</p>
                </div>
            <?php else: ?>
                <div class="card-body">
                    <ul>
                        <?php foreach ($ticketList as $ticket): ?>
                            <li><?= htmlspecialchars($ticket['name']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="card-text"><a href="/create-ticket/">Create another ticket</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>