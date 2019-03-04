<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">

    <title>Gojeg Clone</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet">
  

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script src="<?php echo base_url();?>assets/js/jquery-1.10.2.min.js"></script>
    <link href="<?php echo base_url();?>css/mycss.css" rel="stylesheet">
    
	<style>
		body {
		  min-height: 600px;
		  padding-top: 90px;
      font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
      font-size: 12px;
      line-height: 16px;
      color: rgb(51, 51, 51);
      background-image:url("img/bg1.png");
      background: none repeat scroll 0% 0% #F2F2F2;

		}

	</style>
  <style type="text/css">
    #report { border-collapse:collapse;}
    #report tr.odd td { background:#fff; cursor:pointer; }
    #report div.arrow { background:transparent url('../../img/arrows.png') no-repeat scroll 0px -16px; width:16px; height:16px; display:block;}
    #report div.up { background-position:0px 0px;}
</style>
  <link rel="stylesheet" href="<?php echo base_url();?>assets/js/select2/select2_metro.css">
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/select2/select2.min.js"></script>
  
  </head>

  <body>

    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo base_url(); ?>">Gojeg</a>
        </div>
        <?php $this->load->view('menu_admin'); ?>
    </div>

    <div class="container minimal">
		<?php 
		$flashdata = $this->session->flashdata('message');
		if(!empty($flashdata)){ ?>
			<div class="alert alert-info" >
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <span class="glyphicon glyphicon-info-sign"></span>  <?php echo $flashdata; ?>
      </div>
		<?php 
		} ?>
		<!-- Main component for a primary marketing message or call to action 
		<div class="jumbotron">
			<h1>Navbar example</h1>
			<p>This example is a quick exercise to illustrate how the default, static and fixed to top navbar work. It includes the responsive CSS and HTML, so it also adapts to your viewport and device.</p>
			<p>To see the difference between static and fixed top navbars, just scroll.</p>
			<p>
			  <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
			</p>
		</div>
		-->
  <!--   <?php if (isset($breadcrumb)){ ?>
      <ol class="breadcrumb">
        <?php foreach ($breadcrumb as $key => $value) {
          echo '<li><a href="'.$key.'">'.$value.'</a></li>';
        } ?>        
      </ol>
    <?php } ?> -->

		<?php $this->load->view($content); ?>

    <div class="row">
      <div class="col-md-12">
        <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
      </div>
    </div>

    </div>
    

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.validate.js"; ?>></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/datetimepicker/jquery.datetimepicker.css"/>
    <script type="text/javascript" src="<?php echo base_url();?>assets/datetimepicker/jquery.datetimepicker.js"; ?>></script>

  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/switch/bootstrap-switch.css"/>
    <script type="text/javascript" src="<?php echo base_url();?>assets/switch/bootstrap-switch.min.js"; ?>></script>

  <script type="text/javascript">
    $(function() {
      $("select").select2();
      $('.datetimepicker_mask').datetimepicker({
        mask:'9999-19-39 29:59',
        step:5,
        format:'Y-m-d H:i',
      });
      $('.datetimepicker').datetimepicker({
        //mask:'9999-19-39 29:59',
        step:5,
        format:'Y-m-d H:i:s',
      });
      $('.datepicker_mask').datetimepicker({
        mask:'9999-19-39',
        step:60,
        format:'Y-m-d',
      });

      $('.datepicker').datetimepicker({
        //mask:'9999-19-39',
        step:5,
        format:'d-m-Y',
      });
      $('.datepicker1').datetimepicker({
        //mask:'9999-19-39',
        step:5,
        format:'Y-m-d',
      });
      $(".make-switch").bootstrapSwitch({
        size : 'small'
      });
    });
  </script>

  </body>
</html>
