<?php 
	$m_prev = ($month > 1)? $month - 1 : 12;
	$y_prev = ($month > 1)? $year : $year - 1;
	
	$m_next = ($month < 12)? $month + 1 : 1;
	$y_next = ($month < 12)? $year : $year + 1;
?>
<div class="main">
	<div class="main-inner">
	    <div class="container">
	     	<div class="row">
	      		<div class="span12">
	      			<div class="info-box">
               			<div class="row-fluid">
                  			<div class="span12">
								<h1 class="text-center mt20"><small><?php echo anchor("expend/$y_prev/$m_prev", "&laquo; Prev")?></small>&nbsp;&nbsp;&nbsp;<?php echo $mon.' '.$year?>&nbsp;&nbsp;&nbsp;<small><?php echo anchor("expend/$y_next/$m_next", "Next &raquo;")?></small></h1>
								<hr>
								<table id="expend" class="display" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th class="text-center">No</th>
											<th class="text-left">Event</th>
											<th class="text-left hidden-sm">Account</th>
											<th class="text-left hidden-sm">Date</th>
											<th class="text-right">Amount</th>
										</tr>
									</thead>
									<tbody>
									<?php 
										$total = 0;
										if(!empty($expend)){
											$i = 1;
											foreach($expend as $r){
												foreach($r->transaction as $t){
													echo '<tr>
														<td>'.$i.'</td>
														<td>'.$t->name.'</td>
														<td>'.$t->account->name.'</td>
														<td>'.DateTime::createFromFormat('Y-m-d', $r->date)->format('d F Y').' '.$t->transaction_time.'</td>
														<td class="text-right">'.number_format($t->amount, 0, ',', '.').'</td>
													</tr>';
													$i++;	
													$total += $t->amount;
												}
											}
										} 
									?>
									</tbody>
									<tfoot>
										<tr>
											<th colspan="4" class="text-right hidden-sm">Total : </th>
											<th class="text-right"><strong><span class="totalAmount"><?php echo number_format($total, 0, ',', '.') ?></span></strong></th>
										</tr>
									</tfoot>
								</table>
							</div>
               			</div>
             		</div>
         		</div>
         	</div>     
	    </div>
	</div>
</div>