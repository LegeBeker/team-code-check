<?php include 'includes/header.php'; ?>

<?php $returns = fetchAllCommits($accessToken, fetchAllBranches($accessToken)); ?>

<div class="container-fluid">
    <div class="content-wrapper">
        <div class="content">
            <h1>All Commits</h1>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($returns['commits'] as $commit) { ?>
                            <tr onclick="parent.location='<?php echo $commit['html_url']; ?>'">
                                <td style="width:250px"><?php echo $commit['branch'] ?></td>
                                <td style="width:150px"><?php echo $commit['commit']['author']['name']; ?></td>
                                <td style="width:150px"><?php echo date('H:i d/m', strtotime($commit['commit']['author']['date'])); ?></td>
                                <td><?php echo $commit['commit']['message']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>