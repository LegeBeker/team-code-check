<?php include 'includes/functions.php'; ?>
<?php session_start(); ?>

<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Sagrada Overview</title>
    <link rel='stylesheet' href='css/master.css'>
    <link rel="icon" href="https://volkanwelp.com/img/favicon.ico" type="image/x-icon">
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' integrity='sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z' crossorigin='anonymous'>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">
            <img src="https://volkanwelp.com/img/favicon.ico" height="25px" class="mr-2" alt="">
            Sagrada
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/time-report.php">Time Report</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/graphs.php">Graphs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://github.com/LegeBeker/Sagrada" target="_blank">
                        GitHub Page
                        <svg width="12" height="12" viewBox="0 0 24 24" class="ml-1">
                            <path class="link-icon" d="M21 13v10h-21v-19h12v2h-10v15h17v-8h2zm3-12h-10.988l4.035 4-6.977 7.07 2.828 2.828 6.977-7.07 4.125 4.172v-11z"></path>
                        </svg>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['username'])) { ?>
                    <li class="nav-item">
                        <a class="btn btn-secondary" href="forms/logout.php">Logout</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="btn btn-secondary" href="/login-page.php">Login</a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>