<?php
date_default_timezone_set('Europe/Amsterdam');

$accessToken = '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';


function fetchAllBranches($accessToken)
{
    // get all branches from github api
    $url = 'https://api.github.com/repos/LegeBeker/Sagrada/branches';
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: PHP',
                "Authorization: token $accessToken"
            ]
        ]
    ];
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);
    $branches = json_decode($response, true);

    return $branches;
}

function getAllTimeReports()
{
    $servername = "time-report-sagrada.cph5mepxu5k8.eu-central-1.rds.amazonaws.com";
    $username = "admin";
    $password = "VolkanSagrada2023";
    $dbname = "sagrada-overview";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT *, TIMESTAMPDIFF(SECOND, start, end) as total_seconds FROM time_report";

    $result = $conn->query($sql);

    $conn->close();

    return $result;
}

$branches = fetchAllBranches($accessToken);
?>


<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Time Report</title>
    <link rel='stylesheet' href='css/master.css'>
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' integrity='sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z' crossorigin='anonymous'>
</head>

<body>
    <nav class="navbar">
        <a class="navbar-brand" href="/time-report.php">Time Report</a>
        <a class="navbar-brand" href="/">Home</a>
        <a class="navbar-brand" href="https://github.com/LegeBeker/Sagrada" target="_blank">GitHub Page</a>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="content-wrapper">
                    <div class="content">
                        <h1>Time Report</h1>
                        <form action="submit.php" method="post">
                            <div class="form-group">
                                <label for="start-time">Person:</label>
                                <select id="person" name="person" class="form-control" required>
                                    <option value="" disabled selected>Select a person</option>
                                    <option value="volkan">Volkan</option>
                                    <option value="eren">Eren</option>
                                    <option value="tim">Tim</option>
                                    <option value="lars">Lars</option>
                                    <option value="mike">Mike</option>
                                    <option value="roy">Roy</option>
                                    <option value="euphrates">Euphrates</option>
                                    <option value="mourad">Mourad</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="start-time">Start Time:</label>
                                <input type="datetime-local" id="start-time" name="start_time" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="end-time">End Time:</label>
                                <input type="datetime-local" id="end-time" name="end_time" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="branch">Branch:</label>
                                <select id="branch" name="branch" class="form-control" required>
                                    <option value="" disabled selected>Select a branch</option>
                                    <?php foreach ($branches as $branch) { ?>
                                        <option value="<?php echo $branch['name']; ?>"><?php echo $branch['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <input type="text" id="description" name="description" class="form-control" maxlength="50" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-wrapper">
                    <div class="content">
                        <h1>Last reports</h1>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Person</th>
                                        <th scope="col">Total Time</th>
                                        <th scope="col">Branch</th>
                                        <th scope="col">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $timeReports = getAllTimeReports();
                                    if ($timeReports->num_rows > 0) {
                                        while ($row = $timeReports->fetch_assoc()) {
                                            $person = $row['person'];
                                            $totalTime = gmdate("H:i", $row['total_seconds']);
                                            $branch = $row['branch'];
                                            $description = $row['description'];
                                    ?>
                                            <tr>
                                                <td><?php echo $person; ?></td>
                                                <td><?php echo $totalTime; ?></td>
                                                <td><?php echo $branch; ?></td>
                                                <td><?php echo $description; ?></td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-w2jmWwRyy/xMjqZGY1YvYg/F28Wd6oGVH6HQ9l5U5D6gokL0Ubs1Z3qymjC4/rQo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QLvJoaZeEWA1Ai/6WKWbTvEJvGOfn" crossorigin="anonymous"></script>
</body>

</html>