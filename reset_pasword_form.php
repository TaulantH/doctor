<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        .background{
            background-color: #252422;
            
        }
        body{
            background-color: #FFFCF2;
        }
        .color{
            color: #EB5E28 !important;
        }
        .loginBtn{
            background-color: #EB5E28;
            border: none;
        }
    </style>
</head>
<body>
    <h1>Reset Password</h1>
    <?php if (isset($error)) : ?>
        <p><?php echo $error; ?></p>
    <?php else : ?>
        <form method="post" action="reset_password.php">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
            <label for="new_password">New Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="reset_password">Reset Password</button>
        </form>
    <?php endif; ?>
</body>
</html>
