<?php include 'includes/header.php'; ?>

<?php $returns = fetchAllCommits($accessToken, fetchAllBranches($accessToken)); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="content-wrapper">
                <div class="content">
                    <h1>Last 20 Commits</h1>
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
                                <?php
                                $i = 0;
                                foreach ($returns['commits'] as $commit) {
                                    if ($i++ >= 20) {
                                        break;
                                    }
                                ?>

                                    <tr onclick="parent.location='<?php echo $commit['html_url']; ?>'">
                                        <td style="width:250px"><?php echo $commit['branch'] ?></td>
                                        <td style="width:150px"><?php echo $commit['commit']['author']['name']; ?></td>
                                        <td style="width:150px"><?php echo date('H:i d/m', strtotime($commit['commit']['author']['date'])); ?></td>
                                        <td><?php echo $commit['commit']['message']; ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="4" style="text-align: center;">
                                        <a href="all-commits.php">See all commits</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="content-wrapper">
                <div class="content">
                    <h1>Branches</h1>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Branch Name</th>
                                    <th>Last author</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($returns['branches'] as $branch) { ?>
                                    <tr>
                                        <td><?php echo $branch['name']; ?></td>
                                        <td><?php echo $branch['last_author']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="content-wrapper">
                <div class="content">
                    <h1>Users</h1>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Last commit date</th>
                                    <th>Commit count</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($returns['users'] as $username => $user) { ?>
                                    <tr>
                                        <td><?php echo $username; ?></td>
                                        <td><?php echo date('H:i d/m', strtotime($user['last_commit_date'])); ?></td>
                                        <td><?php echo $user['commit_count']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>