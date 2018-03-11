<script src="<?php echo base_url()?>assets/js/excanvas.min.js"></script> 
<script src="<?php echo base_url()?>assets/js/chart.min.js" type="text/javascript"></script> 
<script>
	var lineChartData = {
        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        datasets: [
			{
			    fillColor: "rgba(220,220,220,0.5)",
			    strokeColor: "rgba(220,220,220,1)",
			    pointColor: "rgba(220,220,220,1)",
			    pointStrokeColor: "#fff",
			    labels: ["Expend"],
			    data: expendData
			},
			{
			    fillColor: "rgba(151,187,205,0.5)",
			    strokeColor: "rgba(151,187,205,1)",
			    pointColor: "rgba(151,187,205,1)",
			    pointStrokeColor: "#fff",
			    labels: ["Income"],
			    data: incomeData
			}
		]

    }

    var myLine = new Chart(document.getElementById("area-chart").getContext("2d")).Line(lineChartData);
</script>