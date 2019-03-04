<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function pre($data){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

function e($data){
	echo $data;
}

function getlast($input){
	$x = $input;//(int)$input;
	$y = substr($x,1)+1;
	//pre($x);
	//pre($y);
	$t = substr($input,0,1).sprintf("%03s",   $y);
	return $t;
}
	

function message($kode){
	$pesan = array(0=>'Belum Ada Data', 1=>'pesan satu');
	return $pesan[$kode];
}

function sekarang(){
	$hariini = date('Y-m-d h:i:s');
	return $hariini;
}

function format_uang($money){
	//return sprintf("%01.2f", $money);
	return number_format($money, 2, ',', '.');

}

function hari($tgl=''){
	$hari = array(1=>'Senin','Selasa','Rabu','Kamis','Jum`at','Sabtu','Minggu');
	$bulan = array('01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember');
	
	if ($tgl==''){
		$hariini = date('Y-m-d h:i:s');
		
		$x = explode(' ',$hariini);
		$d = explode('-',$x[0]);
		$eee  = mktime(date("H"), date("i"), date("s"), date("n"), date("j"),  date("Y"));
		$namahari = date("N", $eee);
		
		return $hari[$namahari].', '.$d[2].' '.$bulan[$d[1]].' '.$d[0];
	}
	
	if ($tgl != '0000-00-00 00:00:00'){
		$x = explode(' ',$tgl);
		$d = explode('-',$x[0]);
		$eee  = mktime(0, 0, 0, $d[1], $d[2], $d[0]);
		$namahari = date("N", $eee);
		
		return $hari[$namahari].', '.$d[2].' '.$bulan[$d[1]].' '.$d[0];
	}else{
		$str = 'Format Data Tanggal Salah';
	}
	return $str;
}

function kolomTabel($db,$tabel){
	$data = array();
	foreach($tabel as $v){
		$data['tabel'][$v] = $v; //list tabel
		$data['fields'][$v] = "<b>".$v."</b>";
		$fields = $db->field_data($v);
		foreach($fields as $v1){
			if($v1->primary_key == 1){
				$data['primary'][$v]= $v1->name; //list primary key
				$data['fields'][$v.".".$v1->name] = $v.".".$v1->name; //list tabel + kolom primary key
			}else{
				$data['kolom'][$v][$v1->name] = $v1->name; //list kolom selain primary key
			}
			
		}		
	}
	
	return $data;
}
function buatDir($path){
	if(!is_dir($path)) //create the folder if it's not already exists
    {
      mkdir($path,0755,TRUE);
    } 
}

//filter input post
function filter($data){
	return htmlentities(strip_tags($data));
}

function server_info()
{
	global $_SERVER;
	#pre($_SERVER);
	$php_self	= isset($_SERVER['PHP_SELF'])? "http://".$_SERVER['SERVER_NAME'].
		$_SERVER['PHP_SELF']:'';
	$req_uri	= isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'';
	$referer_page 	= (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!="") ? 
		$_SERVER['HTTP_REFERER']:'';

	$remote_address = getRealIpAddr();//isset($_SERVER["REMOTE_ADDR"])?$_SERVER["REMOTE_ADDR"]:'';

	$arr_data = array(
		'referer_page'	=> $referer_page,
		'php_self'		=> $php_self,
		'req_uri'		=> $req_uri,
		'remote_address'=>$remote_address
	);

	return $arr_data;
}

function getRealIpAddr()
{
    if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      	$ip =$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      	$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
    	#echo "C";
      	$ip=$_SERVER['REMOTE_ADDR'];
    }

    #printData($_SERVER);

    return $ip;
}

function errorArray($err){
	$data = array();

	$data[1] = "INVALID REFERER SITE";

	return $data[$err];
}

//function md5+salt
function md5x($pass, $salt='aisyapandawalimafamily-gojegApp'){
	$pass = $salt.md5($pass);
	return md5($pass);
}

function ya_tidak($id = ''){
	$data = array();
	$data [1] = 'Ya';
	$data [0] = 'Tidak';

	if($id != ''){
		return $data[$id];
	}else{
		return $data;
	}

}



function aktif($id = ''){
	$data = array();
	$data [1] = 'Aktif';
	$data [0] = 'tidak';
	
	if($id != ''){
		return $data[$id];
	}else{
		return $data;
	}

}

function rupiah($data, $koma = 0){
	return number_format($data,$koma,',','.');
}

function format_neraca($id = ''){
	$data = array();
	$data [1] = 'T-Account';
	$data [2] = 'Report Form';
	
	if($id != ''){
		return $data[$id];
	}else{
		return $data;
	}

}

function isCurl(){
    return function_exists('curl_version');
}
