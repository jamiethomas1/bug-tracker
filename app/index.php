<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/userController.class.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/classes/orgController.class.php');

session_start();

$_SESSION['org_id'] = '';

if (isset($_SESSION["user_id"])) {
    $dbh = new UserController();
    $orgHandle = new OrgController();
    
    $user = $dbh->getUserByID($_SESSION["user_id"]);
    $orgList = $orgHandle->getOrganisations($user["userID"]);
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous" defer></script>
    <title>Bug Tracker</title>
</head>
<body>
    <div class="navbar bg-dark" data-bs-theme="dark" role="navigation">
        <div class="container justify-content-end">
            <ul class="nav nav-pills">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#">Active</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#">Link</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#">Link</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link disabled" data-bs-toggle="tab" href="#">Disabled</a>
                </li>
            </ul>
            <div>
                <?php if (isset($user)): ?>
                    <p class="text-white"><?= htmlspecialchars($user["name"]) ?></p>
                    <img src="/img/user.png" alt="Profile Picture" class="avi img-thumbnail">
                    <p><a href="/login/logout.php">Log out</a></p>
                <?php else: ?>
                    <p><a href="/login/">Log in</a> or <a href="/sign-up">sign up</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Home</li>
            </ol>
        </nav>
        <div class="card">
            <!-- If user has organisation(s) then display a list, otherwise display default -->
            <?php if (empty($orgList)): ?>
                <h1 class="card-title display-1">Organisations</h1>
                <div class="card-body">
                    <p class="card-text">An organisation allows you to manage your projects. <a href="/create-organisation/">Create one now</a>.</p>
                </div>
            <?php else: ?>
                <h1 class="card-title display-1">My Organisations</h1>
                <div class="card-body">
                    <ul>
                        <?php foreach ($orgList as $org): ?>
                            <li><a href="/organisation/?org_id=<?= htmlspecialchars($org['orgID']) ?>"><?= htmlspecialchars($org['name']);  ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="card-text"><a href="/create-organisation/">Create another organisation</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!--<footer><a href="https://www.flaticon.com/free-icons/user" title="user icons">User icons created by Freepik - Flaticon</a></footer>-->
</body>
</html>