
<!DOCTYPE html>
<meta charset="utf-8">
<style> /* set the CSS */

.line_news {
  fill: none;
  stroke: blue;
  stroke-width: 2.5px;
}

.line_blog {
    fill: none;
    stroke: yellow;
    stroke-width: 2.5px;
}

.line_mid {
    fill:none;
    stroke: brown;
    stroke-width: 2.5px;
}

</style>
<body>

<!-- load the d3.js library -->    	
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="crossfilter.js"></script>
<script>

// set the dimensions and margins of the graph
var margin = {top: 20, right: 20, bottom: 30, left: 50},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

// parse the date / time
var parseTime = d3.timeParse("%Y-%m-%d");

// set the ranges
var x = d3.scaleTime().range([0, width]);
var y = d3.scaleLinear().range([height, 0]);

// define the line
var valueline_news = d3.line()
    .x(function(d) { return x(d.date); })
    .y(function(d) { return y(d.news); });

// define the line
var valueline_mid = d3.line()
    .x(function(d) { return x(d.date); })
    .y(function(d) { return y(d.mid); });
// define another line
var valueline_blog = d3.line()
    .x(function(d) { return x(d.date); })
    .y(function(d) { return y(d.blog); });

// append the svg obgect to the body of the page
// appends a 'group' element to 'svg'
// moves the 'group' element to the top left margin
var svg = d3.select("body").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform",
          "translate(" + margin.left + "," + margin.top + ")");



// Get the data
d3.json("query_media.php", function(error, data) {
  if (error) throw error;

  // format the data
  data.forEach(function(d) {
      d.date = parseTime(d.date);
      //d.news = +d.news;
  });

  if(d3.max(data, function(d) { return d.news; }) > d3.max(data, function(d) { return d.blog; })){
    y.domain([0, d3.max(data, function(d) { return d.news; })]);
  } else {
    y.domain([0, d3.max(data, function(d) { return d.blog; })]);
  }
  // Scale the range of the data
  x.domain(d3.extent(data, function(d) { return d.date; }));
  

  // Add the valueline path.
  svg.append("path")
      .data([data])
      .attr("class", "line_news")
      .attr("d", valueline_news);

  svg.append("path")
    .data([data])
    .attr("class", "line_blog")
    .attr("d", valueline_blog);
  
  // Add the X Axis
  svg.append("g")
      .attr("transform", "translate(0," + height + ")")
      .call(d3.axisBottom(x));

  // Add the Y Axis
  svg.append("g")
      .call(d3.axisLeft(y));

});

</script>
</body>