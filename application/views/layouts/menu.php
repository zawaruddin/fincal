

<div class="navbar navbar-fixed-top">	
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			
			<a class="brand" href="<?php echo site_url('/')?>">Financial Management Calendar</a>		
			
			<div class="nav-collapse">
				<ul class="nav pull-right">
					<li class="dropdown">						
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-cog"></i> Setting<b class="caret"></b>
						</a>
						
						<ul class="dropdown-menu">
							<li><a href="<?php echo site_url('type')?>">Account Type</a></li>
							<li><a href="<?php echo site_url('account')?>">Account</a></li>
							<li><a href="<?php echo site_url('category')?>">Category</a></li>
							<li><a href="<?php echo site_url('currency')?>">Currency</a></li>
						</ul>						
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="subnavbar">
	<div class="subnavbar-inner">
		<div class="container">
			<ul class="mainnav">
				<li class="<?php echo isset($m_dashboard)? $m_dashboard : '' ?>" >
					<a href="<?php echo site_url('/')?>">
						<i class="icon-dashboard"></i><span>Dashboard</span>
					</a>	    				
				</li>
				<li class="<?php echo isset($m_calendar)? $m_calendar : '' ?>" >
					<a href="<?php echo site_url('calendar')?>">
						<i class="icon-calendar"></i><span>Calendar</span>
					</a>	    				
				</li>
				<li class="<?php echo isset($m_income)? $m_income : '' ?>" >
					<a href="<?php echo site_url('income')?>">
						<i class="icon-money"></i><span>Income</span>
					</a>    				
				</li>
				<li class="<?php echo isset($m_expend)? $m_expend : '' ?>" >
					<a href="<?php echo site_url('expend')?>">
						<i class="icon-shopping-cart"></i><span>Expend</span>
					</a>    				
				</li> 
				<!--li class="<? echo isset($m_report)? $m_report : '' ?>" >					
					<a href="<?php echo site_url('report')?>">
						<i class="icon-signal"></i><span>Report</span>
					</a>  									
				</li>
				<li class="<?php echo isset($m_graph)? $m_graph : '' ?>" >
					<a href="#<?php //echo site_url('graph')?>">
						<i class="icon-bar-chart"></i><span>Graph</span>
					</a>    				
				</li>                
                <li class="dropdown <? echo isset($m_setting)? $m_setting : '' ?>" >					
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-list-alt"></i><span>Setting</span><b class="caret"></b>
					</a>  
					<ul class="dropdown-menu">
                    	<li><a href="<?php echo site_url('setting/accType')?>">Account Type</a></li>
						<li><a href="<?php echo site_url('setting/account')?>">Account</a></li>
						<li><a href="<?php echo site_url('setting/category')?>">Category</a></li>
						<li><a href="<?php echo site_url('setting/currency')?>">Currency</a></li>
                    </ul>    									
				</li>
                
                
                <li>					
					<a href="shortcodes.html">
						<i class="icon-code"></i>
						<span>Shortcodes</span>
					</a>  									
				</li>
				
				<li class="dropdown">					
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon-long-arrow-down"></i>
						<span>Drops</span>
						<b class="caret"></b>
					</a>	
				
					<ul class="dropdown-menu">
                    	<li><a href="icons.html">Icons</a></li>
						<li><a href="faq.html">FAQ</a></li>
                        <li><a href="pricing.html">Pricing Plans</a></li>
                        <li><a href="login.html">Login</a></li>
						<li><a href="signup.html">Signup</a></li>
						<li><a href="error.html">404</a></li>
                    </ul>    				
				</li-->
			
			</ul>

		</div> <!-- /container -->
	
	</div> <!-- /subnavbar-inner -->

</div> <!-- /subnavbar -->