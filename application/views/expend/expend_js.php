<script src="<?php echo base_url()?>assets/js/Datatables/media/js/jquery.dataTables.js"></script>

<script>
	$(document).ready(function() {		
		trans  = $('#expend').DataTable({
			"length": 100,
			"lengthMenu": [50, "All"],
			"aoColumns": [
				{ "sWidth": "5%", "sClass": "text-center", "bSearchable": false, "bSortable": false },
				{ "sWidth": "35%", "bSearchable": true, "bSortable": true },
				{ "sWidth": "20%", "sClass": "hidden-sm", "bSearchable": true, "bSortable": true },
				{ "sWidth": "25%", "sClass": "hidden-sm", "bSearchable": true, "bSortable": true },
				{ "sWidth": "15%", "sClass": "text-right", "bSearchable": false, "bSortable": true }
			]
		});
	});
</script>