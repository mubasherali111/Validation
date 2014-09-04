<?php
if ($_POST) {
    require_once 'Validation.php';

    $rules = array(
        'email' => 'required|email:zaynali.com',
        'password' => 'required|min_length:8|max_length:30',
        'environment' => 'required|white_list:admin,user,guest'
    );

    $validation = new Validation();

    if ($validation->validate($_POST, $rules)) {
        echo "<pre>", print_r($_POST), "</pre>";
    } else {
        $validation->show_errors();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Validation</title>
    </head>
    <body>
        <form action="index.php" method="POST" novalidate>
            Email: <input type="email" name="email"> <br>
            Password: <input type="password" name="password"> <br>
            <input type="text" name="environment" value="admin">
            <input type="submit" value="submit">
        </form>
    </body>
</html>