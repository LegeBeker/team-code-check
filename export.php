<?php include 'includes/functions.php'; ?>
<?php session_start(); ?>

<?php $timeReports = getAllTimeReports() ?>

<link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' integrity='sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z' crossorigin='anonymous'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">



<div class="container-fluid">
    <div class="col-12 p-3">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-header">
                    <h1>Projectie</h1>
                </div>
                <div class="card-body">
                    <canvas id="projectionChart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 p-3">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-header">
                    <h1>Uren per week</h1>
                </div>
                <div class="card-body">
                    <canvas id="totalHoursChart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 p-3">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-header">
                    <h1>Cumulatieve uren</h1>
                </div>
                <div class="card-body">
                    <canvas id="timeReportChart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 p-3">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-header">
                    <h1>Uren per taak (Branch)</h1>
                </div>
                <div class="card-body">
                    <canvas id="branchChart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 p-3">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-header">
                    <h1>Uren per type</h1>
                </div>
                <div class="card-body">
                    <canvas id="typeChart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php for ($i = 1; $i <= 7; $i++) : ?>
        <div class="col-12 p-3">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-header">
                        <h1>Week <?php echo $i ?></h1>
                    </div>
                    <div class="card-body">
                        <canvas id="<?php echo $i; ?>Chart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    <?php endfor; ?>

    <div class="col-12 p-3">
        <div class="content-wrapper">
            <div class="content">
                <h1>Alle registraties</h1>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Naam</th>
                                <th scope="col">Type</th>
                                <th scope="col">Totale tijd</th>
                                <th scope="col">Datum</th>
                                <th scope="col">Taak (Branch)</th>
                                <th scope="col">comment</th>
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
                                    <td><?php echo ucfirst($row['person']); ?></td>
                                    <td><?php echo $row['type']; ?></td>
                                    <td><?php echo sprintf('%02d:%02d', floor($row['total_seconds'] / 3600), floor(($row['total_seconds'] - (floor($row['total_seconds'] / 3600) * 3600)) / 60)); ?></td>
                                    <td><?php echo date('Y/m/d', strtotime($row['end'])); ?></td>
                                    <td><?php echo $row['branch']; ?></td>
                                    <td><?php echo $row['comment']; ?></td>
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
</div>

<script>
    var finishedTimers = <?php echo json_encode(getFinishedTimers(getAllTimeReports())); ?>;
    var TypeTimes = <?php echo json_encode(getTypeTime()); ?>;
    var BranchTimes = <?php echo json_encode(getBranchTime($accessToken)); ?>;
    var actualHours = <?php echo json_encode(getActualHours()); ?>;
    var actualHoursPerson = <?php echo json_encode(getActualHoursPerson($persons)); ?>;
</script>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="js/general.js"></script>