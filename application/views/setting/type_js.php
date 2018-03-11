<script src="<?php echo base_url()?>assets/js/jquery-validation/lib/jquery.form.js"></script>
<script src="<?php echo base_url()?>assets/js/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="<?php echo base_url()?>assets/js/jquery-validation/dist/additional-methods.js"></script>
<script src="<?php echo base_url()?>assets/js/bootstrap-modal/js/bootstrap-modal.js"></script>
<script src="<?php echo base_url()?>assets/js/bootstrap-modal/js/bootstrap-modalmanager.js"></script>
<script src="<?php echo base_url()?>assets/js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script src="<?php echo base_url()?>assets/js/blockUI/jquery.blockUI.js"></script>
<script src="<?php echo base_url()?>assets/js/modals.js"></script>
<script src="<?php echo base_url()?>assets/js/block.js"></script>

<script src="<?php echo base_url()?>assets/js/Datatables/media/js/jquery.dataTables.js"></script>

<script>
	var ajaxModal = $('#ajax-modal');
	var ajaxModalPopup = $('#ajax-modal-popup');
	var acc_type;
	
	$(document).ready(function() {		
		acc_type  = $('#type_table').DataTable( {
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?php echo site_url("type_load")?>",
				"type": "POST",
				"data": {'<?php echo $this->security->get_csrf_token_name()?>' : '<?php echo $this->security->get_csrf_hash()?>'}
			},
			"aoColumns": [
				{ "data": "rownum", "sWidth": "5%", "sClass": "text-center", "bSearchable": false, "bSortable": false },
				{ "data": "name", "sWidth": "85%", "bSearchable": true, "bSortable": true },
				{ "data": "action", "sWidth": "10%", "sClass": "text-center", "bSearchable": false, "bSortable": false }
			]
		} );
	});
</script>