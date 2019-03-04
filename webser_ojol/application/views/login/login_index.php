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

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">

    

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #eee;
        background-image:url("../img/bg1.png");
      }

      .form-signin {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin .checkbox {
        font-weight: normal;
      }
      .form-signin .form-control {
        position: relative;
        font-size: 16px;
        height: auto;
        padding: 10px;
        -webkit-box-sizing: border-box;
           -moz-box-sizing: border-box;
                box-sizing: border-box;
      }
      .form-signin .form-control:focus {
        z-index: 2;
      }
      .form-signin input[type="text"] {
        margin-bottom: -1px;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
      }
      .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
      }
      .btn-lg {
          border-radius: 0px;
      }
      .btn-login {
          color: #FFF;
          background-color: #2fb0f7;
          border-color: #2fb0f7;
      }
  </style>
  </head>

  <body>

    <div class="container">

      <form class="form-signin" role="form" method="post" action="">
        
        <?php $flashmessage = $this->session->flashdata('message');
        $error = validation_errors();
        if($flashmessage || $error){ ?>
        <div class="alert alert-success">
          <?php echo $flashmessage; ?><?php echo validation_errors(); ?></div>
        <?php } ?>

        <h2 class="form-signin-heading">Silahkan Login</h2>
        <input type="text" class="form-control" name="f_username" id="f_username" placeholder="Username" required autofocus value="<?php echo set_value("f_username");?>">
        <input type="password" class="form-control" placeholder="Password" name="f_password" id="f_password" value="<?php echo set_value("f_password");?>" required>
        <?php echo $cap_img;?><br/>
        <input type="text" name="f_captcha" id="f_captcha" class="form-control" placeholder="Masukkan Captcha di Atas" required/><br/>
        <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>
