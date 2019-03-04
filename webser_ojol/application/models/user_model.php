<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class User_model extends CI_Model{
		var $tabel = 'user';
		var $statusData = 'user_status';
		
		function __construct(){
			parent::__construct();
	    } 

		function userArray(){
			$data = array();
			$this->db->where($this->statusData,1);
			$this->db->order_by("user_nama","ASC");
			$query = $this->db->get($this->tabel);
			foreach($query->result() as $row){
				$data[$row->id_user] = $row->user_nama;
			}
			
			return $data;
		}
	    
		//simpan data
		function addData($data)
		{
			return $this->db->insert($this->tabel, $data);
		}

		//update data
		function updateData($id, $data){
			$this->db->where('id_user',$id);
			return $this->db->update($this->tabel, $data);
		}
		
		//ambil data berdasarkan id tertentu
		function getDataById($id){
		    $id = intval( $id );
		    $this->db->where('id_user',$id);
			$this->db->where($this->statusData,1);
			
			$query = $this->db->get($this->tabel);
		 
			return $query;
			
		}
		
		//hitung jumlah total data
		function countData($cari = '')
		{
			$this->db->where($this->statusData,1);
			if($cari != '')	{
				$this->db->like('user_nama', $cari);
			}
			
			return $this->db->count_all_results($this->tabel);
		}
		
		//ambil data perhalaman
		function getData($limit, $offset, $cari = '')
		{
			
			$offset = (int)$offset;		
			$this->db->where($this->statusData,1);
			//join field yang berelasi	
			if($cari != '')	{
				$this->db->like('user_nama', $cari);
			}
			//$this->db->join('user_cluster uc', 'uc.id_user = user.id_user','left');
			$this->db->limit($limit,$offset);
			$this->db->order_by('id_user', 'DESC');
			$query = $this->db->get($this->tabel);
			#pre($this->db->last_query());
			return $query;
		}
	    
			
	}

//riyadi | 24 Januari 2014 | ci generator V3.0
//END user_model Class
//lokasi: ./application/model/user_model.php