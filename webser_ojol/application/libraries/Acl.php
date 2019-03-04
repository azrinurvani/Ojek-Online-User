<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acl
{
	var $perms = array();		//Array : Stores the permissions for the user
	var $userID;			//Integer : Stores the ID of the current user
	var $userRoles = array();	//Array : Stores the roles of the current user
	var $ci;
	function __construct($config=array()) {
		$this->ci = &get_instance();
		$this->userID = floatval($config['userID']);
		$this->userRoles = $this->getUserRoles();
		$this->buildACL();
	}

	function buildACL() {
		//first, get the rules for the user's role
		if (count($this->userRoles) > 0)
		{
			$this->perms = array_merge($this->perms,$this->getRolePerms($this->userRoles));
		}
		//then, get the individual user permissions
		$this->perms = array_merge($this->perms,$this->getUserPerms($this->userID));
	}

	function getPermKeyFromID($permID) {
		//$strSQL = "SELECT `permKey` FROM `".DB_PREFIX."permissions` WHERE `ID` = " . floatval($permID) . " LIMIT 1";
		$this->ci->db->select('permision_key');
		$this->ci->db->where('id_permesion',floatval($permID));
		$sql = $this->ci->db->get('permision',1);
		$data = $sql->result();
		return $data[0]->permision_key;
	}

	function getPermNameFromID($permID) {
		//$strSQL = "SELECT `permName` FROM `".DB_PREFIX."permissions` WHERE `ID` = " . floatval($permID) . " LIMIT 1";
		$this->ci->db->select('permision_nama');
		$this->ci->db->where('id_permision',floatval($permID));
		$sql = $this->ci->db->get('permision',1);
		$data = $sql->result();
		return $data[0]->permision_nama;
	}

	function getRoleNameFromID($roleID) {
		//$strSQL = "SELECT `roleName` FROM `".DB_PREFIX."roles` WHERE `ID` = " . floatval($roleID) . " LIMIT 1";
		$this->ci->db->select('role_nama');
		$this->ci->db->where('id_role',floatval($roleID),1);
		$sql = $this->ci->db->get('role');
		$data = $sql->result();
		return $data[0]->role_nama;
	}

	function getUserRoles() {
		//$strSQL = "SELECT * FROM `".DB_PREFIX."user_roles` WHERE `userID` = " . floatval($this->userID) . " ORDER BY `addDate` ASC";

		$this->ci->db->where(array('userID'=>floatval($this->userID)));
		$this->ci->db->order_by('addDate','asc');
		$sql = $this->ci->db->get('user_role');
		$data = $sql->result();

		$resp = array();
		foreach( $data as $row )
		{
			$resp[] = $row->id_role;
		}
		return $resp;
	}

	function getAllRoles($format='ids') {
		$format = strtolower($format);
		//$strSQL = "SELECT * FROM `".DB_PREFIX."roles` ORDER BY `roleName` ASC";
		$this->ci->db->order_by('role_nama','asc');
		$sql = $this->ci->db->get('role');
		$data = $sql->result();

		$resp = array();
		foreach( $data as $row )
		{
			if ($format == 'full')
			{
				$resp[] = array("id" => $row->id_role,"name" => $row->role_nama);
			} else {
				$resp[] = $row->id_role;
			}
		}
		return $resp;
	}

	function getAllPerms($format='ids') {
		$format = strtolower($format);
		//$strSQL = "SELECT * FROM `".DB_PREFIX."permissions` ORDER BY `permKey` ASC";

		$this->ci->db->order_by('permision_key','asc');
		$sql = $this->ci->db->get('permision');
		$data = $sql->result();

		$resp = array();
		foreach( $data as $row )
		{
			if ($format == 'full')
			{
				$resp[$row->permKey] = array('id' => $row->id_permision, 'name' => $row->permision_nama, 'key' => $row->permision_key);
			} else {
				$resp[] = $row->id_permision;
			}
		}
		return $resp;
	}

	function getRolePerms($role) {
		if (is_array($role))
		{
			//$roleSQL = "SELECT * FROM `".DB_PREFIX."role_perms` WHERE `roleID` IN (" . implode(",",$role) . ") ORDER BY `ID` ASC";
			$this->ci->db->where_in('id_role',$role);
		} else {
			//$roleSQL = "SELECT * FROM `".DB_PREFIX."role_perms` WHERE `roleID` = " . floatval($role) . " ORDER BY `ID` ASC";
			$this->ci->db->where(array('id_role'=>floatval($role)));

		}
		$this->ci->db->order_by('id_role','asc');
		$sql = $this->ci->db->get('role_permision'); //$this->db->select($roleSQL);
		$data = $sql->result();
		$perms = array();
		foreach( $data as $row )
		{
			$pK = strtolower($this->getPermKeyFromID($row->id_permision));
			if ($pK == '') { continue; }
			if ($row->value === '1') {
				$hP = true;
			} else {
				$hP = false;
			}
			$perms[$pK] = array('perm' => $pK,'inheritted' => true,'value' => $hP,'name' => $this->getPermNameFromID($row->id_permision),'id' => $row->id_permision);
		}
		return $perms;
	}

	function getUserPerms($userID) {
		//$strSQL = "SELECT * FROM `".DB_PREFIX."user_perms` WHERE `userID` = " . floatval($userID) . " ORDER BY `addDate` ASC";

		$this->ci->db->where('id_user',floatval($userID));
		$this->ci->db->order_by('addDate','asc');
		$sql = $this->ci->db->get('user_permision');
		$data = $sql->result();

		$perms = array();
		foreach( $data as $row )
		{
			$pK = strtolower($this->getPermKeyFromID($row->id_permision));
			if ($pK == '') { continue; }
			if ($row->value == '1') {
				$hP = true;
			} else {
				$hP = false;
			}
			$perms[$pK] = array('perm' => $pK,'inheritted' => false,'value' => $hP,'name' => $this->getPermNameFromID($row->id_permision),'id' => $row->id_permision);
		}
		return $perms;
	}

	function hasRole($roleID) {
		foreach($this->userRoles as $k => $v)
		{
			if (floatval($v) === floatval($roleID))
			{
				return true;
			}
		}
		return false;
	}

	function hasPermission($permKey) {
		$permKey = strtolower($permKey);
		if (array_key_exists($permKey,$this->perms))
		{
			if ($this->perms[$permKey]['value'] === '1' || $this->perms[$permKey]['value'] === true)
			{
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

/* End of file Acl.php */