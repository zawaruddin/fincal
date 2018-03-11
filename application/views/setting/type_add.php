<div class="modal-content">
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="text-center">Add Account Type</h3>
    </div>
    <div class="modal-body">
	<?php echo form_open('save', 'class="form-horizontal" id="edit-form"')?>	
		<input name="id" value="<?php echo $id?>" style="display:none;" type="hidden">
		<input name="ids" id="ids" value="" style="display:none;" type="hidden">
		<fieldset>
			<div class="control-group top">											
				<label class="control-label" for="event">Event</label>
				<div class="controls">
					<input class="span6" id="event" name="event" value="" type="text" maxlength="100">
				</div>
			</div>
			<div class="control-group">											
				<label class="control-label" for="account">Account</label>
				<div class="controls">
					<?php echo form_dropdown('account', $account, '', 'id="account"')?>
				</div>
			</div>
			<div class="control-group">											
				<label class="control-label" for="category">Category</label>
				<div class="controls">
					<?php echo form_dropdown('category', $category, '', 'id="category"')?>
				</div>
			</div>
			<div class="control-group">											
				<label class="control-label" for="time">Time</label>
				<div class="controls">
					<input class="span4" id="time" name="time" value="" placeholder="hh:mm" type="text">
				</div>
			</div>					
			<div class="control-group">											
				<label class="control-label" for="amount">Amount</label>
				<div class="controls">
					<input class="span4" id="amount" name="amount" value="" type="text" maxlength="10">
				</div>
			</div>
			
								
									
			<div class="control-group">
				<label class="control-label" for="submit">&nbsp;</label>
				<div class="controls">
					<button type="submit" class="btn btn-primary">Save</button> 
					<button type="reset" class="btn btn-default reset">Reset</button> 
				</div>
			</div>
		</fieldset>
	<?php echo form_close()?>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
    </div>
</div>
<script type="text/javascript"> 
$(document).ready(function() {
    $("#edit-form").validate({
    	rules:{
			event: {
				required: true,
				maxlength:100
			},
			time: {
				required: true,
				time: true
			},
			amount:{
				required: true,
				number: true,
				maxlength: 10
			}
		},
		submitHandler: function(form) {
			blockTab(ajaxModal);
			jQuery(form).ajaxSubmit({
				dataType:  'json',
				success: function(data){
					var msg;
					$('.alert.alert-success, .alert.alert-danger').remove();
				    unblockTab(ajaxModal);
					if(data.stat){
						msg = '<div class="alert alert-success"><i class="fa fa-check-circle"></i><strong> '+data.title+'</strong> '+data.msg+'</div>';
						$('.top').before(msg);
						trans.ajax.reload();
						$('.reset').trigger('click');
						totalAmount = data.total;
					}else{
						msg = '<div class="alert alert-danger"><i class="fa fa-check-circle"></i><strong> '+data.title+'</strong> '+data.msg+'</div>';
						$('.top').before(msg);
					}
					$('.totalAmount').html(totalAmount);
					$(dCal).html(totalAmount);
				}
			});
		}
    });
});
</script>