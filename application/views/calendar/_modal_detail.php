<?php 
	$total = (($total < 0)? '<font class="red">' : '<font class="blue">').number_format($total, 0, ',', '.').'</font>'; 
?>


<div class="modal-content">
    <div class="modal-header">
    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="text-center">Detail Transaction in <?php echo "{$d} {$m} {$y}"?></h3>
    </div>
    <div class="modal-body">
    	<table id="detail-transaction" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th class="text-center">No</th>
						<th class="text-left">Event</th>
						<th class="text-left hidden-sm">Account</th>
						<th class="text-left hidden-sm">Category</th>
						<th class="text-left hidden-sm">Time</th>
						<th class="text-right">Amount</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th></th>
						<th></th>
						<th class="text-left hidden-sm"><?php echo form_dropdown('account_filter', array_merge(array(0 => '- All -'), $account))?></th>
						<th class="text-left hidden-sm"><?php echo form_dropdown('category_filter', array_merge(array(0 => '- All -'), $category))?></th>
						<th class="hidden-sm"></th>
						<th class="text-right"><strong><span class="totalAmount"><?php echo $total;?></span></strong></th>
						<th></th>
					</tr>
				</tfoot>
			</table>
			<hr>
			<?php echo form_open('save', 'class="form-horizontal" id="edit-form"')?>
			<div class="row top">
	      		<div class="span6">	
					<input name="id" value="<?php echo $id?>" style="display:none;" type="hidden">
					<input name="ids" id="ids" value="" style="display:none;" type="hidden">
					<fieldset>
						<div class="control-group">											
							<label class="control-label" for="event">Event</label>
							<div class="controls">
								<input tabindex="1" class="span5" id="event" name="event" value="" type="text" maxlength="100">
							</div>
						</div>
						<div class="control-group">											
							<label class="control-label" for="amount">Amount</label>
							<div class="controls">
								<input autocomplete="off" tabindex="2" class="span2 text-right" id="amount" name="amount" value="" type="text" maxlength="18">
							</div>
						</div>
						<div class="control-group">											
							<label class="control-label" for="time">Time</label>
							<div class="controls">
								<input tabindex="3" class="span2" id="time" name="time" value="<?php echo date('H:i').':00'?>" placeholder="hh:mm" type="text">
							</div>
						</div>					
					</fieldset>
				</div>
				<div class="span6">
					<fieldset>
						<div class="control-group">											
							<label class="control-label" for="account">Account</label>
							<div class="controls">
								<?php echo form_dropdown('account', $account, '', 'id="account" tabindex="4"')?>
							</div>
						</div>
						<div class="control-group">											
							<label class="control-label" for="category">Category</label>
							<div class="controls">
								<?php echo form_dropdown('category', $category, '', 'id="category" tabindex="5"')?>
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
				</div>
			</div>
		<?php echo form_close()?>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
    </div>
</div>

<script type="text/javascript"> 
	var trans;
	var totalAmount = '<?php echo $total ?>';
	var dCal = '.d<?php echo $d ?>';
	
	$(document).ready(function() {
		$('#event').focus();
		$("#amount").inputmask("decimal",{
            isNumeric: true,
            groupSeparator: ".",//",", // | "."
            radixPoint: ",",
            groupSize: 3,
            autoGroup: true,
            allowMinus: false});
		
		$('#time').timepicker({
			showMeridian: false,
			showSeconds: true,
			snapToStep: true,
			maxHours: 24,
			icons:{
				up: 'icon-small icon-chevron-up',
				down: 'icon-small icon-chevron-down',
			}
		});
		
		trans  = $('#detail-transaction').DataTable( {
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?php echo site_url("getDetail")?>",
				"type": "POST",
				"data": {'id': <?php echo ($id)? $id : 'null'?>, '<?php echo $this->security->get_csrf_token_name()?>' : '<?php echo $this->security->get_csrf_hash()?>'}
			},
			"aoColumns": [
				{ "data": "rownum", "sWidth": "4%", "sClass": "text-center", "bSearchable": false, "bSortable": false },
				{ "data": "name", "sWidth": "43%", "bSearchable": true, "bSortable": false },
				{ "data": "account", "sWidth": "12%", "sClass": "hidden-sm", "bSearchable": false, "bSortable": true },
				{ "data": "category", "sWidth": "15%", "sClass": "hidden-sm", "bSearchable": false, "bSortable": true },
				{ "data": "time", "sWidth": "10%", "sClass": "text-center hidden-sm", "bSearchable": false, "bSortable": true },
				{ "data": "amount", "sWidth": "8%", "sClass": "text-right", "bSearchable": false, "bSortable": true },
				{ "data": "action", "sWidth": "8%", "sClass": "text-center", "bSearchable": false, "bSortable": false }
			]
		} );
	   
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
					maxlength: 18
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
							$('#event').focus();
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
	    
	    $('body').on('click', '.edit',  function (ev) {
	    	$('#ids').val($(this).attr('data-id'));	
	    	$('#event').val($(this).attr('data-name')).focus();
	    	$('#account').val($(this).attr('data-account'));
	    	$('#category').val($(this).attr('data-category'));
	    	$('#time').val($(this).attr('data-time'));
	    	$('#amount').val($(this).attr('data-amount'));
	    });
	    
	    $('.reset').click(function(){
	    	$('#ids').val('');
	    });
	});
</script>