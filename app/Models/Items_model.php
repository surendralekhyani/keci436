<?php namespace App\Models;
use CodeIgniter\Model;

class Items_model extends Model 
{
	var $table = 'items';

    public function getDataLimit()
	{
        $builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->where('deleted', 'N')
			                 ->limit(5)
			                 ->orderBy('itemRowId desc')
			                 ->get();
		return($query->getResultArray());
	}

    public function getDataAll()
	{
		$builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->where('deleted', 'N')
			                 // ->limit(5)
			                 ->orderBy('itemRowId desc')
			                 ->get();
		return($query->getResultArray());
	}
    

	public function checkDuplicate($request)
    {
		$builder = $this->db->table($this->table);
		$query = $builder->select('itemName')
			                 ->where('itemName', $request->getPost('itemName'))
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
		$builder->selectMax('itemRowId');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->itemRowId + 1;
    	$data = array(
	        'itemRowId' => $current_row
	        , 'itemName' => $request->getPost('itemName')
	        , 'sellingPrice' => $request->getPost('sellingPrice')	        
	        , 'pp' => $request->getPost('purchasePrice')	        
	        , 'gstRate' => $request->getPost('gstRate')	        
	        , 'hsn' => $request->getPost('hsn')	        
	        , 'createdBy' => session('userRowId')
	        , 'createdStamp' => date('Y-m-d H:i')
		);
		$this->db->table($this->table)->insert($data);
		$this->db->transComplete();		
	}

	public function checkDuplicateOnUpdate($request)
    {
    	$builder = $this->db->table($this->table);
		$query = $builder->select('itemRowId')
			                 ->where('itemName', $request->getPost('itemName'))
			                 ->where('itemRowId !=', $request->getPost('globalrowid'))
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
	        'itemName' => $request->getPost('itemName')
	        , 'sellingPrice' => $request->getPost('sellingPrice')	        
	        , 'pp' => $request->getPost('purchasePrice')	        
	        , 'gstRate' => $request->getPost('gstRate')	        
	        , 'hsn' => $request->getPost('hsn')	        
		);
		$where = array('itemRowId' => $request->getPost('globalrowid'));
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
		$where = array('itemRowId' => $request->getPost('rowId'));
		$this->db->table($this->table)->update($data, $where);
		$this->db->transComplete();		
	}

}