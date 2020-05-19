<?php
    session_start();
    $_SESSION['query'] = "";
    if(!isset($_SESSION['first_run'])){
        $_SESSION['first_run'] = 1;
        $_SESSION['start_date'] = "07/05/2015";
        $_SESSION['end_date'] = "07/12/2015";
    }
    if(isset($_SESSION['keyword'])){ 
        $key = $_SESSION['keyword'];
    } else {
        $key ="";
    }
      
?>

<!DOCTYPE html>
<meta charset="utf-8">
<!--<link rel="stylesheet" type="text/css" src="jquery.dataTables.css">-->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<!-- load the d3.js library -->    	
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/d3-legend/2.25.6/d3-legend.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.10.13/features/mark.js/datatables.mark.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/g/mark.js(jquery.mark.min.js)"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/plug-ins/1.10.13/features/mark.js/datatables.mark.min.css"/>
<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script> -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>


<style> /* set the CSS */

    #div_table{
        /* position:absolute;
        top: 300px; */
    }
    #sentiment{
        /* position:absolute;
        top:50px;
        left:10px; */
        display: inline;
    }

    #media{
        /* position:absolute; 
        top:50px;
        left:500px;  */
        /* text-align: center; */
        display: inline;
    }

    .line_positive {
        fill: none;
        stroke: lime;
        stroke-width: 2.5px;
    }

    .line_negative {
        fill: none;
        stroke: red;
        stroke-width: 2.5px;
    }

    .line_neutral {
        fill:none;
        stroke: #6E6D6D;
        stroke-width: 2.5px;
    }

    .line_news {
        fill: none;
        stroke: blue;
        stroke-width: 2.5px;
    }

    .line_blog {
        fill: none;
        stroke: gold;
        stroke-width: 2.5px;
    }

    .column_nowrap {
        white-space: nowrap;
    }

    .legend rect {
        fill:white;
        stroke:black;
        opacity:0.8;
    }

    .btn-cancel {
        display: none;
    }

    /*div.tooltip {	
        position: absolute;			
        text-align: center;			
        width: 60px;					
        height: 28px;					
        padding: 2px;				
        font: 12px sans-serif;		
        background: lightsteelblue;	
        border: 0px;		
        border-radius: 8px;			
        pointer-events: none;			
    } */

    pre {
        overflow: auto;
        white-space: pre-wrap; 
    }

    span.highlight{
        font-weight: bold;
    }
    .gold {
        background-color: gold;
    }

    .blue{
        background-color: blue;
    }

    mark {
        padding: 0;
        background: red !important;
    }
</style>
<body>
    
    
    <form action="form_handle.php" method="POST" autocomplete="on">
            Enter Keyword:
            <input type="text" name="keyword" value="<?php echo $key; ?>">
            &nbsp;
            Date Range
            <input type="text" id="daterange" name="daterange" />
            <input type="submit">
    </form><br/><br/>
    <!-- <div id='tooltip' style='position:absolute;background-color:lightgray;padding:5px'></div> -->
    <div style="border:1px solid black;">
    <div id="sentiment"></div> 
    <div id="media"></div></div><hr>
    <div id="div_table">
        <table width="100%" class="display responsive" id="dataTable">
            <caption><b><font size="4px"> List of Articles</b></font> </caption>
            <thead>
                <tr>
                    <th></th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Source</th>
                    <th>Media</th>
                    
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Source</th>
                    <th>Media</th>
                    
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- Modal
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            Modal content-->
            <!-- <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div> -->
    </div> -->
    <script>
        $('input[name="daterange"]').daterangepicker({
            "minYear": 2015,
            "maxYear": 2015,
            "locale": {
                "format": "MM/DD/YYYY",
                "separator": " - ",
                "applyLabel": "Apply",
                "cancelLabel": "Reset",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Custom",
                "weekLabel": "W",
                "daysOfWeek": [
                    "Su",
                    "Mo",
                    "Tu",
                    "We",
                    "Th",
                    "Fr",
                    "Sa"
                ],
                "monthNames": [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December"
                ],
                "firstDay": 1
            },
            "showCustomRangeLabel": false,
            "startDate": "<?php echo $_SESSION['start_date'];?>",
            "endDate": "<?php echo $_SESSION['end_date'];?>",
            "minDate": "07/05/2015",
            "maxDate": "09/30/2015",
            "opens": "left",
            "cancelClass": "btn-cancel",
            "autoUpdateInput": true
        }, function(start, end, label) {
        //console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
        });

        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            //do something, like clearing an input
            // $.ajax({
            //     type: "POST",
            //     url: "update_date.php" ,
            //     data: { startDate: picker.startDate.format('YYYY-MM-DD'),
            //             endDate: picker.endDate.format('YYYY-MM-DD') },
            //     success : function() { 
                    
            //         // here is the code that will run on client side after running clear.php on server

            //         // function below reloads current page
            //         //location.reload();

            //     }
            // });
            // $('#daterange').data('daterangepicker').setStartDate('03/01/2014');
            // $('#daterange').data('daterangepicker').setEndDate('03/31/2014');
        });
    </script>
    <script type="text/javascript">
       
        function sentimentChart() {
            // set the dimensions and margins of the graph
            var margin = {top: 20, right: 20, bottom: 50, left: 50},
                width = 650 - margin.left - margin.right,
                height = 250 - margin.top - margin.bottom;

            // parse the date / time
            var parseTime = d3.timeParse("%Y-%m-%d");
            // var formatTime = d3.timeFormat("%e %B");

            // set the ranges
            var x = d3.scaleTime().range([0, width - 100]);
            var y = d3.scaleLinear().range([height, 0]);

            // define the line
            var valueline_positive = d3.line()
                .curve(d3.curveCatmullRom)
                .x(function(d) { return x(d.date); })
                .y(function(d) { return y(d.positive); });

            // define the line
            var valueline_neutral = d3.line()
                .curve(d3.curveCatmullRom)
                .x(function(d) { return x(d.date); })
                .y(function(d) { return y(d.neutral); });
            // define another line
            var valueline_negative = d3.line()
                .curve(d3.curveCatmullRom)
                .x(function(d) { return x(d.date); })
                .y(function(d) { return y(d.negative); });

            // append the svg obgect to the body of the page
            // appends a 'group' element to 'svg'
            // moves the 'group' element to the top left margin
            var svg = d3.select("#sentiment").append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform",
                    "translate(" + margin.left + "," + margin.top + ")");
            
            // var div = d3.select("body").append("div")
            //     .attr("class", "tooltip")
            //     .style("opacity", 0);
            
            // Get the data
            d3.json("query_all.php", function(error, data) {
                if (error) throw error;

                // format the data
                data.forEach(function(d) {
                    d.date = parseTime(d.date);
                    //d.positive = +d.positive;
                });
            
                //Scale the range of the data
                x.domain(d3.extent(data, function(d) { return d.date; }));
                if(d3.max(data, function(d) { return d.positive; }) > d3.max(data, function(d) { return d.neutral; })){
                    if(d3.max(data, function(d) { return d.positive; }) > d3.max(data, function(d) { return d.negative; })){
                        y.domain([0, d3.max(data, function(d) { return d.positive; }) + 10]);
                    } else {
                        y.domain([0, d3.max(data, function(d) { return d.negative; }) + 10]);
                    }
                } else if(d3.max(data, function(d) { return d.neutral; }) > d3.max(data, function(d) { return d.negative; })){
                    y.domain([0, d3.max(data, function(d) { return d.neutral; }) + 10]);
                } else {
                    y.domain([0, d3.max(data, function(d) { return d.negative; }) + 10]);
                }
                    

                // Add the valueline path.
                svg.append("path")
                    .data([data])
                    .attr("class", "line_positive")
                    .attr("d", valueline_positive)
                    // .on("mouseover", function(d) {
                    //     div.transition()
                    //         .duration(200)
                    //         .style("opacity", .9);
                    //     var point = Math.round(x.invert(d3.mouse(this)[0]), 0);
                    //     div.html(d[point].date + "<br/>" + d[point].positive)
                    //         .style("left", (d3.event.pageX) + "px")
                    //         .style("top", (d3.event.pageY - 28) + "px");
                    //     }
                    // )
                    // .on("mouseout", function(d) {
                    //     div.transition()
                    //         .duration(500)
                    //         .style("opacity", 0);
                    //     }
                    // );
                
                svg.append("path")
                    .data([data])
                    .attr("class", "line_negative")
                    .attr("d", valueline_negative);
            
                svg.append("path")
                    .data([data])
                    .attr("class", "line_neutral")
                    .attr("d", valueline_neutral);
                
                // text label for the x axis
                svg.append("text")             
                    .attr("transform",
                            "translate(" + (width/2) + " ," + 
                                        (height + margin.top + 25) + ")")
                    .style("text-anchor", "middle")
                    .text("Date");

                
                // Add the X Axis
                svg.append("g")
                    .attr("transform", "translate(0," + height + ")")
                    .call(d3.axisBottom(x))
                    .selectAll("text")
                    .attr("transform", "translate(-05,00)rotate(-25)")
                    .style("text-anchor", "end")
                    .style("font-size", 10);

                svg.append("text")
                    .attr("x", (width / 2))             
                    .attr("y", 0 - ((margin.top - 10) / 2))
                    .attr("text-anchor", "middle")  
                    .style("font-size", "16px") 
                    .style("text-decoration", "underline")  
                    .text("Sentiment vs Date Graph");
                // text label for the y axis
                svg.append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 0 - margin.left)
                    .attr("x",0 - (height / 2))
                    .attr("dy", "1em")
                    .style("text-anchor", "middle")
                    .text("No. of Articles"); 
                
                // Add the Y Axis
                svg.append("g")
                    .call(d3.axisLeft(y));
            
                svg.append("circle")
                    .attr("cx",width - 75)
                    .attr("cy",10)
                    .attr("r", 6)
                    .style("fill", "lime");
            
                svg.append("circle")
                    .attr("cx",width - 75)
                    .attr("cy",25)
                    .attr("r", 6)
                    .style("fill", "#6E6D6D");
                
                svg.append("circle")
                    .attr("cx",width - 75)
                    .attr("cy",40)
                    .attr("r", 6)
                    .style("fill", "Red");
                
                svg.append("text")
                    .attr("x", width - 60)
                    .attr("y", 10)
                    .text("Positive")
                    .style("font-size", "12px")
                    .attr("alignment-baseline","middle");
                
                svg.append("text")
                    .attr("x", width - 60)
                    .attr("y", 25)
                    .text("Neutral")
                    .style("font-size", "12px")
                    .attr("alignment-baseline","middle");
                    
                svg.append("text")
                    .attr("x", width - 60)
                    .attr("y", 40)
                    .text("Negative")
                    .style("font-size", "12px")
                    .attr("alignment-baseline","middle");
            });
        }

        function mediaChart(){
            // set the dimensions and margins of the graph
            var margin = {top: 20, right: 20, bottom: 50, left: 50},
                width = 600 - margin.left - margin.right,
                height = 250 - margin.top - margin.bottom;

            // parse the date / time
            var parseTime = d3.timeParse("%Y-%m-%d");

            // set the ranges
            var x = d3.scaleTime().range([0, width - 50]);
            var y = d3.scaleLinear().range([height, 0]);

            // define the line
            var valueline_news = d3.line()
                .curve(d3.curveCatmullRom)
                .x(function(d) { return x(d.date); })
                .y(function(d) { return y(d.news); });

            // define another line
            var valueline_blog = d3.line()
                .curve(d3.curveCatmullRom)
                .x(function(d) { return x(d.date); })
                .y(function(d) { return y(d.blog); });

            // append the svg obgect to the body of the page
            // appends a 'group' element to 'svg'
            // moves the 'group' element to the top left margin
            var svg = d3.select("#media").append("svg")
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
                });

                if(d3.max(data, function(d) { return d.news; }) > d3.max(data, function(d) { return d.blog; })){
                    y.domain([0, d3.max(data, function(d) { return d.news; }) + 10]);
                    //console.log(y);
                } else {
                    y.domain([0, d3.max(data, function(d) { return d.blog; }) + 10]);
                }
                //console.log(d3.max(data, function(d) { return d.news; }));
                // Scale the range of the data
                x.domain(d3.extent(data, function(d) { return d.date; }));
                
                // Add the valueline path.
                svg.append("path")
                    .data([data])
                    .attr("class", "line_news")
                    .attr("d", valueline_news)
                    .attr("data-legend", "News");

                svg.append("path")
                    .data([data])
                    .attr("class", "line_blog")
                    .attr("d", valueline_blog)
                    .attr("data-legend", "Blog");
                
                svg.append("text")
                    .attr("x", (width / 2))             
                    .attr("y", 0 - ((margin.top - 10) / 2))
                    .attr("text-anchor", "middle")  
                    .style("font-size", "16px") 
                    .style("text-decoration", "underline")  
                    .text("Blogs vs News Graph");

                // text label for the x axis
                svg.append("text")             
                    .attr("transform",
                            "translate(" + (width/2) + " ," + 
                                        (height + margin.top + 25) + ")")
                    .style("text-anchor", "middle")
                    .text("Date");
                // Add the X Axis
                svg.append("g")
                    .attr("transform", "translate(0," + height + ")")
                    .call(d3.axisBottom(x))
                    .selectAll("text")
                    .attr("transform", "translate(-05,00)rotate(-25)")
                    .style("text-anchor", "end")
                    .style("font-size", 10);

                // text label for the y axis
                svg.append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 0 - margin.left)
                    .attr("x",0 - (height / 2))
                    .attr("dy", "1em")
                    .style("text-anchor", "middle")
                    .text("No. of Articles");
                
                // Add the Y Axis
                svg.append("g")
                    .call(d3.axisLeft(y));
                
                svg.append("circle")
                    .attr("cx",width - 35)
                    .attr("cy",10)
                    .attr("r", 6)
                    .style("fill", "Blue");
                
                svg.append("circle")
                    .attr("cx",width - 35)
                    .attr("cy",25)
                    .attr("r", 6)
                    .style("fill", "gold");
                
                svg.append("text")
                    .attr("x", width - 20)
                    .attr("y", 10)
                    .text("News")
                    .style("font-size", "12px")
                    .attr("alignment-baseline","middle");
                
                svg.append("text")
                    .attr("x", width - 20)
                    .attr("y", 25)
                    .text("Blog")
                    .style("font-size", "12px")
                    .attr("alignment-baseline","middle");
            
            });

        }
        
        function table() {

            /* Formatting function for row details - modify as you need */
            function format ( d ) {
                // `d` is the original data object for the row
                console.log(d.content)
                return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:20px;">'+
                    '<tr>'+
                        '</tr><tr><td><font size="4px">Summary:</font></br><pre>'+
                        d.summary+'</pre></td>'+
                    '</tr><tr>' + 
                    '<td><font size="4px">Content:</font></br><pre>'+
                        d.content+'</pre></td>' +
                '</table>';
                // '<b><font size="4px">Summary:</font></b></br><pre>'+ d.content+'</pre>';
            }

            // $.extend(true, $.fn.dataTable.defaults, {
            //     mark: true
            // });

            var table = $('#dataTable').DataTable( {
                ajax: "query_table.php",
                mark: true;
                columns: [
                    {
                        "className":      'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    { "data": "title" },
                    { "data": "date" },
                    { "data": "source" },
                    { "data": "media" }
                ],
                dom: 'Bfrtip',
                buttons: [
                    'print'
                ],
                
                // responsive: {
                //     details: {
                //         display: $.fn.DataTable.Responsive.display.modal( {
                //             header: function ( row ) {
                //                 var data = row.data();
                //                 return 'Details for '+data[0]+' '+data[1];
                //             }
                //         } ),
                //         renderer: $.fn.DataTable.Responsive.renderer.tableAll()
                //     }
                // }
                
            } );

            // Add event listener for opening and closing details
            $('#dataTable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
        
                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                } 
            } );
        }
        
        sentimentChart();
        mediaChart(); 
        table();
        
    </script>
</body>
</html>