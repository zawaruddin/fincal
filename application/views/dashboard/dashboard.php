<div class="main">
	<div class="main-inner">
	    <div class="container">
	     	<div class="row">
	     		<div class="span12">
		      		<div class="widget">
		      			<div class="widget-content">
				      		<h1>History Year&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp; 
				      		<?php 
				      			foreach($list_year as $y){
									echo ($y->year == $year)? '&raquo;&nbsp;'.$y->year.'&nbsp;&laquo;' : '<small><a href="'.site_url('dashboard/'.$y->year).'">'.$y->year.'</a></small>';
									echo '&nbsp;&nbsp;&nbsp;';
								}
				      		?>
				      		</h1>				      		
			      		</div>
		      		</div>
	      		</div>
	     	
	      		<div class="span12">
	      			<?php
	      				$default = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0];
						
						$s_exp = '';
						$a_inc = $a_exp = $tot_trans = $default;
						
						$t_inc = '<div class="widget widget-table action-table">
									<div class="widget-header"> <i class="icon-th-list"></i>
										<h3>Income in '.$year.' by Account</h3>
									</div>
									<div class="widget-content table-responsive">
										<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>Account</th>
												<th class="text-right">January</th>
												<th class="text-right">February</th>
												<th class="text-right">March</th>
												<th class="text-right">April</th>
												<th class="text-right">May</th>
												<th class="text-right">June</th>
												<th class="text-right">July</th>
												<th class="text-right">August</th>
												<th class="text-right">September</th>
												<th class="text-right">October</th>
												<th class="text-right">November</th>
												<th class="text-right">December</th>
											</tr>
										</thead>
										<tbody>';
	      				foreach($income as $account => $inc){
							$t_inc .= '<tr><th>'.$account.'</th>';
							foreach($inc as $m => $i){
								$t_inc     .= '<td class="text-right '.(($i > 0)? 'blue' : '').'">'.number_format($i, 0, ',', '.').'</td>';
								$a_inc[$m] += $i; 
							}
							$t_inc .= '</tr>';
						}
						
						$t_inc .= '</tbody><tfoot><tr class="bolder"><th class="text-right">sub - total :</th>';
						
						foreach($a_inc as $m => $i) {
							$t_inc .= '<td class="text-right '.(($i > 0)? 'blue' : '').'">'.number_format($i, 0, ',', '.').'</td>';
						}
						
						$t_inc .= '</tr></tfoot></table></div></div>';
						
						
						$t_exp = '<div class="widget widget-table action-table">
									<div class="widget-header"> <i class="icon-th-list"></i>
										<h3>Expend in '.$year.' by Account</h3>
									</div>
									<div class="widget-content table-responsive">
										<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>Account</th>
												<th class="text-right">January</th>
												<th class="text-right">February</th>
												<th class="text-right">March</th>
												<th class="text-right">April</th>
												<th class="text-right">May</th>
												<th class="text-right">June</th>
												<th class="text-right">July</th>
												<th class="text-right">August</th>
												<th class="text-right">September</th>
												<th class="text-right">October</th>
												<th class="text-right">November</th>
												<th class="text-right">December</th>
											</tr>
										</thead>
										<tbody>';
						
						foreach($expend as $account => $exp){
							$t_exp .= '<tr><th>'.$account.'</th>';
							foreach($exp as $m => $i){
								$t_exp     .= '<td class="text-right '.(($i > 0)? 'red' : '').'">'.number_format($i * -1, 0, ',', '.').'</td>';
								$a_exp[$m] += $i; 
							}
							$t_exp .= '</tr>';
						}
						
						$t_exp .= '</tbody><tfoot><tr class="bolder"><th class="text-right">sub - total :</th>';
						
						foreach($a_exp as $m => $i) {
							$t_exp .= '<td class="text-right '.(($i > 0)? 'red' : '').'">'.number_format($i * -1, 0, ',', '.').'</td>';
						}
						
						$t_exp .='</tr></tfoot></table></div></div>';
						
						$first  = '<div class="widget widget-table action-table">
									<div class="widget-header"> <i class="icon-th-list"></i>
										<h3>Income and Expend in '.$year.'</h3>
									</div>
									<div class="widget-content table-responsive">
										<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>Category</th>
												<th class="text-right">January</th>
												<th class="text-right">February</th>
												<th class="text-right">March</th>
												<th class="text-right">April</th>
												<th class="text-right">May</th>
												<th class="text-right">June</th>
												<th class="text-right">July</th>
												<th class="text-right">August</th>
												<th class="text-right">September</th>
												<th class="text-right">October</th>
												<th class="text-right">November</th>
												<th class="text-right">December</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>Income (+)</th>';
						
						$ie_tot = $e_exp = '';
						foreach($a_inc as $m => $i) {
							$first .= '<td class="text-right '.(($i > 0)? 'blue' : '').'">'.number_format($i, 0, ',', '.').'</td>';
							$e_exp .= '<td class="text-right '.(($a_exp[$m] > 0)? 'red' : '').'">'.number_format($a_exp[$m] * -1, 0, ',', '.').'</td>';
							$t_tot  = ($i - $a_exp[$m]);
							$ie_tot.= '<td class="text-right '.(($t_tot == 0)? '' : (($t_tot > 0)? 'blue' : 'red')).'">'.number_format($t_tot, 0, ',', '.').'</td>';
						}
						
						$first .= '</tr><tr><th>Expend (-)</th>'.$e_exp.'</tr>
								<tfoot>
									<tr class="bolder"><th class="text-right">sub - total :</th>'.$ie_tot.'</tr>
									<tr class="bolder"><th colspan="12" class="text-right"><strong>Total Saldo :</strong></th><td class="text-right '.(($saldo >= 0)? 'blue' : 'red').'"><strong>'.number_format($saldo, 0, ',', '.').'</strong></td></tr>
								</tfoot>
								</tbody></table></div></div>'; 
	      				
	      				echo $first;	
	      				echo $t_inc;
	      				echo $t_exp;
	      			?>
	      			
	      			<div class="widget">
                        <div class="widget-header">
                        	<i class="icon-bar-chart"></i>
                            <h3>Graph Income and Expend in <?php echo $year?></h3>
                        </div>
                        <div class="widget-content">
                        	<canvas id="area-chart" class="chart-holder" width="1140" height="350"></canvas>
                        </div>    
                    </div>
         		</div>
         	</div>     
	    </div>
	</div>
</div>
<script>
	var expendData = [<?php echo implode(',', $a_exp)?>];
	var incomeData = [<?php echo implode(',', $a_inc)?>];
</script>