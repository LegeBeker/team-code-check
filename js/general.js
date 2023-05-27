var localTime = new Date().toLocaleString("en-US", {
    timeZone: "Europe/Amsterdam"
});

$('.end-timer').click(function () {
    var timerId = $(this).data('timer_id');
    var comment = prompt('Please enter a comment');

    $('#comment' + timerId).val(comment);

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

// Create an array of dates from 2023-03-29 to today
var startDate = new Date('2023-03-29');
var currentDate = new Date();
while (startDate <= currentDate) {
    var dateStr = startDate.toLocaleDateString();
    if (chartData.labels.indexOf(dateStr) === -1) {
        chartData.labels.push(dateStr);
    }
    startDate.setDate(startDate.getDate() + 1);
}

// Group the data by person
var groupedData = {};
finishedTimers.forEach(function (item) {
    var person = item.person.toLowerCase();
    if (!groupedData[person]) {
        groupedData[person] = {};
    }
    var dateStr = new Date(item.end).toLocaleDateString();
    if (!groupedData[person][dateStr]) {
        groupedData[person][dateStr] = 0;
    }
    var start = new Date(item.start);
    var end = new Date(item.end);
    var timeDiff = end.getTime() - start.getTime();
    var hours = timeDiff / (1000 * 60 * 60); // Convert milliseconds to hours
    groupedData[person][dateStr] += hours;
});

// Sort the labels as dates again
chartData.labels.sort(function (a, b) {
    return new Date(a) - new Date(b);
});

// Loop through the grouped data and create a data series for each person
var colorIndex = 0;
for (var person in groupedData) {
    if (groupedData.hasOwnProperty(person)) {
        var cumulativeHours = 0;
        var timeData = [];
        for (var i = 0; i < chartData.labels.length; i++) {
            var dateStr = chartData.labels[i];
            if (groupedData[person][dateStr]) {
                var hours = groupedData[person][dateStr];
                cumulativeHours += hours;
            }
            timeData.push(cumulativeHours);
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


// Create the chart
var ctx = document.getElementById('timeReportChart').getContext('2d');
var chart = new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
        scales: {
            y: {
                ticks: {
                    beginAtZero: true,
                    stepSize: 5,
                    max: 50,
                    callback: function (value, index, values) {
                        return value + 'h';
                    }
                }
            },
            x: {
                display: false
            }
        }
    }
});

var labels = [];
var data = [];

for (var i = 0; i < TypeTimes.length; i++) {
    labels.push(TypeTimes[i].type);
    data.push(TypeTimes[i].total_hours);
}

var ctx2 = document.getElementById('typeChart').getContext('2d');
var chart2 = new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            label: 'Hours Spent',
            data: data,
            backgroundColor: colors
        }]
    }
});

var labels = [];
var data = [];

for (var i = 0; i < BranchTimes.length; i++) {
    labels.push(BranchTimes[i].branch);
    data.push(BranchTimes[i].total_hours);
}

var targetHoursBranch = Array(BranchTimes.length).fill(8); // target of 28 hours per week

var ctx2 = document.getElementById('branchChart').getContext('2d');
var chart2 = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Target Hours',
                data: targetHoursBranch,
                type: 'line',
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
            },
            {
                label: 'Hours Spent',
                data: data,
                backgroundColor: colors
            }]
    },
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                ticks: {
                    beginAtZero: true,
                    stepSize: 5,
                    max: 50,
                    callback: function (value, index, values) {
                        return value + 'h';
                    }
                }
            },
            x: {
                ticks: {
                    fontSize: 14
                }
            }
        }
    }
});

var ctx3 = document.getElementById('totalHoursChart').getContext('2d');
var targetHours = Array(7).fill(84); // target of 28 hours per week

var chart3 = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7'], // replace with actual week numbers
        datasets: [
            {
                label: 'Target Hours',
                data: targetHours,
                type: 'line',
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
            },
            {
                label: 'Actual Hours',
                data: Object.values(actualHours),
                backgroundColor: function (value, index) {
                    return getColor(value["raw"]);
                },
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }
        ]
    },
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                ticks: {
                    beginAtZero: true,
                    stepSize: 5,
                    max: 50,
                    callback: function (value, index, values) {
                        return value + 'h';
                    }
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Hours per Week',
                    fontSize: 14
                }
            },
            x: {
                ticks: {
                    fontSize: 14
                }
            }
        },
        legend: {
            labels: {
                fontSize: 14
            }
        },
        title: {
            display: true,
            text: 'Project Hours Report',
            fontSize: 18,
            fontColor: '#333'
        },
        tooltips: {
            callbacks: {
                label: function (tooltipItem, data) {
                    var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                    return datasetLabel + ': ' + tooltipItem.yLabel + 'h';
                }
            }
        }
    }
});

function getColor(value) {
    if (value > 8) {
        return `hsl(120, 100%, 80%)`;
    }
    return `hsl(${(value / 8) * 120}, 100%, ${((value / 8) * 30) + 50}%)`;
}

// if there are person + chart elements, create a chart for each person
if (document.getElementsByClassName('personChart').length > 0) {
    for (var i = 0; i < Object.keys(actualHoursPerson).length; i++) {

        var person = Object.keys(actualHoursPerson)[i];
        var personHours = Object.values(actualHoursPerson)[i];

        var ctx = document.getElementById(person + 'Chart').getContext('2d');
        var targetHours = Array(7).fill(8); // target of 28 hours per week

        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7'], // replace with actual week numbers
                datasets: [
                    {
                        label: 'Target Hours',
                        data: targetHours,
                        type: 'line',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                    },
                    {
                        label: 'Actual Hours',
                        data: personHours,
                        backgroundColor: function (value, index) {
                            return getColor(value["raw"]);
                        },
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                    }
                ]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                animation: false,
                scales: {
                    y: {
                        ticks: {
                            beginAtZero: true,
                            max: 50,
                            callback: function (value, index, values) {
                                return value + 'h';
                            }
                        }
                    },
                    x: {
                        ticks: {
                            fontSize: 14
                        }
                    }
                },
                legend: {
                    labels: {
                        fontSize: 14
                    }
                }
            }
        });
    }
}
// Create the chart data
var chartData = {
    labels: [], // x-axis labels
    datasets: [] // data series
};

// Define the start and end dates
var startDate = new Date('2023-04-17');
var endDate = new Date('2023-06-04');

// Add the x-axis labels to the chart data
var currentDate = new Date(startDate);
while (currentDate <= endDate) {
    var dateStr = currentDate.toLocaleDateString();
    chartData.labels.push(dateStr);
    currentDate.setDate(currentDate.getDate() + 1);
}

// 0 for first day, 672 for last day
var projectionData = [];
projectionData[0] = 0;
projectionData[chartData.labels.length - 1] = 570;

chartData.datasets.push({
    label: 'Projection',
    data: projectionData,
    borderColor: 'rgba(54, 162, 235, 0.2)',
    backgroundColor: 'rgba(54, 162, 235, 0.2)',
    lineTension: 0,
});

// Calculate the y-axis values for the cumulative time worked line
var cumulativeData = [];
var cumulativeSeconds = 0;
for (var i = 0; i < finishedTimers.length; i++) {
    var timer = finishedTimers[i];
    var timerStart = new Date(timer.start);
    var timerEnd = new Date(timer.end);
    var timerSeconds = (timerEnd - timerStart) / 1000;
    cumulativeSeconds += timerSeconds;
    var timerDay = timerStart.toLocaleDateString();
    var timerDayIndex = chartData.labels.indexOf(timerDay);
    cumulativeData[timerDayIndex] = cumulativeSeconds / 3600;
}

// Add the cumulative time worked line to the chart data
chartData.datasets.push({
    label: 'Cumulative Time Worked',
    data: cumulativeData,
    fill: false,
    borderColor: 'rgba(54, 162, 235, 1)',
    backgroundColor: 'rgba(54, 162, 235, 1)',
    lineTension: 0,
});

// Create the chart
var ctx = document.getElementById('projectionChart').getContext('2d');
var chart = new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
        spanGaps: true,
        scales: {
            y: {
                ticks: {
                    beginAtZero: true,
                    callback: function (value, index, values) {
                        return value + 'h';
                    }
                },
            },
            x: {
                display: false,
            },
        },
    },
});

for (var i = 1; i <= 7; i++) {
    var weekHours = [];
    for (var j = 0; j < Object.keys(actualHoursPerson).length; j++) {
        weekHours.push(Object.values(actualHoursPerson)[j][i - 1]);
    }

    var ctx = document.getElementById(i + 'Chart').getContext('2d');
    var targetHours = Array(Object.keys(actualHoursPerson).length).fill(14); // target of 28 hours per week

    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(actualHoursPerson),
            datasets: [
                {
                    label: 'Target Hours',
                    data: targetHours,
                    type: 'line',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                },
                {
                    label: 'Actual Hours',
                    data: weekHours,
                    backgroundColor: function (value, index) {
                        return getColor(value["raw"]);
                    },
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                }
            ]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            animation: false,
            scales: {
                y: {
                    ticks: {
                        beginAtZero: true,
                        min: 10,
                        max: 50,
                        callback: function (value, index, values) {
                            return value + 'h';
                        }
                    }
                },
                x: {
                    ticks: {
                        fontSize: 14
                    }
                }
            },
            legend: {
                labels: {
                    fontSize: 14
                }
            },
            title: {
                display: true,
                text: 'Week ' + i,
                fontSize: 18,
                fontColor: '#333'
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + tooltipItem.yLabel + 'h';
                    }
                }
            }
        }
    });
}