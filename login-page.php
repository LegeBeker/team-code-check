<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background: #f7f7f7;
        }

        .login-form {
            width: 340px;
            margin: 50px auto;
            font-size: 15px;
        }

        .login-form form {
            margin-bottom: 15px;
            background: #fff;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        .login-form h2 {
            margin: 0 0 15px;
        }

        .form-control,
        .btn {
            min-height: 38px;
            border-radius: 2px;
        }

        .btn {
            font-size: 15px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input[type="checkbox"] {
            margin-top: 3px;
        }

        .small {
            font-size: 12px;
        }

        .text-muted {
            color: #999;
        }
    </style>
</head>

<body>
    <?php if (isset($_GET['login-error']) && $_GET['login-error'] == 1) { ?>
        <div class="alert alert-danger" role="alert">
            <strong>Oh no!</strong> Your username or password is incorrect.
        </div>
    <?php } ?>

    <div class="login-form">
        <form action="/forms/login.php" method="post">
            <h2 class="text-center">Log in</h2>
            <div class="form-group">
                <input name="username" type="username" class="form-control" placeholder="Username" required="required">
            </div>
            <div class="form-group">
                <input name="password" type="password" class="form-control" placeholder="Password" required="required">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Log in</button>
            </div>
        </form>
    </div>

</body>

</html>