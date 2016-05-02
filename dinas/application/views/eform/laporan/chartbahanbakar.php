<div class="row">
  <div class="col-md-6">
    <table class="table table-bordered table-hover">
      <tr>
        <th>Jenis Bahan Bakar</th>
        <th>Jumlah</th>
        <th>Persentase</th>
      </tr>
      <tr>
        <td>Listrik / Gas</td>
        <td><?php echo $listrik;?></td>
        <td><?php echo ($listrik>0) ? number_format($listrik/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Minyak Tanah</td>
        <td><?php echo $minyak;?></td>
        <td><?php echo ($minyak>0) ? number_format($minyak/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Arang / Kayu</td>
        <td><?php echo $arang;?></td>
        <td><?php echo ($arang>0) ? number_format($arang/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
      <tr>
        <td>Lainnya</td>
        <td><?php echo $lainnya;?></td>
        <td><?php echo ($lainnya>0) ? number_format($lainnya/$jumlahorang*100,2):0; echo " %";?></td>
      </tr>
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
  </div>
  </div>
  <div class="row"> 
    <div class="col-md-5"></div>
    <div class="col-md-7">
      <div class="col-md-3">
          <div class="bux"></div> &nbsp; <label>Listrik / Gas</label>
      </div>
      <div class="col-md-3">
          <div class="bux1"></div> &nbsp; <label>Minyak Tanah</label>
      </div>
      <div class="col-md-3">
          <div class="bux2"></div> &nbsp; <label>Arang / Kayu</label>
      </div>
      <div class="col-md-3">
          <div class="bux3"></div> &nbsp; <label>Lainnya</label>
      </div>
    </div>
  </div>    
<style type="text/css">

      .bux{
        width: 10px;
        padding: 10px; 
        margin-right: 40%;
        background-color: #e02a11;
        margin: 0;
        float: left;
      }
      .bux1{
        width: 10px;
        padding: 10px;
        background-color: #00BFFF;
        margin: 0;
        float: left;
      }
      .bux2{
        width: 10px;
        padding: 10px;
        background-color: #800080;
        margin: 0;
        float: left;
      }
      .bux3{
        width: 10px;
        padding: 10px;
        background-color: #20ad3a;
        margin: 0;
        float: left;
      }
</style>
<?php // print_r($bar);?>
<script>
  $(function () { 
    
    //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [<?php 
            
            echo "
              {
              value: ";echo number_format(($listrik>0) ? $listrik/$jumlahorang*100:0,2).",
              color: \"".'#e02a11'."\",
              highlight: \"".'#e02a11'."\",
              label: \"".'Listrik / Gas'."\"
              },
              {
              value: ";echo number_format(($minyak>0) ? $minyak/$jumlahorang*100:0,2).",
              color: \"".'#00BFFF'."\",
              highlight: \"".'#00BFFF'."\",
              label: \"".'Minyak Tanah'."\"
              },
              {
              value: ";echo number_format(($arang>0) ? $arang/$jumlahorang*100:0,2).",
              color: \"".'#800080'."\",
              highlight: \"".'#800080'."\",
              label: \"".'Arang / Kayu'."\"
              },
              {
              value: ";echo number_format(($lainnya>0) ? $lainnya/$jumlahorang*100:0,2).",
              color: \"".'#20ad3a'."\",
              highlight: \"".'#20ad3a'."\",
              label:  \"".'Lainnya'."\"
              }"; 
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