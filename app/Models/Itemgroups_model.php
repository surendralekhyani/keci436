<?php namespace App\Models;
use CodeIgniter\Model;

class Itemgroups_model extends Model 
{
	var $table = 'itemgroups';

    
    public function getDataLimit()
    {
    	$builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->where('deleted', 'N')
			                 // ->limit(5)
			                 ->orderBy('itemGroupRowId desc')
			                 ->get();
		return($query->getResultArray());
	}
    

	public function checkDuplicate($request)
    {
		$builder = $this->db->table($this->table);
		$query = $builder->select('itemGroupName')
			                 ->where('itemGroupName', $request->getPost('itemGroupName'))
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
		$builder->selectMax('itemGroupRowId');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->itemGroupRowId + 1;
    	$data = array(
	        'itemGroupRowId' => $current_row
	        , 'itemGroupName' => $request->getPost('itemGroupName')
		);
		$this->db->table($this->table)->insert($data);
		$this->db->transComplete();		
	}

	public function checkDuplicateOnUpdate($request)
    {
    	$builder = $this->db->table($this->table);
		$query = $builder->select('itemGroupRowId')
			                 ->where('itemGroupName', $request->getPost('itemGroupName'))
			                 ->where('itemGroupRowId !=', $request->getPost('globalrowid'))
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
        // $this->db->transStart(true); /// this line to test rollback
        $this->db->transStart();
    	$data = array(
	        'itemGroupName' => $request->getPost('itemGroupName')
		);
		$where = array('itemGroupRowId' => $request->getPost('globalrowid'));
		$this->db->table($this->table)->update($data, $where);			
		$this->db->transComplete();		
	}

	public function deleteNow($request)
	{
        $this->db->transStart();
		$data = array(
		        'deleted' => 'Y'
		);
		$where = array('itemGroupRowId' => $request->getPost('rowId'));
		$this->db->table($this->table)->update($data, $where);
		$this->db->transComplete();		
	}
}