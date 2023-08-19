<?php namespace App\Models;
use CodeIgniter\Model;

class Adminrights_model extends Model 
{
	var $table = 'userrights';
	
    public function getData()
	{
		$builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->where('userrowid', 1)
			                 ->orderBy('menuoption')
			                 ->get();
		return($query->getResultArray());
	}
        
    
	public function checkDuplicate($request)
    {
		$builder = $this->db->table($this->table);
		$query = $builder->select('menuoption')
			                 ->where('menuoption', $request->getPost('menuoption'))
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
		$builder->selectMax('rightrowid');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->rightrowid + 1;
    	$data = array(
	        'rightrowid' => $current_row
	        , 'userrowid' => 1
	        , 'menuoption' => $request->getPost('menuOption')
	        , 'controllername' => $request->getPost('controllerName')
	        , 'createdBy' => session('userRowId')
	        , 'createdStamp' => date('Y-m-d H:i')
		);
		$this->db->table($this->table)->insert($data);
		$this->db->transComplete();		
	}



	public function deleteNow($request)
	{
		$where = array('rightrowid' => $request->getPost('rowId'));
		$this->db->table($this->table)->delete($where);
	}
}