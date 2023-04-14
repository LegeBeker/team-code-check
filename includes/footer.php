<script>
    var finishedTimers = <?php echo json_encode(getFinishedTimers(getAllTimeReports())); ?>;
    var TypeTimes = <?php echo json_encode(getTypeTime()); ?>;
    var BranchTimes = <?php echo json_encode(getBranchTime()); ?>;
    var actualHours = <?php echo json_encode(getActualHours()); ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="js/general.js"></script>

</body>

</html>