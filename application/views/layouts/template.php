<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Financial Management Calendar</title>
	
	<meta name="author" content="Moch Zawaruddin Abdullah">
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	
	<link href="<?php echo base_url()?>assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/css/font-awesome.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/css/style.css" rel="stylesheet">
	<link href="<?php echo base_url()?>assets/css/pages/reports.css" rel="stylesheet">
	
	<?php if(isset($css) && $css === true) { $this->load->view("{$content}_css"); } ?>

	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	    <![endif]-->
</head>
<body>
	<?php $this->load->view('layouts/menu')?>

	<?php $this->load->view($content)?>
	
	<?php $this->load->view('layouts/footer')?>	
	    
	<script src="<?php echo base_url()?>assets/js/jquery-1.7.2.min.js"></script>
	<script src="<?php echo base_url()?>assets/js/bootstrap.min.js"></script>
   
	<?php if(isset($js) && $js === true) { $this->load->view("{$content}_js"); } ?>
</body>
</html>