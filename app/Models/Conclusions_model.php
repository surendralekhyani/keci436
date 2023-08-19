<?php namespace App\Models;
use CodeIgniter\Model;

class Conclusions_model extends Model 
{
	var $table = 'conclusions';

    public function getDataLimit()
	{
		$builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->where('deleted', 'N')
			                 ->orderBy('conclusionRowId desc')
			                 ->get();
		return($query->getResultArray());
	}

	public function checkDuplicate($request)
    {
		$builder = $this->db->table($this->table);
		$query = $builder->select('conclusion')
			                 ->where('conclusion', $request->getPost('conclusion'))
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
		$builder->selectMax('conclusionRowId');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->conclusionRowId + 1;
    	$data = array(
	        'conclusionRowId' => $current_row
	        , 'context' => $request->getPost('context')
	        , 'conclusion' => $request->getPost('conclusion')	        
	        , 'createdBy' => session('userRowId')
	        , 'createdStamp' => date('Y-m-d H:i')
		);
		$this->db->table($this->table)->insert($data);
		$this->db->transComplete();		
	}

	public function checkDuplicateOnUpdate($request)
    {
		$builder = $this->db->table($this->table);
		$query = $builder->select('conclusion')
			                 ->where('conclusion', $request->getPost('conclusion'))
							 ->where('conclusionRowId !=', $request->getPost('globalrowid'))
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
	        'context' => $request->getPost('context')
	        , 'conclusion' => $request->getPost('conclusion')	        
		);
		$where = array('conclusionRowId' => $request->getPost('globalrowid'));
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
		$where = array('conclusionRowId' => $request->getPost('rowId'));
		$this->db->table($this->table)->update($data, $where);
		$this->db->transComplete();
	}

}