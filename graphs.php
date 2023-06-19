<?php include 'includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-header">
                        <h1>Projection</h1>
                    </div>
                    <div class="card-body">
                        <canvas id="projectionChart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <?php for ($i = 1; $i <= 7; $i++) : ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php echo $i === 1 ? 'active' : ''; ?>" data-toggle="tab" href="#week<?php echo $i; ?>" role="tab" aria-controls="week<?php echo $i; ?>" aria-selected="<?php echo $i === 1 ? 'true' : 'false'; ?>">Week <?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <?php for ($i = 1; $i <= 7; $i++) : ?>
                                <div class="tab-pane <?php echo $i === 1 ? 'show active' : ''; ?>" id="week<?php echo $i; ?>" role="tabpanel" aria-labelledby="week<?php echo $i; ?>-tab">
                                    <canvas id="<?php echo $i; ?>Chart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-header">
                        <h1>Hours needed each week</h1>
                    </div>
                    <div class="card-body">
                        <canvas id="totalHoursChart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-header">
                        <h1>Cumulative Hours</h1>
                    </div>
                    <div class="card-body">
                        <canvas id="timeReportChart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-header">
                        <h1>Hours spent per branch</h1>
                    </div>
                    <div class="card-body">
                        <canvas id="branchChart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-header">
                        <h1>Hours spent per type</h1>
                    </div>
                    <div class="card-body">
                        <canvas id="typeChart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" role="tablist">
                            <?php foreach ($persons as $person) : ?>
                                <li class="nav-item">
                                    <a class="nav-link <?php if (isset($_SESSION['username'])) {
                                                            echo $person === $_SESSION['username'] ? 'active' : '';
                                                        } else {
                                                            echo $person === $persons[0] ? 'active' : '';
                                                        }  ?>" data-toggle="tab" href="#<?php echo $person; ?>" role="tab" aria-controls="<?php echo $person; ?>" aria-selected="<?php echo $person === $persons[0] ? 'true' : 'false'; ?>"><?php echo ucfirst($person); ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <?php foreach ($persons as $i => $person) : ?>
                                <div class="tab-pane <?php echo $i === 0 ? 'show active' : ''; ?>" id="<?php echo $person; ?>" role="tabpanel" aria-labelledby="<?php echo $person; ?>-tab">
                                    <canvas id="<?php echo $person; ?>Chart" class="personChart" style="width:100%;max-height:500px;height: 500px;"></canvas>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
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

<?php include 'includes/footer.php'; ?>