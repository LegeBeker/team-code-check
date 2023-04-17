<?php include 'includes/header.php'; ?>

<?php $timeReports = getAllTimeReports() ?>

<div class="container-fluid">
    <div class="row">
        <?php if (isset($_SESSION['username'])) { ?>
            <div class="col-md-5">
                <div class="content-wrapper">
                    <div class="content">
                        <h1>Start Timer</h1>
                        <form action="forms/start-timer.php" method="post">
                            <div class="form-group">
                                <label for="person">Person:</label>
                                <select id="person" name="person" class="form-control" required>
                                    <option value="" disabled selected>Select a person</option>
                                    <?php foreach ($persons as $person) { ?>
                                        <option value="<?php echo $person; ?>" <?php if ($_SESSION['username'] == $person) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo ucfirst($person); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="type">Type:</label>
                                <select id="type" name="type" class="form-control" required>
                                    <?php foreach ($types as $type) { ?>
                                        <option value="<?php echo $type; ?>"><?php echo ucfirst($type); ?></option>
                                    <?php } ?>
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
                        <h1>Time Report</h1>
                        <form action="forms/submit.php" method="post">
                            <div class="form-group">
                                <label for="start-time">Person:</label>
                                <select id="person" name="person" class="form-control" required>
                                    <option value="" disabled selected>Select a person</option>
                                    <?php foreach ($persons as $person) { ?>
                                        <option value="<?php echo $person; ?>" <?php if ($_SESSION['username'] == $person) {
                                                                                    echo "selected";
                                                                                } ?>><?php echo ucfirst($person); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="type">Type:</label>
                                <select id="type" name="type" class="form-control" required>
                                    <?php foreach ($types as $type) { ?>
                                        <option value="<?php echo $type; ?>"><?php echo ucfirst($type); ?></option>
                                    <?php } ?>
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
            <div class="col-md-7">
            <?php } else { ?>
                <div class="col-md-12">
                <?php } ?>
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
                                            <td class="align-middle"><?php echo ucfirst($runningTimer['person']); ?></td>
                                            <td class="align-middle"><?php echo $runningTimer['type']; ?></td>
                                            <td class="align-middle"><?php echo $runningTimer['start']; ?></td>
                                            <td class="align-middle"><?php echo $runningTimer['branch']; ?></td>
                                            <td class="align-middle text-right">
                                                <input name="comment" id="comment<?php echo $runningTimer['id']; ?>" type="hidden">
                                                <input name="timer_id" value="<?php echo $runningTimer['id']; ?>" type="hidden">
                                                <?php if (isset($_SESSION['username']) && ($_SESSION['username'] == $runningTimer['person'] || $_SESSION['username'] == 'volkan')) { ?>
                                                    <button name="end_timer" data-timer_id="<?php echo $runningTimer['id']; ?>" class="btn btn-danger end-timer">End Timer</button>
                                                <?php } ?>
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
                        <h1>Last reports</h1>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Person</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Total Time</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Branch</th>
                                        <th scope="col">comment</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (count(getFinishedTimers($timeReports)) == 0) {
                                        echo '<tr><td colspan="7">No reports</td></tr>';
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
                                            <td><?php echo ucfirst($row['person']); ?></td>
                                            <td><?php echo $row['type']; ?></td>
                                            <td><?php echo sprintf('%02d:%02d', floor($row['total_seconds'] / 3600), floor(($row['total_seconds'] - (floor($row['total_seconds'] / 3600) * 3600)) / 60)); ?></td>
                                            <td><?php echo date('Y/m/d', strtotime($row['end'])); ?></td>
                                            <td><?php echo $row['branch']; ?></td>
                                            <td><?php echo $row['comment']; ?></td>
                                            <td>
                                                <?php if (isset($_SESSION['username']) && ($_SESSION['username'] == $row['person'] || $_SESSION['username'] == 'volkan')) { ?>
                                                    <form action="forms/delete-timer.php" method="post">
                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php
                                        if ($i == 7) {
                                            break;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="7" class="text-center"><a href="all-reports.php">See all reports</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
            </div>
    </div>

    <?php include 'includes/footer.php'; ?>