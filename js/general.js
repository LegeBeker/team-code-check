var localTime = new Date().toLocaleString("en-US", {
    timeZone: "Europe/Amsterdam"
});

// show comment box when end timer button is clicked and set the value of the comment input to the value of the comment box and then submit the form
$('.end-timer').click(function () {
    var comment = prompt('Please enter a comment');
    $('#comment').val(comment);
    // when the prompt is closed, submit the form
    $(this).closest('form').submit();
});

var colors = [
    '#F44336', // Red
    '#E91E63', // Pink
    '#9C27B0', // Purple
    '#673AB7', // Deep Purple
    '#3F51B5', // Indigo
    '#2196F3', // Blue
    '#03A9F4', // Light Blue
    '#00BCD4', // Cyan
    '#009688', // Teal
    '#4CAF50', // Green
    '#8BC34A', // Light Green
    '#CDDC39', // Lime
    '#FFEB3B', // Yellow
    '#FFC107', // Amber
    '#FF9800', // Orange
    '#FF5722' // Deep Orange
];


// Create the chart data
var chartData = {
    labels: [], // x-axis labels
    datasets: [] // data series
};

// Group the data by person
var groupedData = {};
finishedTimers.forEach(function (item) {
    var person = item.person.toLowerCase();
    if (!groupedData[person]) {
        groupedData[person] = {};
    }
    var dateStr = new Date(item.end).toLocaleDateString();
    if (chartData.labels.indexOf(dateStr) === -1) {
        chartData.labels.push(dateStr);
    }
    if (!groupedData[person][dateStr]) {
        groupedData[person][dateStr] = 0;
    }
    var start = new Date(item.start);
    var end = new Date(item.end);
    var timeDiff = end.getTime() - start.getTime();
    var hours = timeDiff / (1000 * 60 * 60); // Convert milliseconds to hours
    groupedData[person][dateStr] += hours;
});

chartData.labels.sort();

// Loop through the grouped data and create a data series for each person
var colorIndex = 0;
for (var person in groupedData) {
    if (groupedData.hasOwnProperty(person)) {
        var cumulativeHours = 0;
        var timeData = [];
        for (var dateStr in groupedData[person]) {
            if (groupedData[person].hasOwnProperty(dateStr)) {
                var hours = groupedData[person][dateStr];
                cumulativeHours += hours;
                timeData[chartData.labels.indexOf(dateStr)] = cumulativeHours;
            }
        }

        chartData.datasets.push({
            label: person.charAt(0).toUpperCase() + person.slice(1),
            data: timeData,
            fill: false,
            borderColor: colors[colorIndex],
            backgroundColor: colors[colorIndex],
        });
        colorIndex = (colorIndex + 1) % colors.length;
    }
}

console.log(chartData);

// Create the chart
var ctx = document.getElementById('timeReportChart').getContext('2d');
var chart = new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});