<?php include 'includes/header.php'; ?>

<?php $timeReports = getAllTimeReports($servername, $username, $password, $dbname) ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="content-wrapper">
                <div class="content">
                    <h1>Start Timer</h1>
                    <form action="forms/start-timer.php" method="post">
                        <div class="form-group">
                            <label for="person">Person:</label>
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
                            <label for="branch">Branch:</label>
                            <select id="branch" name="branch" class="form-control" required>
                                <option value="" disabled selected>Select a branch</option>
                                <?php foreach (fetchAllBranches($accessToken) as $branch) { ?>
                                    <option value="<?php echo $branch['name']; ?>"><?php echo $branch['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Start Timer</button>
                    </form>
                </div>
            </div>
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
                                    <th scope="col">comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (count(getFinishedTimers($timeReports)) == 0) {
                                    echo '<tr><td colspan="4">No reports</td></tr>';
                                }
                                $i = 0;

                                $finishedTimers = getFinishedTimers($timeReports);

                                usort($finishedTimers, function ($a, $b) {
                                    return strtotime($b['end']) - strtotime($a['end']);
                                });

                                foreach ($finishedTimers as $row) {
                                    $i++;
                                ?>
                                    <tr>
                                        <td><?php echo $row['person']; ?></td>
                                        <td><?php echo sprintf('%02d:%02d', floor($row['total_seconds'] / 3600), floor(($row['total_seconds'] - (floor($row['total_seconds'] / 3600) * 3600)) / 60)); ?></td>
                                        <td><?php echo $row['branch']; ?></td>
                                        <td><?php echo $row['comment']; ?></td>
                                    </tr>
                                <?php
                                    if ($i == 5) {
                                        break;
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
        <div class="col-md-8">
            <div class="content-wrapper">
                <div class="content">
                    <h1>Running Timers</h1>
                    <table class="table table-hover">
                        <tbody>
                            <?php
                            if (count(getRunningTimers($timeReports)) == 0) {
                                echo '<tr><td colspan="4" class="text-muted text-center">No running timers</td></tr>';
                            }
                            foreach (getRunningTimers($timeReports) as $runningTimer) { ?>
                                <form action="forms/stop-timer.php" method="post">
                                    <tr>
                                        <td class="align-middle"><?php echo $runningTimer['person']; ?></td>
                                        <td class="align-middle"><?php echo $runningTimer['start']; ?></td>
                                        <td class="align-middle"><?php echo $runningTimer['branch']; ?></td>
                                        <td class="align-middle text-right">
                                            <input name="comment" id="comment<?php echo $runningTimer['id']; ?>" type="hidden">
                                            <input name="timer_id" value="<?php echo $runningTimer['id']; ?>" type="hidden">
                                            <button name="end_timer" data-timer_id="<?php echo $runningTimer['id']; ?>" class="btn btn-danger end-timer">End Timer</button>
                                        </td>
                                    </tr>
                                </form>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="content-wrapper">
                <div class="content">
                    <h1>Time Report</h1>
                    <form action="forms/submit.php" method="post">
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
                            <label for="end-time">End Time: (if not filled, it will be running)</label>
                            <input type="datetime-local" id="end-time" name="end_time" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="branch">Branch:</label>
                            <select id="branch" name="branch" class="form-control" required>
                                <option value="" disabled selected>Select a branch</option>
                                <?php foreach (fetchAllBranches($accessToken) as $branch) { ?>
                                    <option value="<?php echo $branch['name']; ?>"><?php echo $branch['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="comment">comment:</label>
                            <input type="text" id="comment" name="comment" class="form-control" maxlength="50">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>