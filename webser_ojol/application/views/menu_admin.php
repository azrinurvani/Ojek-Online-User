<?php if($this->session->userdata('login') != TRUE){
  //redirect(base_url().'login/index');

} 
/*$ci =&get_instance();
$ci->load->model('form_model');
$formArray  = $ci->form_model->formArrayMenu();*/
#pre($formArray);
?>
<div class="navbar-collapse collapse">
<ul class="nav navbar-nav">
  <li ><a href="<?php echo base_url(); ?>">
  <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
     &nbsp;Home</a></li>
  
   <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> &nbsp;Master <b class="caret"></b></a>
    <ul class="dropdown-menu">
      <?php if($this->session->userdata('id_level') == 1) { ?> 
        <li>
          <a href="<?php echo base_url('user/data'); ?>">
          <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp;
          User</a>
        </li>
        <li>
          <a href="<?php echo base_url('level/data'); ?>">
          <span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>&nbsp;
          Level</a>
        </li> 
        <li>
          <a href="<?php echo base_url('jurusan/data'); ?>">
          <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>&nbsp;
          Jurusan</a>
        </li> 
        <li class="divider"></li>
        <li>
          <a href="<?php echo base_url('jenis/data'); ?>">
          <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>&nbsp;
          Jenis Industri</a>
        </li> 
      <?php } ?>
       
      </li> 
           
    </ul>
  </li>
<!-- 
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> &nbsp;Komoditas <b class="caret"></b></a>
    <ul class="dropdown-menu">
      <?php if($this->session->userdata('id_level') == 1) { ?> 
        
         <li>
          <a href="<?php echo base_url('kategori/data'); ?>">
          <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>&nbsp;
          Kategori</a>
        </li>
        <li>
          <a href="<?php echo base_url('produk/data'); ?>">
          <span class="glyphicon glyphicon-fire" aria-hidden="true"></span>&nbsp;
          Produk</a>
        </li> 
        <?php } ?>
      </li> 
           
    </ul>
  </li>

  
  <li>
    <a href="<?php echo base_url('partner/data'); ?>">
    <span class="glyphicon glyphicon-fire" aria-hidden="true"></span>&nbsp;
    Partner</a>
  </li> -->

  <!-- <li>
    <a href="<?php echo base_url('galery/data'); ?>">
    <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>&nbsp;
    Galery Photo</a>
  </li> -->
  
</ul>

<ul class="nav navbar-nav navbar-right">
 <!--  <li><a href="../navbar/">Default</a></li>
  <li><a href="../navbar-static-top/">Static top</a></li> -->
  <li class="active"><a href="<?php echo base_url('login/logout'); ?>"> <span class="glyphicon glyphicon-off" aria-hidden="true"></span>&nbsp; Logout</a></li>
</ul>
</div><!--/.nav-collapse -->
</div>