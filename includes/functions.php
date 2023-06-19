<?php
date_default_timezone_set('Europe/Amsterdam');

// github access token
$accessToken = '';

$GLOBALS['repo'] = 'LegeBeker/Sagrada';

$persons = [
    "volkan",
    "eren",
    "tim",
    "lars",
    "mike",
    "roy",
];

$types = [
    "development",
    "testing",
    "review",
    "support"
];

function runQuery($query)
{
    $servername = "";
    $username = "";
    $password = "";
    $dbname = "";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query($query);

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    $conn->close();

    return $data;
}

function fetchCommitsFromBranch($accessToken, $branch)
{
    $commits = [];
    $page = 1;

    $cacheKey = "commits_$branch";
    $cacheFile = "cache/$cacheKey.cache";

    $headers = array(
        'User-Agent: PHP',
        "Authorization: token $accessToken"
    );

    if (file_exists($cacheFile)) {
        // check if cache is still valid
        $etag = file_get_contents("$cacheFile.etag");
        $opts = array(
            'http' => array(
                'method' => 'GET',
                'header' => array_merge($headers, array("If-None-Match: $etag"))
            )
        );

        $context = stream_context_create($opts);
        $response = @file_get_contents("https://api.github.com/repos/" . $GLOBALS['repo'] . "/commits?sha=$branch&per_page=1", false, $context);

        if ($http_response_header[0] == 'HTTP/1.1 304 Not Modified') {
            // cache is still valid
            $commits = unserialize(file_get_contents($cacheFile));
            return $commits;
        }
    }

    do {
        $opts = array(
            'http' => array(
                'method' => 'GET',
                'header' => $headers
            )
        );

        if (isset($etag)) {
            $opts['http']['header'][] = "If-None-Match: $etag";
        }

        $context = stream_context_create($opts);
        $response = file_get_contents("https://api.github.com/repos/" . $GLOBALS['repo'] . "/commits?sha=$branch&per_page=100&page=$page", false, $context);

        if (isset($http_response_header[0]) && $http_response_header[0] == 'HTTP/1.1 200 OK') {
            $etag = trim(str_replace('ETag:', '', $http_response_header[6]));
        }

        $newCommits = json_decode($response, true);
        $commits = array_merge($commits, $newCommits);

        $page++;
    } while (count($newCommits) === 100);

    file_put_contents($cacheFile, serialize($commits));
    file_put_contents("$cacheFile.etag", $etag);

    return $commits;
}

function fetchAllBranches($accessToken)
{
    $url = 'https://api.github.com/repos/' . $GLOBALS['repo'] . '/branches';
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
    return json_decode($response, true);
}

function fetchAllCommits($accessToken, $branches)
{
    $commits = [];
    $users = [];
    $branchesWithLastAuthor = [];

    $commitsMain = fetchCommitsFromBranch($accessToken, 'main');
    foreach ($commitsMain as $commit) {
        $commit['branch'] = 'main';
        $commits[] = $commit;
    }

    foreach ($branches as $branch) {
        $branchName = $branch['name'];

        if ($branch['name'] === 'main') {
            continue;
        }

        $branchCommits = fetchCommitsFromBranch($accessToken, $branchName);

        foreach ($branchCommits as $branchCommit) {
            $isDuplicate = false;

            foreach ($commits as $commit) {
                if ($commit['sha'] == $branchCommit['sha']) {
                    $isDuplicate = true;
                    break;
                }
            }

            if (!$isDuplicate) {
                $branchCommit['branch'] = $branchName;
                $commits[] = $branchCommit;
            }
        }

        $commitsInBranch = array_filter($commits, function ($commit) use ($branchName) {
            return $commit['branch'] === $branchName;
        });

        if (count($commitsInBranch) !== 0) {
            $lastCommit = array_values($commitsInBranch)[0];
            $lastAuthor = $lastCommit['commit']['author']['name'];
        } else {
            $lastAuthor = '';
        }

        $branchWithAuthor = $branch;
        $branchWithAuthor['last_author'] = $lastAuthor;
        $branchesWithLastAuthor[] = $branchWithAuthor;
    }

    foreach ($commits as $commit) {
        $author = $commit['commit']['author']['name'];

        if ($author == 'LegeBeker') {
            $author = 'Volkan Welp';
        }

        if ($author == 'TimBogersGitHub') {
            $author = 'Tim Bogers';
        }

        if (!array_key_exists($author, $users)) {
            $users[$author] = [
                'last_commit_date' => $commit['commit']['author']['date'],
                'commit_count' => 1
            ];
        } else {
            $users[$author]['commit_count']++;
            if (strtotime($commit['commit']['author']['date']) > strtotime($users[$author]['last_commit_date'])) {
                $users[$author]['last_commit_date'] = $commit['commit']['author']['date'];
            }
        }
    }

    uasort($users, function ($a, $b) {
        $dateA = strtotime($a['last_commit_date']);
        $dateB = strtotime($b['last_commit_date']);
        return ($dateA < $dateB) ? 1 : (($dateA > $dateB) ? -1 : 0);
    });


    usort($commits, function ($a, $b) {
        $dateA = strtotime($a['commit']['author']['date']);
        $dateB = strtotime($b['commit']['author']['date']);
        return ($dateA < $dateB) ? 1 : (($dateA > $dateB) ? -1 : 0);
    });

    return [
        'commits' => $commits,
        'branches' => $branchesWithLastAuthor,
        'users' => $users
    ];
}

function getAllTimeReports()
{
    $sql = "SELECT *, TIMESTAMPDIFF(SECOND, start, end) as total_seconds FROM time_report WHERE deleted = 0";
    return runQuery($sql);
}

function getTypeTime()
{
    $sql = "SELECT type, SUM(TIME_TO_SEC(TIMEDIFF(end, start)))/3600 AS total_hours FROM time_report WHERE deleted = 0 GROUP BY type";
    return runQuery($sql);
}

function getBranchTime($accessToken)
{
    $persons = [
        "volkan",
        "eren",
        "tim",
        "lars",
        "mike",
        "roy",
    ];

    $sql = "SELECT
    branch,
    SUM(TIME_TO_SEC(TIMEDIFF(end, start)))/3600 AS total_hours
FROM
    time_report
WHERE
    deleted = 0
    AND branch IS NOT NULL
GROUP BY
    branch
";
    $result = runQuery($sql);

    // if after the last space in a branch there is a person's name, remove it
    foreach ($result as $key => $value) {
        $branch = $value['branch'];
        $branchParts = explode('-', $branch);
        $firstPart = $branchParts[0];
        $lastPart = $branchParts[count($branchParts) - 1];

        $lastPart = strtolower($lastPart);

        if (in_array($lastPart, $persons)) {
            unset($branchParts[count($branchParts) - 1]);
        }

        // if first part is a number, remove it
        if (is_numeric($firstPart)) {
            unset($branchParts[0]);
        }

        $result[$key]['branch'] = implode(' ', $branchParts);
    }
    return $result;
}

function getActualHours()
{
    $sql = "SELECT week(start,1) AS week_number, SUM(TIMESTAMPDIFF(SECOND, start, end)) AS actual_seconds FROM time_report WHERE start BETWEEN '2023-04-17' AND DATE_ADD('2023-04-17', INTERVAL 7 WEEK) AND deleted = 0 GROUP BY week(start,1) ORDER BY week(start,1) ASC";

    // array with 7 elements, each element is 0
    $actualHours = array_fill(0, 7, 0);

    $i = 0;
    foreach (runQuery($sql) as $value) {
        // convert seconds to hours
        $actualHours[$i++] = $value['actual_seconds'] / 3600;
    }

    return $actualHours;
}

function getActualHoursPerson($persons)
{
    $sql = "SELECT WEEK(start,1) AS week_number, SUM(TIMESTAMPDIFF(SECOND, start, end)) AS actual_seconds, person FROM time_report WHERE start BETWEEN '2023-04-17' AND DATE_ADD('2023-04-17', INTERVAL 7 WEEK) AND deleted = 0 GROUP BY WEEK(start,1), person ORDER BY WEEK(start,1) ASC";

    // for each person, array with 7 elements, each element is 0
    foreach ($persons as $person) {
        $actualHoursPerson[$person] = array_fill(0, 7, 0);
        $i[$person] = 0;
    }

    foreach (runQuery($sql) as $value) {
        // convert seconds to hours
        $actualHoursPerson[$value['person']][$i[$value['person']]] = $value['actual_seconds'] / 3600;
        $i[$value['person']]++;
    }

    return $actualHoursPerson;
}

function getRunningTimers($timeReports)
{
    $runningTimers = array();
    foreach ($timeReports as $timeReport) {
        if ($timeReport['end'] == null) {
            $runningTimers[] = $timeReport;
        }
    }
    return $runningTimers;
}

function getFinishedTimers($timeReports)
{
    $finishedTimers = array();
    foreach ($timeReports as $timeReport) {
        if ($timeReport['end'] != null) {
            $finishedTimers[] = $timeReport;
        }
    }

    return $finishedTimers;
}
