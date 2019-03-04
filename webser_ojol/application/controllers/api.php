<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct(){
		parent::__construct();

		date_default_timezone_set('Asia/Jakarta');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
	}
	
	public function checkBooking(){

		$idBooking = $this->input->post('idbooking');

		$this->db->where('booking_status',2);
		$this->db->where('id_booking',$idBooking);
		$q = $this->db->get('booking');
		
		if($q->num_rows() > 0){				
			$data['result'] = 'true';
			$data['msg'] = 'Data booking ada';
			$data['driver'] = $q->row()->booking_driver;
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Data booking tidak ada.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);



	}

	private function check_sesi(){
		$token = $this->input->post('f_token');
		$device = $this->input->post('f_device');

		//$token = 'a6bccee9979eef5c66532b5a36880d39';
		//$device = 'ffffffff-be46-4574-ffff-ffffbdceeae1';
		
		if($token || $device){
			$sql = "SELECT * FROM sesi WHERE 
				sesi_key = ? AND sesi_device = ? 
				AND sesi_status = ?";
			// $this->db->where('sesi_key', $token);
			// $this->db->where('sesi_status', 1);
			// $this->db->where('sesi_device', $device);
			$query = $this->db->query($sql, array($token, $device, 1));
			if($query->num_rows() > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}

		
		
	}

	public function daftar($isDriver = ''){ 
		$data = array();
		$nama = $this->input->post('nama');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$hp = $this->input->post('phone');
		
		//check email in di database
		$this->db->where('user_email', $email);
		$this->db->where_not_in('user_status', array(9));
		$q = $this->db->get('user');

		if($q->num_rows() > 0) {
			$data['result'] = 'false';
			$data['msg'] = 'Email anda sudah terdaftar, silahkan untuk login.';
		}else{		
			$simpan = array();
			
			if($isDriver != ''){
				$level = 2;
			}else{
				$level = 3;
			}
			$simpan['user_level'] = $level;
			$simpan['user_password'] = md5x($password);
			$simpan['user_nama'] = $nama;
			$simpan['user_email'] = $email;
			$simpan['user_register'] = date('Y-m-d H:i:s');
			$simpan['user_hp'] = $hp;

			$status = $this->db->insert('user',$simpan);
			
			if($status){				
				$data['result'] = 'true';
				$data['msg'] = 'Pendaftaran berhasil, silahkan untuk login';


				
			}else{
				$data['result'] = 'false';
				$data['msg'] = 'Pendafatran gagal, silahkan coba kembali';
			}

		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}
    

	public function send_notification($penerima, $message) {
        // $api_key = "AAAAHOYTRKI:APA91bGfGdBLTuZSHAtPkoIFta1nsY46EvOtj6yJM7AsFC8txZtkRPb3nbqPT1tYcGyVAzqSez8Bn0beYJFqMmKH4sVxFFCsGfBlUkyjxypApQpS8R-pnHGylS_4zHpIFWv7EwtisVRU";
//	        api key ini diambil dari Cloud Messaging di konsol Firebase
	        $api_key = "AAAAluR9_tw:APA91bEhLEu__qiKSacPd8KYM8hBlrR7__a37isODyquKX7tGW6Jm6NHJrLnwgq2VqfkzFqoqvp3UOk0rb3QzuwO-P-aVsrYlogZ7sB1FXNMr4y2pIu_kZPUcyj85HsL9Oja0RWNnY8F";
			$url = 'https://fcm.googleapis.com/fcm/send';
			$fields = array(
		                'registration_ids'  => $penerima,
		                'data'              => array("datax" => $message ),
		                );
			#pre($fields);
			$headers = array('Authorization: key=' . $api_key,
							'Content-Type: application/json');						
							
							
			$ch = curl_init();
			 curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);
			//curl_close($ch);

		//	echo $result;
			
			return $result;
			}

    public function login(){ 
    	//echo "iswandi";
		$data = array();
		$device = $this->input->post("device");
		$email =  $this->input->post("f_email");
		$password =  $this->input->post("f_password");

		//$email = 'riyadi.rb@gmail.com';
		//$password = '123456';
		
		if($email == '' || $password == ''){
			$data['result'] = 'false';
			$data['msg'] = 'Silahkan isi email dan atau password anda.';
			echo json_encode($data);
			return;
			
		}
		
		$this->db->where('user_email', $email);
		$this->db->where('user_password', md5x($password));
		$this->db->where('user_status', 1);
		$this->db->where('user_level', 3);
		$query = $this->db->get('user');
		if($query->num_rows() > 0){
			$q = $query->row();

			//delete semua sesi user ini sebelumnya
			$this->db->where('id_user' , $q->id_user);
			$this->db->update('sesi', array('sesi_status' => 9));					
			//create token
			$key = md5(date('Y-m-d H:i:s').$device);
			//masukkan kedlam tabel sesi
			$simpan = array();
			$simpan['sesi_key'] =  $key;
			$simpan['id_user'] = $q->id_user;
			$simpan['sesi_device'] = $device;
			$status = $this->db->insert('sesi', $simpan);
			if($status){
				$data['result'] = 'true';
				$data['token'] =  $key;
				$data['data'] = $q;
				$data['msg'] = 'Login berhasil.';
				$data['idUser'] = $q->id_user;
			}else{
				$data['result'] = 'false';
				$data['token'] = '';
				$data['idUser'] = '';
				$data['msg'] = 'Error create sesi login, Silahkan coba lagi.';
			}
		}else{			
			$data['result'] = 'false';
			$data['msg'] = 'Username atau password salah.';
			
		}		
		echo json_encode($data);
	}

    public function registerGcm(){ 
		$data = array();
		
		$this->db->where('id_user', $this->input->post("f_idUser"));
		$data_simpan['user_gcm'] = $this->input->post("f_gcm");
		$simpan = $this->db->update('user', $data_simpan);
		if($simpan){
			$data['result'] = 'true';
			$data['msg'] = 'gcm berhasil disimpan';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'id Gcm gagal disimpan.';
		}
				
		echo json_encode($data);
	}
	

	public function upload($folder = 'produk')
	{
	   $status = "gagal, upload file";
	   $file_element_name = $this->input->post('userfile');
	   #pre($_FILES);
	   $folder = 'img/'.$folder.'/';

	  	
	   	if (!empty($_FILES)) {
	   		buatDir($folder);
		    $file_path = $folder . basename( $_FILES['userfile']['name']);
		    //file_put_contents('f.txt',$file_path);
		    if(move_uploaded_file($_FILES['userfile']['tmp_name'], $file_path)) {
		        $status =  "success";
		    } else{
		        $status =  "fail";
		    }
		}
	   #pre($status);
	   return $status;
	}

	public function insert_booking(){ 
		$data = array();

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$idUser = $this->input->post('f_idUser');
		$latAwal = $this->input->post('f_latAwal');
		$lngAwal = $this->input->post('f_lngAwal');
		$awal = $this->input->post('f_awal');
		$latAkhir = $this->input->post('f_latAkhir');
		$lngAkhir = $this->input->post('f_lngAkhir');
		$akhir = $this->input->post('f_akhir');
		$catatan = $this->input->post('f_catatan');
		$jarak = $this->input->post('f_jarak');	

		$tarifUser = $jarak * 10000;
		$tarifDriver = $jarak * 15000;
		$waktu = date('Y-m-d H:i:s');
		$simpan['booking_user'] = $idUser;
		$simpan['booking_tanggal'] = $waktu;
		$simpan['booking_from'] = $awal;
		$simpan['booking_from_lat'] = $latAwal;
		$simpan['booking_from_lng'] = $lngAwal;
		$simpan['booking_catatan'] = $catatan;
		$simpan['booking_tujuan'] = $akhir;
		$simpan['booking_tujuan_lat'] = $latAkhir;
		$simpan['booking_tujuan_lng'] = $lngAkhir;
		$simpan['booking_biaya_user'] = $tarifUser;
		$simpan['booking_biaya_driver'] = $tarifDriver;
		$simpan['booking_jarak'] = $jarak;

		$status = $this->db->insert('booking',$simpan);
		
		if($status){	
			$idBooking = $this->db->insert_id();			
			$data['result'] = 'true';
			$data['msg'] = 'Booking berhasil';
			$data['tarif'] = $tarifUser;
			$data['waktu'] = $waktu;
			$data['id_booking'] = $idBooking;

			//kirimkan pushnotif kepada driver
			$this->push_notif($idBooking);
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Booking gagal, silahkan coba kembali';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function cancel_booking(){ 
	$data = array();

		$id = $this->input->post('idbooking');

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		if($id == ''){
			$data['result'] = 'false';
			$data['msg'] = 'Booking tidak dikenali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}
		$simpan = array();
		$simpan['booking_status'] = 3; //3. Booking cancel
		$this->db->where('id_booking', $id);
		$status = $this->db->update('booking',$simpan);
		
		if($status){				
			$data['result'] = 'true';
			$data['msg'] = 'Booking berhasil dicancel';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Cancel Booking gagal.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function get_booking(){ 
	$data = array();

		$status = $this->input->post('status');

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		
		$idUser = $this->input->post('f_idUser');
		if($status == 1){
			$this->db->where_in('booking_status', 2);
		}else if($status == 4){
			$this->db->join('user', 'user.id_user=booking.booking_driver');
			$this->db->where('booking_status', $status);
		}else{
			$this->db->where('booking_status', $status);
		}
		
		$this->db->where('booking_user', $idUser);
		$this->db->order_by('id_booking', 'DESC');
		$q = $this->db->get('booking ');
		
		if($q->num_rows() > 0){				
			$data['result'] = 'true';
			$data['msg'] = 'Data booking ada';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Data booking tidak ada.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	// ===================== xrb21 | riyadi.rb@gmail.com ======================
	// Training Android "5 Hari Membangun aplikasi Ojeg Online" IMASTUDIO Jogja
	// Yogyakarta, 8-12 Feb 2016

	// API for DRIVER
	public function login_driver(){ 
		$data = array();
		$device = $this->input->post('device');
		$email =  $this->input->post("f_email");
		$password =  $this->input->post("f_password");


		//$email = 'riyadi.rb@gmail.com';
		//$password = '123456';
		
		if($email == '' || $password == ''){
			$data['result'] = 'false';
			$data['msg'] = 'Silahkan isi email dan atau password anda.';
			echo json_encode($data);
			return;
			
		}
		
		$this->db->where('user_email', $email);
		$this->db->where('user_password', md5x($password));
		$this->db->where('user_level', 2);
		$this->db->where('user_status', 1);
		$query = $this->db->get('user');
		if($query->num_rows() > 0){
			$q = $query->row();

			//delete semua sesi user ini sebelumnya
			$this->db->where('id_user' , $q->id_user);
			$this->db->update('sesi', array('sesi_status' => 9));					
			//create token
			$key = md5(date('Y-m-d H:i:s').$device);
			//masukkan kedlam tabel sesi
			$simpan = array();
			$simpan['sesi_key'] =  $key;
			$simpan['id_user'] = $q->id_user;
			$simpan['sesi_device'] = $device;
			$status = $this->db->insert('sesi', $simpan);
			if($status){
				$data['result'] = 'true';
				$data['token'] =  $key;
				$data['data'] = $q;
				$data['msg'] = 'Login berhasil.';
				$data['idUser'] = $q->id_user;
			}else{
				$data['result'] = 'false';
				$data['token'] = '';
				$data['idUser'] = '';
				$data['msg'] = 'Error create sesi login, Silahkan coba lagi.';
			}
		}else{			
			$data['result'] = 'false';
			$data['msg'] = 'Username atau password salah.';
			
		}		
		echo json_encode($data);
	}

	public function get_request_booking(){ 
// 		$data = array();
// 	$iddriver =  $this->input->post("f_idUser");
// 		if(!$this->check_sesi()){
// 			$data['result'] = 'false';
// 			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
// 			#pre($this->db->last_query());
// 			echo json_encode($data);
// 			return;
// 		}
	
// 		$this->db->join('user', 'user.id_user=booking.booking_user');
// 		$this->db->where('booking_status', 1);
// 		$this->db->where('booking_driver', $iddriver);
	
// 		$this->db->order_by('id_booking', 'DESC');
// 		$q = $this->db->get('booking');
		
// 		if($q->num_rows() > 0){				
// 			$data['result'] = 'true';
// 			$data['msg'] = 'Data booking ada';
// 			$data['data'] = $q->result();
// 		}else{
// 			$data['result'] = 'false';
// 			$data['msg'] = 'Data booking tidak ada.';
// 		}
		
// 		#pre($this->db->last_query());
// 		echo json_encode($data);
	$data = array();

		// if(!$this->check_sesi()){
		// 	$data['result'] = 'false';
		// 	$data['msg'] = 'Sesi login expired, silahkan login kembali';		
		// 	#pre($this->db->last_query());
		// 	echo json_encode($data);
		// 	return;
		// }

		$this->db->join('user', 'user.id_user=booking.booking_user');
		$this->db->where('booking_status', 1);
		$this->db->order_by('id_booking', 'DESC');
		$q = $this->db->get('booking');
		
		if($q->num_rows() > 0){				
			$data['result'] = 'true';
			$data['msg'] = 'Data booking ada';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Data booking tidak ada.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function take_booking(){ 
		$data = array();

		$id = $this->input->post('id');
		$idUser = $this->input->post('f_iddriver');

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		if($id == ''){
			$data['result'] = 'false';
			$data['msg'] = 'Booking tidak dikenali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		//check apakah booking sudah ada yang ambil atau tidak
		$this->db->where('id_booking', $id);
		$this->db->where('booking_status', 1);
		$q = $this->db->get('booking');
		if($q->num_rows() == 0){
			$data['result'] = 'false';
			$data['msg'] = 'Booking sudah ada yang take,coba booking yang lainnya.';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$simpan = array();
		$simpan['booking_status'] = 2; //2. Booking diambil oleh driver
		$simpan['booking_driver'] = $idUser;
		$simpan['booking_take_tanggal'] = date('Y-m-d H:i:s');
		$this->db->where('id_booking', $id);
		$status = $this->db->update('booking',$simpan);
		
		if($status){				
			$data['result'] = 'true';
			$data['msg'] = 'Take Booking berhasil';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Take Booking gagal.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function get_handle_booking(){ 
		$data = array();

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$idUser = $this->input->post('f_idUser');
		$this->db->join('user', 'user.id_user=booking.booking_user');
		$this->db->where('booking_status', 2);
		$this->db->where('booking_driver', $idUser);
		$this->db->order_by('id_booking', 'DESC');
		$q = $this->db->get('booking');
		
		if($q->num_rows() > 0){				
			$data['result'] = 'true';
			$data['msg'] = 'Data handle booking ada';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Data handle booking tidak ada.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function complete_booking(){ 
	$data = array();

		$id = $this->input->post('id');
		//check apakah booking sudah ada yang ambil atau tidak
		$idUser = $this->input->post('f_idUser');
		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		if($id == ''){
			$data['result'] = 'false';
			$data['msg'] = 'Booking tidak dikenali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}


		$this->db->where('id_booking', $id);
		$this->db->where('booking_status', 2);
		$this->db->where('booking_driver', $idUser);
		$q = $this->db->get('booking');
		if($q->num_rows() == 0){
			$data['result'] = 'false';
			$data['msg'] = 'Ini bukan data booking anda.';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$simpan = array();
		$simpan['booking_status'] = 4; //2. Booking diambil oleh driver
		$simpan['booking_complete_tanggal'] = date('Y-m-d H:i:s');
		$this->db->where('id_booking', $id);
		$status = $this->db->update('booking',$simpan);
		
		if($status){				
			$data['result'] = 'true';
			$data['msg'] = 'Booking Completed';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Complete booking gagal.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function get_complete_booking(){ 
	$data = array();

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$idUser = $this->input->post('f_idUser');
		$this->db->join('user', 'user.id_user=booking.booking_user');
		$this->db->where('booking_status', 4);
		$this->db->where('booking_driver', $idUser);
		$this->db->order_by('id_booking', 'DESC');
		$q = $this->db->get('booking');
		
		if($q->num_rows() > 0){				
			$data['result'] = 'true';
			$data['msg'] = 'Data complete ada';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Data complete tidak ada.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	//send totikasi kepada driver
	public function push_notif($idBooking){
		$last = $idBooking;
		if (isCurl()){
			//ambil semua id dari data gcm
			$datax = array();
			$this->db->join('user', 'user.id_user=booking.booking_user');
			$this->db->where('booking_status', 1);
			$this->db->where('id_booking', $idBooking);
			$q = $this->db->get('booking');
			
			if($q->num_rows() > 0){				
				$datax['result'] = 'true';
				$datax['msg'] = 'Data booking ada';
				$datax['data'] = $q->row();
			}else{
				$datax['result'] = 'false';
				$datax['msg'] = 'Data booking tidak ada.';
			}
			
			$this->db->where('user_level', 2);
			$this->db->where('user_status', 1);
			$this->db->where_not_in('user_gcm', array(""));
			$qq = $this->db->get('user');
			#pre($datax);	
			if($qq->num_rows() > 0){

				$receivers = array();
				$message = array("datax" => $datax); 
				foreach ($qq->result() as $r) {
					$receivers[] = $r->user_gcm;
				}
				#pre($receivers);
				$hasil = $this->send_notification($receivers, $message);
				//pre($hasil);
			#	echo "berhasil ngirim notif";
			}else{
				echo "data tidak ada";
			}
		}else{
			$pesan = ' Curl tidak aktif tidak bisa kirim notifikasi berita ke user.';
		}
	}

	public function insert_posisi(){ 
		$data = array();

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$idUser = $this->input->post('f_idUser');
		$lat = $this->input->post('f_lat');
		$lng = $this->input->post('f_lng');
		
		$waktu = date('Y-m-d H:i:s');
		$simpan['tracking_driver'] = $idUser;
		$simpan['tracking_waktu'] = $waktu;
		$simpan['tracking_lat'] = $lat;
		$simpan['tracking_lng'] = $lng;

		$status = $this->db->insert('tracking',$simpan);
		
		if($status){	
			$data['result'] = 'true';
			$data['msg'] = 'input tracking berhasil';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'input tracking gagal, silahkan coba kembali';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function get_driver(){  
$data = array();
$iddriver = $this->input->post('f_iddriver');
		$this->db->join('user', 'user.id_user=tracking.tracking_driver');
		$this->db->where('tracking_status', 1);
			$this->db->where('tracking_driver', $iddriver);
		$this->db->order_by('id_tracking', 'DESC');
	//	$this->db->group_by('tracking_driver');
		$q = $this->db->get('tracking');
		
		if($q->num_rows() > 0){				
			$data['result'] = 'true';
			$data['msg'] = 'Data driver ada';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'Data driver tidak ada.';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function insert_review(){ 
		$data = array();

		if(!$this->check_sesi()){
			$data['result'] = 'false';
			$data['msg'] = 'Sesi login expired, silahkan login kembali';		
			#pre($this->db->last_query());
			echo json_encode($data);
			return;
		}

		$idUser = $this->input->post('f_idUser');
		$driver = $this->input->post('f_driver');
		$idBooking = $this->input->post('f_idBooking');
		$rating = $this->input->post('f_ratting');
		$comment = $this->input->post('f_comment');
		
		$waktu = date('Y-m-d H:i:s');
		$simpan['review_driver'] = $driver;
		$simpan['review_user'] = $idUser;
		$simpan['review_tanggal'] = $waktu;
		$simpan['review_rating'] = $rating;
		$simpan['review_komentar'] = $comment;
		$simpan['id_booking'] = $idBooking;

		$status = $this->db->insert('review',$simpan);
		
		if($status){	
			$data['result'] = 'true';
			$data['msg'] = 'input review berhasil';
		}else{
			$data['result'] = 'false';
			$data['msg'] = 'input review gagal, silahkan coba kembali';
		}
		
		#pre($this->db->last_query());
		echo json_encode($data);
	}


	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */