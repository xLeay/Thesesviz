<?php
include '../../includes/auth/conf.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $secured_pass = password_hash($password, PASSWORD_DEFAULT);

    $connected = false;

    if ($_GET['from'] == 'connect') {
        $sql = "SELECT * FROM user WHERE login = '$username'";
        $result = $conn->query($sql);
        if ($result->rowCount() > 0) {
            $row = $result->fetch();
            if (password_verify($password, $row['password'])) {
                $connected = true;
            }
        }
        
    } elseif ($_GET['from'] == 'signup') {
        $sql = "INSERT INTO user (login, password) VALUES ('$username', '$secured_pass')";
        $result = $conn->query($sql);
        if ($result) {
            $connected = true;
        }
    }
}

if ($connected) {
    require '../../includes/components/connected.php';
}

if (!$connected) {
    require '../../includes/components/notconnected.php';
}

?>