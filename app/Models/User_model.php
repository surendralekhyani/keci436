<?php namespace App\Models;
use CodeIgniter\Model;
// use App\Models\Loghash_model;

class User_model extends Model 
{
	var $table = 'users';
	
    public function checkDuplicate($request)
    {
    	$builder = $this->db->table($this->table);
		$query = $builder->select('uid')
			                 ->where('uid', $request->getPost('uid'))
			                 ->limit(1)
			                 ->get();
		$row = $query->getRow();
		if (isset($row))
		{
			return 1;
		}
    }
    public function insertNow($request)
    {
    	$this->db->transStart();
		$builder = $this->db->table($this->table);
		$builder->selectMax('rowid');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->rowid + 1;

		// $pwd  = $request->getPost('password');
		// $modelLogHash = new Loghash_model();
  //   	$pwd  = $modelLogHash->create_hash($pwd);
		$pwd  = password_hash($request->getPost('password'), PASSWORD_DEFAULT);

    	$data = array(
	        'rowid' => $current_row
	        , 'uid' => $request->getPost('uid')
	        , 'pwd' => $pwd
	        , 'createdbyrowid' => session('userRowId')
	        , 'createdStamp' => date('Y-m-d H:i')
		);
		$this->db->table($this->table)->insert($data);
		$this->db->transComplete();
				
	}	

	public function checkDuplicateOnUpdate($request)
    {
    	$builder = $this->db->table($this->table);
		$query = $builder->select('uid')
			                 ->where('uid', $request->getPost('uid'))
			                 ->where('rowid !=', $request->getPost('rowid'))
			                 ->limit(1)
			                 ->get();
		$row = $query->getRow();
		if (isset($row))
		{
			return 1;
		}
    }
	public function updateNow($request)
	{
		$this->db->transStart();
    	$data = array(
	        'uid' => $request->getPost('uid')
		);
		$where = array('rowid' => $request->getPost('rowid'));
		$this->db->table($this->table)->update($data, $where);			
		$this->db->transComplete();	
	}

	public function deleteNow($request)
	{
		$this->db->transStart();
		$data = array(
	        'deleted' => 'Y'
	        , 'deletedbyrowid' => session('userRowId')
        	, 'deletedstamp' => date('Y-m-d H:i')
		);
		$where = array('rowid' => $request->getPost('rowid'));
		$this->db->table($this->table)->update($data, $where);

		// $this->db->table($this->table)->delete($where); 
		$this->db->transComplete();		
	}

	public function getData()
	{
		$builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->where('deleted', 'N')
			                 ->where('uid!=', 'admin')
			                 ->orderBy('uid')
			                 ->get();
		return($query->getResultArray());
	}

	// public function getUsersForCheckBox()
	// {
	// 	$this->db->select('rowid, uid');
	// 	$this->db->where('deleted', 'N');
	// 	$this->db->order_by('rowid');
	// 	$query = $this->db->get('users');
	// 	$arr = array();
	// 	foreach ($query->result_array() as $row)
	// 	{
 //    		$arr[$row['rowid']]= $row['uid'];
	// 	}

	// 	return $arr;
	// }
}