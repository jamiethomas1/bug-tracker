<?php

include $_SERVER['DOCUMENT_ROOT'] . '/classes/userController.class.php';

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $em = htmlspecialchars($_POST['email']);
    $pw = htmlspecialchars($_POST['password']);
    
    $dbh = new UserController();
    
    if (!empty($_POST)) {
        $user = $dbh->getUserByEmail($em);
    }

    if ($user) {
        if (password_verify($pw, $user['password_hash'])) {
            session_start();
            session_regenerate_id();
            $_SESSION["user_id"] = $user["id"];
            header("Location: ../index.php");
            exit;
        }
    }
    $is_invalid = true;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in to continue</title>
</head>
<body>
    <h1>Login</h1>

    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>

    <form method="post" novalidate>
        <div>
            <input type="email" name="email" id="email" placeholder="Email"
                    value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        </div>
        <div>
            <input type="password" name="password" id="password" placeholder="Password">
        </div>
        <div>
            <p>New here? <a href="/sign-up">Create an account</a>.</p>
        </div>
        <div>
            <input type="submit" value="Log in">
        </div>
    </form>
</body>
</html>