Validation
==========

A simple PHP input Validation Class.


<div dir="ltr" style="text-align: left;" trbidi="on">
<h2 style="text-align: left;">
<span style="font-family: Trebuchet MS, sans-serif; font-size: x-large;">Usage:</span></h2>
<br />
<ol style="text-align: left;">
<li><span style="font-family: 'Trebuchet MS', sans-serif;">At the top of your PHP page, require the "class.Validation.php".</span></li>
<li><span style="font-family: 'Trebuchet MS', sans-serif;">Set the rules according to your field names with pipe '|', sub rule with Colon ':'</span></li>
<li><span style="font-family: 'Trebuchet MS', sans-serif;">Call the method "validate" with your input data and rules.</span></li>
<li><span style="font-family: 'Trebuchet MS', sans-serif;">Check if there's any error occurred, Display it.</span></li>
</ol>

<h2>Example Code:</h2>
```html+php
<?php
if ($_POST) {
    require_once 'class.Validation.php';

    $rules = array(
        'email' => 'required|email:zaynali.com',
        'password' => 'required|min_length:8|max_length:30',
        'environment' => 'required|white_list:admin,user,guest'
    );

    $validation = new Validation();

    if ($validation->validate($_POST, $rules)) {
        // Validated Data
        echo "<pre>", print_r($_POST), "</pre>";
    } else {
        $validation->show_errors(['id' => 'errors', 'class' => 'fancy-errors'], TRUE);
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
```


