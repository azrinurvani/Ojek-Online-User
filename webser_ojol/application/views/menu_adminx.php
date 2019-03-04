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
  <li ><a href="<?php echo base_url(); ?>">Home</a></li>
   <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pengguna <b class="caret"></b></a>
    <ul class="dropdown-menu">
      
      <li>
        <a href="<?php echo base_url('user/data'); ?>">User</a>
      </li>
      <li>
        <a href="<?php echo base_url('level/data'); ?>">Level</a>
      </li> 
           
    </ul>
  </li>

  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Produk <b class="caret"></b></a>
    <ul class="dropdown-menu">
      <li>
        <a href="<?php echo base_url('motor/data'); ?>">Motor</a>
      </li>   
      <li>
        <a href="<?php echo base_url('tipe/data'); ?>">Tipe</a>
      </li>
      <li>
        <a href="<?php echo base_url('kategori/data'); ?>">Kategori</a>
      </li>
         
    </ul>
  </li>
  <li ><a href="<?php echo base_url('dealer/data'); ?>">Dealer</a></li>
  <li ><a href="<?php echo base_url('service/data'); ?>">Service</a></li>
  <li ><a href="<?php echo base_url('pemesanan/data'); ?>">Pemesanan</a></li>
  <li ><a href="<?php echo base_url('Saran/data'); ?>">Saran</a></li>
</ul>

<ul class="nav navbar-nav navbar-right">
 <!--  <li><a href="../navbar/">Default</a></li>
  <li><a href="../navbar-static-top/">Static top</a></li> -->
  <li class="active"><a href="<?php echo base_url('login/logout'); ?>">Logout</a></li>
</ul>
</div><!--/.nav-collapse -->
</div>