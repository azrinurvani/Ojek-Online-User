<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	var $level;
	var $idUser;
	var $kantor;

	var $url_parameters		= '';
    var $server_info		= '';

    var $processData = array(
		'is_view'=>true,'is_add'=>true, 
		'is_edit'=>true,'is_delete'=>true,
		'is_forcedelete'=>false
	);

	var $jumlah_halaman = 25;
	var $judul = "Naga Mas Motor";

	//acl
	var $acl;
	var $uriController;
	var $uriMethod;
	
	//tombol crud
	var $canEdit = false;
	var $canAdd = false;
	var $canDelete = false;

	//tombol transaksi
	var $breadcrumb = array();
	
	function __construct(){
		parent::__construct();
		#ini_set('display_errors', 'On');
		#error_reporting(E_ALL);
		$this->level = $this->session->userdata('id_level');
		$this->idUser = $this->session->userdata('id_user');
		
		$this->server_info	= server_info();
		//check  uri pertama dan kedua
		$this->uriController = $this->uri->rsegment(1);
		$this->uriMethod = $this->uri->rsegment(2);
		$this->acl = $this->session->userdata('user_acl');
		//pre($this->acl);
		$bread[base_url()] = 'Home';
		$bread[base_url().$this->uriController.'/data'] = ucwords(str_replace("_", " ",$this->uriController));
		if($this->uriMethod != 'data'){
			$bread[base_url().$this->uriController.'/'.$this->uriMethod] = ucwords(str_replace("_", " ", $this->uriMethod));
		}
		$this->breadcrumb = $bread;
		#pre($this->breadcrumb);

		#pre($this->server_info);
		$login_state = $this->session->userdata('login');
		if($login_state != 1){
			$this->session->set_flashdata('message', 'Anda harus login dahulu.');
			redirect(base_url().'login');
		}

		// recheck CSRF
        /*if($this->server_info['referer_page'] == ''){
        	#pre($this->server_info['php_self']);
    
        	$this->session->set_flashdata('message', errorArray(1));
        	//redirect(base_url().'login/home');
        	//show_404("INVALID REFERER SITE FROM ".$this->server_info['referer_page']);

			
        }else{*/
        	$valid_ref_len = strlen(base_url());
			#pre(substr($this->server_info['referer_page'], 0, $valid_ref_len));
			#pre($valid_ref_len);
			$data_post = $this->input->post();
        	if(!empty($data_post) && 
        		substr($this->server_info['referer_page'], 0, $valid_ref_len) != base_url()){
        		show_404("INVALID REFERER SITE FROM ".$this->server_info['referer_page'], true);
        	}else{
				//check controller ada dalam acl user ngga
				$bypass = FALSE;
				
				#pre($this->uriController.'/'.$this->uriMethod)	;		
				if(@in_array($this->uriController.'/'.$this->uriMethod,$this->acl)){
					//cek methodnya
					/*if(($this->uriMethod == 'view' && $this->acl[$this->uriController]['view'] == 1)	|| 
						($this->uriMethod == 'edit' && $this->acl[$this->uriController]['edit'] == 1)	||
						($this->uriMethod == 'add' && $this->acl[$this->uriController]['add'] == 1)	|| 
						($this->uriMethod == 'delete' && $this->acl[$this->uriController]['delete'] == 1)	){
						$bypass = TRUE;
					}*/
					$bypass = TRUE;
				}

				//check the uri untuk acl, kecuali untuk super admin
				if($this->level == 1){
					$bypass = true;
				}

				//check sesi login
				$login_state = $this->session->userdata('login');
				if($login_state != 1){
					$this->session->set_flashdata('message', 'Anda harus login dahulu.');
					//redirect(base_url().'login');
				}else if(!$bypass){
					$this->session->set_flashdata('message', 'Anda Tidak Memiliki Hak Akses.');
					redirect(base_url().'login/home');
				}

				//check aktivitas edit dan delete data
				if(@in_array($this->uriController.'/add',$this->acl) || $this->level == 1 || true){
					$this->canAdd = true;
				}
				#pre($this->uriController.'/edit');
				if(@in_array($this->uriController.'/edit',$this->acl) || $this->level == 1 || true ){
					$this->canEdit = true;
					#pre('yes');
				}
				if(@in_array($this->uriController.'/delete',$this->acl) || $this->level == 1 || true){
					$this->canDelete = true;
				}

				//pre($this->canEdit);
			}

    	//}
					

       
		
	}

	public function manage($controller){
		pre($_GET);
		pre($this->uri->ruri_string());
	}
	
	public function btnAdd($title, $lebar = 1, $x = ''){
		$data = '';
		if((@in_array($this->uriController.'/add',$this->acl))|| $this->level == 1 || true){
			if($x != ''){
				$data = "
				<div class=\"col-md-$lebar\">
			  		<a href=\"".base_url().$this->uriController.'/add/'.$x."\" class=\"btn btn-primary btn-small\" ><span class=\"glyphicon glyphicon-plus\"></span>  $title</a>
			 	</div>";
			}else{
				$data = "
				<div class=\"col-md-$lebar\">
			  		<a href=\"".base_url().$this->uriController.'/add/'."\" class=\"btn btn-primary btn-small\" ><span class=\"glyphicon glyphicon-plus\"></span>  $title</a>
			 	</div>";
			}
			
		}

		return $data;
	}
	
	public function upload_uploadifive()
	{
	   $status = "";
	   $verifyToken = md5($_POST['timestamp']);
	   $file_element_name = 'Filedata';
	   $folder = $_POST['lokasi'];
	   #pre($_FILES);
	   //$folder = 'img/message/'; 

	   buatDir($folder);

	  	if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
		      	$config['upload_path'] = $folder;
		      	$config['allowed_types'] = 'zip|rar|pdf|xlsx|docx|xls|doc|jpg|png|gif|mp4|3gp|avi|mkv|flv';
				//$config['max_size']	= '2000000';
		      //$config['encrypt_name'] = TRUE;
		 
		      $this->load->library('upload', $config);
		      $this->upload->initialize($config);
		 
		      if (!$this->upload->do_upload($file_element_name)){
		         $status = $this->upload->display_errors();
		         //pre($this->upload->display_errors());
		         //$msg = $this->upload->display_errors('', '');
		      }else{
		        $data = $this->upload->data();
		        #pre($data);		        
		        //$status = base_url()."img/message/".$data['file_name']; 
		        $status = $data['file_name'];  
		       
		      }
		      //@unlink($_FILES[$file_element_name]);
	   }
	   return $status;
	}

	//for handle upload file
	public function upload_summernote($folder = 'img')
	{
	   $status = "";
	   $msg = "";
	   $file_element_name = 'userfile';
	   //$folder = 'img/message/';

	   if ($status != "error")
	   {
	      $config['upload_path'] = $folder;
	      $config['allowed_types'] = 'gif|jpg|png';
	      //$config['max_size']  = 1024 * 8;
	      //$config['encrypt_name'] = TRUE;
	 
	      $this->load->library('upload', $config);
	      $this->upload->initialize($config);
	 
	      if (!$this->upload->do_upload($file_element_name))
	      {
	         $status = 'error';
	         //$msg = $this->upload->display_errors('', '');
	         // pre($this->upload->display_errors());
	      }
	      else
	      {
	        $data = $this->upload->data();
	        #pre($data);

	        if($data['image_width'] < 800){
	        	$status = base_url().$folder.$data['file_name'];
	        }else{
		        $configx['image_library'] = 'gd2';
				$configx['source_image'] = $data['full_path'];
				//$config['create_thumb'] = TRUE;
				$configx['maintain_ratio'] = TRUE;
				$configx['width'] = 800;
				//$configx['height'] = 400;

				$this->load->library('image_lib', $configx);

				if($this->image_lib->resize()){
					$status = base_url().$folder.$data['file_name'];
				}else{
					$status = "error";
				}
			}

	       
	      }
	      //@unlink($_FILES[$file_element_name]);
	   }
	   return $status;
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */