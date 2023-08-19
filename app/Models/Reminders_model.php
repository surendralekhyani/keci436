<?php namespace App\Models;
use CodeIgniter\Model;

class Reminders_model extends Model 
{
	var $table = 'reminders';

    public function getDataLimit()
	{
		$builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->where('deleted', 'N')
			                 ->orderBy('reminderRowId desc')
			                 ->get();
		return($query->getResultArray());
	}


	public function checkDuplicate($request)
    {
    	$builder = $this->db->table($this->table);
		$query = $builder->select('remarks')
			                 ->where('remarks', $request->getPost('remarks'))
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
    	$dt = date('Y-m-d', strtotime($request->getPost('dt')));
		$builder = $this->db->table($this->table);
		$builder->selectMax('reminderRowId');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->reminderRowId + 1;
    	$data = array(
	        'reminderRowId' => $current_row
	        , 'dt' => $dt
	        , 'remarks' => $request->getPost('remarks')
	        , 'repeat' => $request->getPost('repeat')
	        , 'createdBy' => session('userRowId')
	        , 'createdStamp' => date('Y-m-d H:i')
		);
		$this->db->table($this->table)->insert($data);
		$this->db->transComplete();	
	}

	public function checkDuplicateOnUpdate($request)
    {
    	$builder = $this->db->table($this->table);
		$query = $builder->select('remarks')
			                 ->where('remarks', $request->getPost('remarks'))
			                 ->where('reminderRowId !=', $request->getPost('globalrowid'))
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
    	$dt = date('Y-m-d', strtotime($request->getPost('dt')));
    	$data = array(
	        'dt' => $dt
	        , 'remarks' => $request->getPost('remarks')	        
	        , 'repeat' => $request->getPost('repeat')
		);
		$where = array('reminderRowId' => $request->getPost('globalrowid'));
		$this->db->table($this->table)->update($data, $where);			
		$this->db->transComplete();	
	}

	public function deleteNow($request)
	{
		$where = array('reminderRowId' => $request->getPost('rowId'));
		$this->db->table($this->table)->delete($where); 
	}

}