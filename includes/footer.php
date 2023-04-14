<script>
    var finishedTimers = <?php echo json_encode(getFinishedTimers(getAllTimeReports($servername, $username, $password, $dbname))); ?>;
    var TypeTimes = <?php echo json_encode(getTypeTime($servername, $username, $password, $dbname)); ?>;
    var BranchTimes = <?php echo json_encode(getBranchTime($servername, $username, $password, $dbname)); ?>;
    var actualHours = <?php echo json_encode(getActualHours($servername, $username, $password, $dbname)); ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
<script src="js/general.js"></script>

</body>

</html>