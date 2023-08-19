<?php namespace App\Models;
use CodeIgniter\Model;

class Duplicates_model extends Model 
{
	var $table = 'items';

    public function getDataAll()
	{
		$builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->where('deleted', 'N')
			                 ->orderBy('itemName')
			                 ->get();
		return($query->getResultArray());
	}

	public function showQuotaionDetail($request)
	{
		$builder = $this->db->table('quotationdetail');
        $query = $builder->select('quotationdetail.*, items.itemName')
							 ->orWhere('quotationdetail.itemRowId', $request->getPost('hatana'))
							 ->orWhere('quotationdetail.itemRowId', $request->getPost('karna'))
                             ->join('items', 'items.itemRowId = quotationdetail.itemRowId')
                             ->orderBy('quotationDetailRowId')
                             ->get();
        return($query->getResultArray());
	}
	public function showCashSale($request)
	{
		$builder = $this->db->table('cashsale');
        $query = $builder->select('*')
							 ->orWhere('cashsale.itemRowId', $request->getPost('hatana'))
							 ->orWhere('cashsale.itemRowId', $request->getPost('karna'))
                             ->join('items', 'items.itemRowId = cashsale.itemRowId')
                             ->orderBy('dt,cashSaleRowId')
                             ->get();
        return($query->getResultArray());
	}
	public function showPurchaseDetail($request)
	{
		$builder = $this->db->table('purchasedetail');
        $query = $builder->select('purchasedetail.*, items.itemName')
							 ->orWhere('purchasedetail.itemRowId', $request->getPost('hatana'))
							 ->orWhere('purchasedetail.itemRowId', $request->getPost('karna'))
                             ->join('items', 'items.itemRowId = purchasedetail.itemRowId')
                             ->orderBy('purchaseDetailRowId')
                             ->get();
        return($query->getResultArray());
	}
	public function showSaleDetail($request)
	{
		$builder = $this->db->table('dbdetail');
        $query = $builder->select('dbdetail.*, items.itemName')
							 ->orWhere('dbdetail.itemRowId', $request->getPost('hatana'))
							 ->orWhere('dbdetail.itemRowId', $request->getPost('karna'))
                             ->join('items', 'items.itemRowId = dbdetail.itemRowId')
                             ->orderBy('dbdRowId')
                             ->get();
        return($query->getResultArray());
	}

	public function replaceNow($request)
	{
        $this->db->transStart();
		///fetching item name
		$builder = $this->db->table('items');
        $query = $builder->select('items.itemName')
							 ->orWhere('items.itemRowId', $request->getPost('karna'))
                             ->get();
		$row = $query->getRowArray();
		$itemName = "";
		if (isset($row))
		{
			$itemName = $row['itemName'];
		}


		$where = array('itemRowId' => $request->getPost('hatana'));
		/////QuotationDetail
		$data = array(
	        'itemRowId' => $request->getPost('karna')
		);
		$this->db->table('quotationdetail')->update($data, $where);

		/////Cash Sale
		$data = array(
	        'itemRowId' => $request->getPost('karna')
	        , 'itemName' => $itemName
		);
		$this->db->table('cashsale')->update($data, $where);

		/////purchaseDetail
		$data = array(
	        'itemRowId' => $request->getPost('karna')
		);
		$this->db->table('purchasedetail')->update($data, $where);

		/////dbDetail
		$data = array(
	        'itemRowId' => $request->getPost('karna')
		);
		$this->db->table('dbdetail')->update($data, $where);


		///Deleting from Items Table
		$this->db->table('items')->delete($where); 

		$this->db->transComplete();		
	}
}