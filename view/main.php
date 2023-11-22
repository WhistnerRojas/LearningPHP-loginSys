<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta author="Whistner" name="Info" content="https://whistner.netlify.app" >
    <title>Sign Up | In Sys.</title>
    <link rel="stylesheet" href="./public/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
</head>
<body>
    {{content}}
    <?php
        if(!isset($_SESSION['unique_id'])){
            echo '<script src="./assets/js/showHidePass.js"></script>';
            echo '<script type="module" src="./assets/js/forms.js"></script>';
        }
        if(!isset($_GET['user_id'])){
            echo '<script src="assets/js/chatSearch.js"></script>';
        }
    ?>
</body>
</html>