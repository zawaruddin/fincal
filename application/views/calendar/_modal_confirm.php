<?php echo form_open('delete', 'class="form-horizontal" id="confirm-form" style="margin:0;"', array('id' => $data->calendar_id,  'ids' => $id))?>	
<div class="modal-content">
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="text-center">Confirm delete Transaction</h3>
    </div>
    <div class="modal-body">
    	<div class="alert alert-block top">
			<h4 style="margin:0">Confirmation !</h4>
			<div>Are you sure to delete this transaction?</div>
			<div><strong>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<em><?php echo $data->name." : ".number_format($data->amount, 0, ',', '.') ?></em></strong></div>
		</div>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
    	<button type="submit" id="deleteTrans" class="btn btn-primary">Delete</button>
    </div>
<?php echo form_close()?>
</div>
<script>
$(document).ready(function(){
    $('#deleteTrans').click(function(e){
		e.preventDefault();
    	blockTab(ajaxModalPopup);
		$.post( "<?php echo site_url('delete') ?>",
				$("#confirm-form").serialize(),
				function( data ) {
					var msg;
					$('.alert.alert-success, .alert.alert-danger').remove();
					unblockTab(ajaxModalPopup);
					if(data.stat){
						msg = '<div class="alert alert-success"><i class="fa fa-check-circle"></i><strong> Success !</strong> '+data.msg+'</div>';
						$('.top').before(msg);
						trans.ajax.reload();
						totalAmount = data.total;
						$('.totalAmount').html(totalAmount);
						$(dCal).html(totalAmount);
						window.setTimeout(function () {
							ajaxModalPopup.modal('hide');
						}, 1500);
					}else{
						msg = '<div class="alert alert-danger"><i class="fa fa-check-circle"></i><strong> Failed !</strong> '+data.msg+'</div>';
						$('.top').before(msg);
					}
				},
		"json");
	});
});
</script>