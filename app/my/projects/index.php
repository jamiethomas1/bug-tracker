<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/userController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/projController.class.php');

session_start();

// Reset session variables when no longer needed
$_SESSION['org_id'] = '';
$_SESSION['proj_id'] = '';
$_SESSION['ticket_id']  = '';

// If logged in show homepage, otherwise redirect to login page
if (isset($_SESSION["user_id"])) {
    $dbh = new UserController();
    $projHandle = new ProjController();
    
    $user = $dbh->getUserByID($_SESSION["user_id"]);
    $projList = $projHandle->getProjectsByUser($user["userID"]);
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
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous" defer></script>
    <title>Bug Tracker</title>
</head>
<body>
<div class="navbar bg-dark navbar-expand-lg" data-bs-theme="dark" role="navigation">
        <div class="container justify-content-end">
            <ul class="nav nav-pills">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="/my/organisations/">Organisations</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" href="#">Projects</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#">Tickets</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" href="#">Inbox</a>
                </li>
            </ul>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                    </svg>
                    <p class="text-white mb-0"><?= htmlspecialchars($user["name"]) ?></p>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="/profile/?user_id=<?= $_SESSION['user_id'] ?>" class="dropdown-item">Profile</a></li>
                    <li><a href="#" class="dropdown-item">Settings</a></li>
                    <li><a href="/login/logout.php" class="dropdown-item">Log out</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Projects</li>
            </ol>
        </nav>
        <div class="card">
            <h1 class="card-title display-1">My Projects</h1>
            <!-- If user has ticket(s) then display a list, otherwise display default -->
            <?php if (empty($projList)): ?>
                <div class="card-body">
                    <p class="card-text">No projects!</p>
                </div>
            <?php else: ?>
                <div class="card-body">
                    <ul>
                        <?php foreach ($projList as $proj): ?>
                            <li><a href="/project/?org_id=<?= htmlspecialchars($proj['orgID']) ?> &proj_id= <?= htmlspecialchars($proj['projID']) ?>"><?= htmlspecialchars($proj['name']);  ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>