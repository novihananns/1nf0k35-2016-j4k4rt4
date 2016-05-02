<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Jenis Kelamin</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>

      <?php 
      $total =0;
      foreach ($showkelamin as $rows) { 
        $total = $rows->total;
      ?>
      <tr>
        <td><?php echo $rows->kelamin; ?></td>
        <td><?php echo $rows->jumlah;?></td>
        <td><?php echo number_format($rows->jumlah/$jumlahorang*100,2); echo " %";?></td>
      </tr>
      <?php } ?>
      <tr>
        <th>Total</th>
        <th><?php echo $jumlahorang; ?></th>
        <th><?php echo ($jumlahorang>0) ? $jumlahorang/$jumlahorang*100 : 0; echo " %";?></th>
      </tr>
      
    </table>
  </div>
  <div class="col-md-6">
    <div class="row" id="row1">
      <div class="chart">
        <canvas id="pieChart" height="240" width="511" style="width: 511px; height: 240px;"></canvas>
      </div>
    </div>
    <div class="row"> 
      <div class="col-md-3">
          
      </div>
      <div class="col-md-4">
          <div class="bux1"></div> &nbsp; <label>Laki-Laki</label>
      </div>
      <div class="col-md-5">
          <div class="bux"></div> &nbsp; <label>Perempuan</label>
      </div>
    </div>
  </div>
</div>
<style type="text/css">

      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #f56954;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #20ad3a;
        margin: 0;
        float: left;
      }
</style>
<?php  //print_r($color);?>
<script>
  $(function () { 
    
    //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [<?php 
           /* $i=0;
            foreach ($bar as $row ) { 
              if(isset($row['jumlah'])) $x = ($row['jumlah']);
              else                          $x = 0;
              if($i>0) echo ",";
              echo "
              {
              value: ".$x.",
              color: \"".$color[$i]."\",
              highlight: \"".$color[$i]."\",
              label: \"".$row['kelamin']."\"
              }";
              $i++;
            }*/
            $i=0;
         foreach ($showkelamin as $row) {
            if($i>0) echo ",";
            echo "
              {
              value: ";echo number_format($row->jumlah/$jumlahorang*100,2).",
              color: \"".$color[$i]."\",
              highlight: \"".$color[$i]."\",
              label: \"".$row->kelamin."\"
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
  });
</script>