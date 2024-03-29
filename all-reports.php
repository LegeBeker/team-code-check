<?php include 'includes/header.php'; ?>

<?php $timeReports = getAllTimeReports() ?>

<div class="container-fluid">
    <div class="content-wrapper">
        <div class="content">
            <h1>Last reports</h1>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
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

                        $finishedTimers = getFinishedTimers($timeReports);

                        usort($finishedTimers, function ($a, $b) {
                            return strtotime($b['end']) - strtotime($a['end']);
                        });

                        foreach ($finishedTimers as $row) {
                        ?>
                            <tr>
                                <td><?php echo ucfirst($row['id']); ?></td>
                                <td class="<?php if (isset($_SESSION['username']) && ($_SESSION['username'] == $row['person'])) {
                                                echo "text-danger";
                                            } else {
                                                echo "text-secondary";
                                            }; ?>"><?php echo ucfirst($row['person']); ?></td>
                                <td><?php echo $row['type']; ?></td>
                                <td><?php echo sprintf('%02d:%02d', floor($row['total_seconds'] / 3600), floor(($row['total_seconds'] - (floor($row['total_seconds'] / 3600) * 3600)) / 60)); ?></td>
                                <td><?php echo date('Y/m/d', strtotime($row['end'])); ?></td>
                                <td><span class="badge badge-<?php if ($row['branch'] == "main") {
                                                                    echo "danger";
                                                                } else {
                                                                    echo "secondary";
                                                                }; ?>"> <?php echo $row['branch']; ?></span></td>
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
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>