"use strict";

// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

  // Create the data table.
  var data = new google.visualization.arrayToDataTable([
    ['Item', 'Count'],
    ['1', 50],
    ['2', 25],
    ['3', 10]
  ]);

  // Set chart options
  var options = {'title':'Most popular items',
                 'width':400,
                 'height':300,
                 'legend':{'position':'none'}};

  // Instantiate and draw our chart, passing in some options.
  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}
