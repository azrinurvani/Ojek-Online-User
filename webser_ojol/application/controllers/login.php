<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	var $judul;
	function __construct(){
		parent::__construct();
		$this->judul = "Halaman User";
		$this->load->model('user_model','model', true);
		//session_start();
		//ini_set('display_errors', 'On');
		//error_reporting(E_ALL);
	}
	
	//untuk login
	public function index()
	{
		$data = array();
		$data['content'] = 'login/login_index';
		$data['cap_img'] = $this->make_captcha();
		//pre($this->make_captcha());
		/* validasi data */
		//$this->form_validation->set_error_delimiters('<span class="error help-inline">', '</span>');
		$this->form_validation->set_rules("f_username", "Username", "trim|required|xss_clean");
		$this->form_validation->set_rules("f_password", "password", "trim|required");
		$this->form_validation->set_rules("f_captcha", "Captcha", "trim|required|callback_validasi_captcha");
		
		$b_login = $this->input->post('b_login');
		
		if($this->session->userdata('login') == TRUE){
			redirect('login/home');
		}
		
		if ( isset($b_login)){
			if ($this->form_validation->run() !== FALSE) {
				$this->db->where('user_email',$this->input->post('f_username'));
				$this->db->where('user_password',md5x($this->input->post('f_password')));
				$this->db->where_in('user_status',array(0,1));
				$cekdata = $this->db->get('user');
				if ($cekdata->num_rows() > 0){
					$data_admin = $cekdata->row();

					$this->session->set_userdata($data_admin);
					$this->session->set_userdata('login', TRUE);
					//$_SESSION['idUser'] = $data_admin->id_user;
					//setcookie("idUser",$data_admin->id_user);
					$user_status = $this->session->userdata('user_status');
					if($user_status == 0) {
						$url = base_url().'login/passwd/loginpertama';
						$this->session->set_flashdata('message', 'Anda login pertama kali, untuk keamanaan silahkan ganti password default Anda.');
					}else if($user_status == 1){
						$d_acl = array();
						$this->session->set_userdata('user_acl', $d_acl);
						$url = base_url().'login/home';
					}
				}else{
					$this->session->set_flashdata('message', 'Kombinasi User dan Password salah.');
					$url = base_url().'login/index';
				}			
							
			}
		}
		
		// jika terdapat data URL, maka arahkan halaman tersebut
		if( isset($url) ){
			redirect($url);
		}
		
		$this->load->view('login/login_index', $data);
	}
	
	public function home(){
		$data = array();
		$data['content'] = 'login/login_home';
		//$data['expired'] = $this->cekExpired();

		/*if($this->cekExpired() == 0){
			$this->session->set_flashdata('message', 'Masa Aktif Password Anda berakhir hari ini silahkan ganti Password Anda agar tetap dapat menggunakan akun Anda.');
			redirect(base_url('login/passwd'));
		}*/
		
		$this->load->view('template',$data);
	}
	
	public function logout(){
		//setcookie("idUser","");
		//unset($_SESSION['idUser']);
		$this->session->sess_destroy();
		
		$url = base_url().'login/index';
		redirect($url);
	}
	
	public function sesi(){
		pre($this->session->all_userdata());
		
		//pre($_SESSION);
	}
	
	public function passwd($aksi='gantipassword'){
		$login_state = $this->session->userdata('login');
		if($login_state != 1 || !isset($login_state)){
			$this->session->set_flashdata('message', 'Anda harus login untuk mengakses halaman tadi.');
			redirect(base_url().'login');
		}
		$data = array();
		$data['content'] = 'login/login_passwd';
		$data['judul'] = "Ganti Password";
		$data['aksi'] = $aksi;
		/* validasi data */
		$this->form_validation->set_error_delimiters('<span class="error help-inline">', '</span>');
		$this->form_validation->set_rules("f_password_lama", "Password Lama", "required|callback_check_password");
		$this->form_validation->set_rules("f_password_baru", "password baru", "required||min_length[6]");
		$this->form_validation->set_rules("f_password_barux", "ulang password baru", "required||matches[f_password_baru]");
		
		$user_status = $this->session->userdata('user_status');
		
		$b_simpan = $this->input->post('b_simpan');		
		if ( isset($b_simpan)){
			if ($this->form_validation->run() !== FALSE) {
				$pass = $this->input->post('f_password_baru');
				$id_user = $this->session->userdata('id_user');				
				
				$data_simpan['user_password'] = md5x($pass);
				if($user_status == 0){
					$data_simpan['user_status'] = 1;
				}
				
				$status = $this->model->updateData($id_user, $data_simpan);
				if($status){
					$this->session->set_flashdata('message', 'Ganti Password Berhasil, Silahkan login.');
					$url = base_url().'login/index';
				}else{
					$this->session->set_flashdata('message', 'Ganti Password Gagal');
				}	
							
			}
		}
		
		// jika terdapat data URL, maka arahkan halaman tersebut
		if( isset($url) ){
			redirect($url);
		}
		
		if($user_status == 1){ 
			$this->load->view('template',$data);
		}else if($user_status == 0){
			$this->load->view('template_login',$data);
		}
	}
	
	//check kesesuaian password lama
	public function check_password($str){
		$id_user = $this->session->userdata('id_user');
		$passwdsama = $this->model->check_password($id_user, md5x($str));
		if (!$passwdsama){
			$this->form_validation->set_message('check_password', '%s tidak sama');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	//membuat captcha
	public function make_captcha(){
		$this->load->helper('captcha');
		$vals = array(
			'img_path' => './captcha/',
			'img_url' => base_url().'captcha/',
			'font_path' => base_url().'img/arial.ttf',
			'img_width' => 300,
			'img_height' => 40,
			'expiration' => 3600
		);
		//create chapcha
		$cap = create_captcha($vals);
		//write to db
		if($cap){
			$data = array(
				 
				'captcha_time' => $cap['time'],
				'ip_address' => $this->input->ip_address(),
				'word' => $cap['word']
				);
			$expiration = time()-$cap['time'];
			//$this->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);
			$query = $this->db->insert_string('captcha', $data);
			$this->db->query($query);
		}else{
			return "Umm, captcha error";
		}
		return $cap['image'];	
	}
	
	function validasi_captcha($str)
	{
		$expiration = time()-3600;
		$this->db->query("DELETE FROM captcha WHERE captcha_time < ".$expiration);

		$sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";
		$binds = array($str, $this->input->ip_address(), $expiration);
		$query = $this->db->query($sql, $binds);
		$row = $query->row();

		if ($row->count == 0)
		{
			$this->form_validation->set_message('validasi_captcha', 'Kode Captcha yang anda masukkan tidak Valid...!!!');
			return FALSE;
		}else{
			return TRUE;
		}
	}

	//cek user expireddate
	function cekExpired(){
		$this->db->select('DATEDIFF(user_expired, CURDATE()) as hari');
		$this->db->where('id_user', $this->session->userdata('id_user'));
		$query = $this->db->get('user');
		if($query->num_rows() > 0){
			return $query->row()->hari;
		}
	}


}


/* End of file login.php */
/* Location: ./application/controllers/login.php */