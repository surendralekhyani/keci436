<?php namespace App\Models;
use CodeIgniter\Model;

class Todo_model extends Model 
{
	var $table = 'todo';

    public function getDataLimit()
	{
		$builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->orderBy('toDoRowId desc')
			                 ->get();
		return($query->getResultArray());
	}


	public function checkDuplicate($request)
    {
		$builder = $this->db->table($this->table);
		$query = $builder->select('toDoName')
			                 ->where('toDoName', $request->getPost('toDoName'))
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
		$builder->selectMax('toDoRowId');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->toDoRowId + 1;
    	$data = array(
	        'toDoRowId' => $current_row
	        , 'toDoName' => $request->getPost('toDoName')
	        , 'createdBy' => session('userRowId')
	        , 'createdStamp' => date('Y-m-d H:i')
		);
		$this->db->table($this->table)->insert($data);
		$this->db->transComplete();	
	}

	public function checkDuplicateOnUpdate($request)
    {
		$builder = $this->db->table($this->table);
		$query = $builder->select('toDoName')
			                 ->where('toDoName', $request->getPost('toDoName'))
			                 ->where('toDoRowId !=', $request->getPost('globalrowid'))
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
	        'customerName' => $request->getPost('customerName')
	        , 'address' => $request->getPost('address')	        
	        , 'mobile1' => $request->getPost('mobile1')
	        , 'mobile2' => $request->getPost('mobile2')
	        , 'remarks' => $request->getPost('remarks')
	        , 'remarks2' => $request->getPost('remarks2')
		);
		$where = array('customerRowId' => $request->getPost('globalrowid'));
		$this->db->table($this->table)->update($data, $where);			
		$this->db->transComplete();	
	}

	public function deleteNow($request)
	{
		$this->db->transStart();
		$data = array(
	        'deleted' => 'Y'
	        , 'deletedBy' => session('userRowId')
        	, 'deletedStamp' => date('Y-m-d H:i')
		);
		$where = array('toDoRowId' => $request->getPost('rowId'));
		$this->db->table($this->table)->update($data, $where);

		$this->db->transComplete();	
	}

}