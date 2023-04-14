<?php include 'includes/header.php'; ?>

<?php $timeReports = getAllTimeReports($servername, $username, $password, $dbname) ?>

<div class="container-fluid">
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
                            <th scope="col">Branch</th>
                            <th scope="col">comment</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count(getFinishedTimers($timeReports)) == 0) {
                            echo '<tr><td colspan="4">No reports</td></tr>';
                        }

                        $finishedTimers = getFinishedTimers($timeReports);

                        usort($finishedTimers, function ($a, $b) {
                            return strtotime($b['end']) - strtotime($a['end']);
                        });

                        foreach ($finishedTimers as $row) {
                        ?>
                            <tr>
                                <td><?php echo ucfirst($row['person']); ?></td>
                                <td><?php echo $row['type']; ?></td>
                                <td><?php echo sprintf('%02d:%02d', floor($row['total_seconds'] / 3600), floor(($row['total_seconds'] - (floor($row['total_seconds'] / 3600) * 3600)) / 60)); ?></td>
                                <td><?php echo $row['branch']; ?></td>
                                <td><?php echo $row['comment']; ?></td>
                                <td>
                                    <form action="forms/delete-timer.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">x</button>
                                    </form>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>