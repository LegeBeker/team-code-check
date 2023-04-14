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

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    $conn->close();

    return $data;
}

$branches = fetchAllBranches($accessToken);
$timeReports = getAllTimeReports();
?>


<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Time Report</title>
    <link rel='stylesheet' href='css/master.css'>
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' integrity='sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z' crossorigin='anonymous'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                    if (count($timeReports) > 0) {
                                        foreach ($timeReports as $row) {
                                    ?>
                                            <tr>
                                                <td><?php echo $row['person']; ?></td>
                                                <td><?php echo sprintf('%02d:%02d', floor($row['total_seconds'] / 3600), floor(($row['total_seconds'] - (floor($row['total_seconds'] / 3600) * 3600)) / 60)); ?></td>
                                                <td><?php echo $row['branch']; ?></td>
                                                <td><?php echo $row['description']; ?></td>
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

                <div class="content-wrapper">
                    <div class="content">
                        <h1>Cumulative Hours</h1>
                        <canvas id="timeReportChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var data = <?php echo json_encode($timeReports); ?>;

        var colors = [
            '#F44336', // Red
            '#E91E63', // Pink
            '#9C27B0', // Purple
            '#673AB7', // Deep Purple
            '#3F51B5', // Indigo
            '#2196F3', // Blue
            '#03A9F4', // Light Blue
            '#00BCD4', // Cyan
            '#009688', // Teal
            '#4CAF50', // Green
            '#8BC34A', // Light Green
            '#CDDC39', // Lime
            '#FFEB3B', // Yellow
            '#FFC107', // Amber
            '#FF9800', // Orange
            '#FF5722' // Deep Orange
        ];

        // Group the data by person
        var groupedData = {};
        data.forEach(function(item) {
            var person = item.person.toLowerCase();
            if (!groupedData[person]) {
                groupedData[person] = {};
            }
            var dateStr = new Date(item.end).toDateString();
            if (!groupedData[person][dateStr]) {
                groupedData[person][dateStr] = 0;
            }
            var start = new Date(item.start);
            var end = new Date(item.end);
            var timeDiff = end.getTime() - start.getTime();
            var hours = timeDiff / (1000 * 60 * 60); // Convert milliseconds to hours
            groupedData[person][dateStr] += hours;
        });

        // Create the chart data
        var chartData = {
            labels: [], // x-axis labels
            datasets: [] // data series
        };

        // Loop through the grouped data and create a data series for each person
        var colorIndex = 0;
        for (var person in groupedData) {
            if (groupedData.hasOwnProperty(person)) {
                var cumulativeHours = 0;
                var timeData = [];
                for (var dateStr in groupedData[person]) {
                    if (groupedData[person].hasOwnProperty(dateStr)) {
                        var hours = groupedData[person][dateStr];
                        cumulativeHours += hours;
                        timeData.push(cumulativeHours);
                        if (chartData.labels.indexOf(dateStr) === -1) {
                            chartData.labels.push(dateStr);
                        }
                    }
                }

                chartData.datasets.push({
                    label: person.charAt(0).toUpperCase() + person.slice(1),
                    data: timeData,
                    fill: false,
                    borderColor: colors[colorIndex],
                    backgroundColor: colors[colorIndex],
                });
                colorIndex = (colorIndex + 1) % colors.length;
            }
        }

        // Create the chart
        var ctx = document.getElementById('timeReportChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
</body>

</html>