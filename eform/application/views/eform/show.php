
<!-- Info boxes -->
<div class="row">
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-red"><i class="fa fa-plus-square"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Puskesmas</span>
        <span class="info-box-number" style="font-size:14px;">{j_puskesmas}<br>Unit</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-blue"><i class="fa fa-bank"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Kelurahan </span>
        <span class="info-box-number" style="font-size:14px;">{j_kelurahan}<br>Desa / Kelurahan</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->

  <!-- fix for small devices only -->
  <div class="clearfix visible-sm-block"></div>

  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-green"><i class="fa fa-users"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Data Keluarga</span>
        <span class="info-box-number" style="font-size:14px;">{j_kk}<br>Kepala Keluarga</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
  <div class="col-md-3 col-sm-6 col-xs-12">
    <div class="info-box">
      <span class="info-box-icon bg-yellow"><i class="fa fa-user"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Data Penduduk</span>
        <span class="info-box-number" style="font-size:14px;">{j_penduduk}<br>Orang</span>
      </div><!-- /.info-box-content -->
    </div><!-- /.info-box -->
  </div><!-- /.col -->
</div><!-- /.row -->

<div class="row">
  <div class="col-md-7">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Data per Kelurahan </h3>
        <br><br>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div><!-- /.box-header -->
        <div class="box-body">
        <div class="row" id="row_dim">
          <div class="chart">
            <canvas id="barChart" height="240" width="511" style="width: 511px; height: 300px;min-height: 300px;"></canvas>
          </div>
        </div>
        <!--<div class="row">
          <div class="col-md-2">
              <div class="bux"></div> &nbsp; <label>Baik</label>
          </div>
          <div class="col-md-3">
              <div class="bux1"></div> &nbsp; <label>Rusak Ringan</label>
          </div>
          <div class="col-md-3">
              <div class="bux2"></div> &nbsp; <label>Rusak Berat</label>
          </div>
        </div>-->
        <div class="row">
          <div class="col-md-6">
              <div class="bux"></div> &nbsp; <label>Laki-laki</label>
          </div>
          <div class="col-md-6">
              <div class="bux1"></div> &nbsp; <label>Perempuan</label>
          </div>
        </div>

      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div><!-- /.col -->
  <div class="col-md-5">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Data Penduduk </h3>        
        <br><br>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
      </div><!-- /.box-header -->
        <div class="box-body">
        <div class="row" id="row1">
          <div class="chart">
            <canvas id="pieChart" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
          </div>
        </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div><!-- /.col -->
</div><!-- /.row -->

<script>
  $(function () { 
    $("#menu_dashboard").addClass("active");
    $("#menu_morganisasi").addClass("active");

      var areaChartData = {
        labels: [<?php 
        $i=0;
        foreach ($bar as $row ) { 
          if($i>0) echo ",";
            echo "\"".$row['kelurahan']."\"";
          $i++;
        } ?>],
        datasets: [
          {
            label: "Laki-laki",
            fillColor: "#20ad3a",
            strokeColor: "#20ad3a",
            pointColor: "#20ad3a",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['j_lakilaki']))  $x = ($row['j_lakilaki']);
              else                           $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
          {
            label: "Perempuan",
            fillColor: "#ffb400",
            strokeColor: "#ffb400",
            pointColor: "#ffb400",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php 
            $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['j_perempuan']))  $x = ($row['j_perempuan']);
              else                            $x = 0;

              if($i>0) echo ",";
              echo "\"".$x."\"";
              $i++;
            } ?>]
          },
        ]
      };

      var areaChartOptions = {
        showScale: true,
        scaleShowGridLines: false,
        scaleGridLineColor: "rgba(0,0,0,.05)",
        scaleGridLineWidth: 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        bezierCurve: true,
        bezierCurveTension: 0.3,
        pointDot: false,
        pointDotRadius: 4,
        pointDotStrokeWidth: 1,
        pointHitDetectionRadius: 20,
        datasetStroke: true,
        datasetStrokeWidth: 2,
        datasetFill: true,
        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
        maintainAspectRatio: false,
        responsive: true
      };

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [<?php 
            $i=0;
            foreach ($pie as $row) { 
              if(isset($row['jml'])) $x = ($row['jml']);
              else                   $x = 0;
              if($i>0) echo ",";
              echo "
              {
              value: ".$x.",
              color: \"".$color[$i]."\",
              highlight: \"".$color[$i]."\",
              label: \"".$row['label']."\"
              }";
              $i++;
            }
            ?>
        ];
        var pieOptions = {
          segmentShowStroke: true,
          segmentStrokeColor: "#fff",
          segmentStrokeWidth: 2,
          percentageInnerCutout: 40, // This is 0 for Pie charts
          animationSteps: 100,
          animationEasing: "easeOutBounce",
          animateRotate: true,
          animateScale: false,
          responsive: true,
          maintainAspectRatio: false,
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
        };
        pieChart.Doughnut(PieData, pieOptions);


        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData;
        var barChartOptions = {
          scaleBeginAtZero: true,
          scaleShowGridLines: true,
          scaleGridLineColor: "rgba(0,0,0,.05)",
          scaleGridLineWidth: 1,
          scaleShowHorizontalLines: true,
          scaleShowVerticalLines: true,
          barShowStroke: true,
          barStrokeWidth: 2,
          barValueSpacing: 5,
          barDatasetSpacing: 1,
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          responsive: true,
          maintainAspectRatio: false
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);

      });
    </script>
    <style type="text/css">
      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #20ad3a;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #ffb400;
        margin: 0;
        float: left;
      }
    </style>
