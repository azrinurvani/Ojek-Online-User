<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Android extends CI_Controller {
	function __construct(){
		parent::__construct();

		date_default_timezone_set('Asia/Jakarta');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		
		
	}

	public function getPopular(){
		$data = array();
		$this->db->select('id_lokasi, lokasi_nama, lokasi_see, lokasi_gambar, getDesa(id_desa) as desaNama, getKecamatanByDesa(id_desa) as kecamatanNama, lokasi_latitude, lokasi_longitude, getJenis(id_jenis) as jenisNama, lokasi_see, lokasi_alamat, lokasi_fasilitas');
		$this->db->where('lokasi_status', 1);
		$this->db->order_by('RAND()', '');
		//$this->db->order_by('lokasi_see', 'DESC');
		$this->db->order_by('lokasi_nama', 'ASC');
		$q = $this->db->get('lokasi');
		if($q->num_rows() > 0){
			$data['result'] = 'true';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['error'] = 'Tidak Ada Data';
		}			
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function getLokasi($id = ''){
		$data = array();
		$this->db->select('id_lokasi, lokasi_nama, lokasi_see, lokasi_gambar, getDesa(id_desa) as desaNama, getKecamatanByDesa(id_desa) as kecamatanNama, lokasi_latitude, lokasi_longitude, getJenis(id_jenis) as jenisNama, lokasi_see,  lokasi_alamat, lokasi_fasilitas');
		if($id != ''){
			$this->db->where('id_lokasi', $id);
		}
		$this->db->where('lokasi_status', 1);
		$this->db->order_by('lokasi_see', 'DESC');
		$this->db->order_by('lokasi_nama', 'ASC');
		$q = $this->db->get('lokasi');
		if($q->num_rows() > 0){
			$data['result'] = 'true';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['error'] = 'Tidak Ada Data';
		}			
		#pre($this->db->last_query());
		echo json_encode($data);
	}


	public function getJenis($id=''){
		$data = array();
		$isCari = $this->input->post('isCari');
		$kataCari = $this->input->post('kataCari');

		if($isCari == 'ya'){
			$this->db->like("lokasi_nama", $kataCari);
		}
		$this->db->select('jenis.id_jenis, jenis_nama, jenis_gambar, jumlahJenis(jenis.id_jenis) as jumlah');
		if($id != ''){
			$this->db->select('id_lokasi, lokasi_nama, lokasi_see, lokasi_gambar, getDesa(id_desa) as desaNama, getKecamatanByDesa(id_desa) as kecamatanNama, lokasi_latitude, lokasi_longitude, lokasi_see,lokasi_alamat, lokasi_fasilitas');
			$this->db->join('lokasi','jenis.id_jenis=lokasi.id_jenis');
			$this->db->where('lokasi.id_jenis', $id);
			$this->db->where('lokasi_status', 1);
			$this->db->order_by('lokasi_nama', 'asc');
			//$this->db->group_by('id_lokasi');
		}
		$this->db->where('jenis_status', 1);
		$this->db->order_by('jenis_nama', 'ASC');
		$q = $this->db->get('jenis');
		if($q->num_rows() > 0){
			$data['result'] = 'true';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['error'] = 'Tidak Ada Data';
		}			
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function getLokasiKeterangan($id){ 
		$this->db->query("CALL setSee(?)" , $id);
		$this->db->where('lokasi_status', 1);
		$this->db->where('id_lokasi', $id);

		$row = $this->db->get('lokasi');
		if($row->num_rows() > 0){
			echo $row->row()->lokasi_keterangan;
		}else{
			echo "Data Tidak Ada.";
		}
		
	}

	public function getEvent($id = ''){
		$data = array();
		$this->db->select('id_event, event_nama, event_gambar, event_tanggal');
		if($id != ''){
			$this->db->where('id_event', $id);
		}
		$this->db->where('event_status', 1);
		$this->db->order_by('event_tanggal', 'DESC');
		$this->db->order_by('event_nama', 'ASC');
		$q = $this->db->get('event');
		if($q->num_rows() > 0){
			$data['result'] = 'true';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['error'] = 'Tidak ada data event kegiatan';
		}			
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function getJumlahEvent($id = ''){
		$data = array();
		$this->db->select('id_event');
		$this->db->where('event_status', 1);
		$this->db->where('event_tanggal > ', date('Y-m-d H:i:s'));
		$q = $this->db->get('event');

		if($id == '1'){
			$data['jumlah'] = $q->num_rows(); 
			echo json_encode($data);

		}else{
			return $q->num_rows();
		}
		
	}

	public function getEventKeterangan($id){ 
		$this->db->where('event_status', 1);
		$this->db->where('id_event', $id);

		$row = $this->db->get('event');
		if($row->num_rows() > 0){
			echo $row->row()->event_keterangan;
		}else{
			echo "Data kegiatan tidak ditemukkan.";
		}
		
	}



	public function lokasiRadius($lat, $lng, $radius = 50){
        $data = array();

        

        $sql = "SELECT jenis.id_jenis, jenis_nama, jenis_gambar, jumlahJenis(jenis.id_jenis) as jumlah, id_lokasi, lokasi_nama, lokasi_see, lokasi_gambar, 
        	getDesa(id_desa) as desaNama, getKecamatanByDesa(id_desa) as kecamatanNama, lokasi_latitude, lokasi_longitude, lokasi_see,lokasi_alamat, jenis_marker , lokasi_fasilitas , 
        	( 6380 * acos( cos( radians(?) ) * cos( radians( lokasi_latitude ) ) * cos( radians( lokasi_longitude )
					- radians(?) ) + sin( radians(?) ) * sin( radians( `lokasi_latitude` ) ) ) ) / 0.62137  AS distance
					FROM lokasi
					JOIN jenis ON  lokasi.id_jenis = jenis.id_jenis
					WHERE lokasi_status = ? ORDER BY distance ASC";
        $query = $this->db->query($sql, array($lat, $lng, $lat, 1));

        #pre($this->db->last_query());

        if($query->num_rows() > 0){
            $data['result'] = 'true';
            $data['data'] = $query->result();
        }else{
            $data['result'] = 'false';
        }

        echo json_encode($data);

    }

    public function lokasiRadius2($lat, $lng, $radius = 50){
        $data = array();

        

        $sql = "SELECT jenis.id_jenis, jenis_nama, jenis_gambar, jumlahJenis(jenis.id_jenis) as jumlah, id_lokasi, lokasi_nama, lokasi_see, lokasi_gambar, 
        	getDesa(id_desa) as desaNama, getKecamatanByDesa(id_desa) as kecamatanNama, lokasi_latitude, lokasi_longitude, lokasi_see,lokasi_alamat, jenis_marker , lokasi_fasilitas ,
        	( 6380 * acos( cos( radians(?) ) * cos( radians( lokasi_latitude ) ) * cos( radians( lokasi_longitude )
					- radians(?) ) + sin( radians(?) ) * sin( radians( `lokasi_latitude` ) ) ) ) / 0.62137  AS distance
					FROM lokasi
					JOIN jenis ON  lokasi.id_jenis = jenis.id_jenis
					WHERE lokasi_status = ? 
					HAVING distance <= ? ORDER BY distance ASC";
        $query = $this->db->query($sql, array($lat, $lng, $lat, 1, $radius));

        #pre($this->db->last_query());

        if($query->num_rows() > 0){
            $data['result'] = 'true';
            $data['data'] = $query->result();
        }else{
            $data['result'] = 'false';
        }

        echo json_encode($data);

    }

    public function getGallery($no = ''){
    	$id = $this->input->post('f_id_lokasi');
		$data = array();
		$this->db->select('lokasi.id_lokasi, lokasi_nama, lokasi_see, lokasi_gambar, getDesa(id_desa) as desaNama, getKecamatanByDesa(id_desa) as kecamatanNama, lokasi_latitude, lokasi_longitude, getJenis(id_jenis) as jenisNama, lokasi_see,  lokasi_alamat , lokasi_fasilitas, galery.*');
		if($id != ''){
			$this->db->where('lokasi.id_lokasi', $id);
		}
		$this->db->join('galery','galery.id_lokasi=lokasi.id_lokasi');
		$this->db->where('lokasi_status', 1);
		$this->db->where('galery_status', 1);
		//$this->db->order_by('lokasi_see', 'DESC');
		$this->db->order_by('id_galery', 'ASC');
		$q = $this->db->get('lokasi');
		if($q->num_rows() > 0){
			$data['result'] = 'true';
			$data['data'] = $q->result();
		}else{
			$data['result'] = 'false';
			$data['error'] = 'Tidak Ada Data';
		}			
		#pre($this->db->last_query());
		echo json_encode($data);
	}

	public function send_notification($registatoin_ids, $message) {
        // include config
        include_once './config.php';
 
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
 
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );
 
        $headers = array(
            'Authorization: key=AIzaSyD5z332VQj0aRlCKd5TvM5RaXo_RrFXtfA',
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);
        echo $result;
    }

    public function registerGcm(){ 
		$data = array();
		
		$this->db->where('gcm_regid', $this->input->post("regid"));
		$this->db->where('gcm_device', $this->input->post("device"));
		$q = $this->db->get('gcm');
		if($q->num_rows() > 0){
			$data['result'] = 'false';
			$data['error'] = 'GCM sudah terdaftar.';
		}else{
			$data_simpan['gcm_regid'] = $this->input->post("regid");
			$data_simpan['gcm_device'] = $this->input->post("device");
			$data_simpan['created_at'] = date('Y-m-d H:i:s');
			$simpan = $this->db->insert('gcm', $data_simpan);

			if($simpan){
				$data['result'] = 'true';
				$data['error'] = 'gcm berhasil disimpan';
			}else{
				$data['result'] = 'false';
				$data['error'] = 'id Gcm gagal disimpan.';
			}
		}
		
		
		
		echo json_encode($data);
	}


	public function getFitur($id){ 
		$this->db->where('motor_status', 1);
		$this->db->where('id_motor', $id);

		$row = $this->db->get('motor');
		if($row->num_rows() > 0){
			echo $row->row()->motor_fitur;
		}else{
			echo "Data Tidak Ada.";
		}
		
	}

	public function getNikmati($id){ 
		$this->db->where('motor_status', 1);
		$this->db->where('id_motor', $id);

		$row = $this->db->get('motor');
		if($row->num_rows() > 0){
			echo $row->row()->motor_nikmati;
		}else{
			echo "Data Tidak Ada.";
		}
		
	}

	public function getTipe($id = ''){
		$this->db->where('tipe_status', 1);
		$this->db->where('motor_status', 1);
		$this->db->where('tipe_status', 1);
		$this->db->join('motor','tipe.id_tipe=motor.id_tipe');
		if($id != ''){
			$this->db->where('motor.id_motor', $id);
		}

		$q = $this->db->get('tipe');
		if($q->num_rows() > 0){
			return $q->row()->id_tipe;
		}else{
			return 0;
		}	
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */