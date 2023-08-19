<?php namespace App\Models;
use CodeIgniter\Model;

class Duplicatecustomers_model extends Model 
{
	var $table = 'customers';

    public function getDataAll()
	{
		$builder = $this->db->table($this->table);
		$query = $builder->select('*')
			                 ->where('deleted', 'N')
			                 ->orderBy('customerName')
			                 ->get();
		return($query->getResultArray());
	}

	public function showQuotaion($request)
	{
		$builder = $this->db->table('quotation');
        $query = $builder->select('quotation.*, customers.customerName')
							 ->orWhere('quotation.customerRowId', $request->getPost('hatana'))
							 ->orWhere('quotation.customerRowId', $request->getPost('karna'))
                             ->join('customers','customers.customerRowId = quotation.customerRowId')
                             ->orderBy('quotationRowId')
                             ->get();
        return($query->getResultArray());
	}

	public function showLedger($request)
	{
		$builder = $this->db->table('ledger');
        $query = $builder->select('ledger.*, customers.customerName')
							 ->orWhere('ledger.customerRowId', $request->getPost('hatana'))
							 ->orWhere('ledger.customerRowId', $request->getPost('karna'))
                             ->join('customers','customers.customerRowId = ledger.customerRowId')
                             ->orderBy('ledgerRowId')
                             ->get();
        return($query->getResultArray());
	}

	public function showPurchase($request)
	{
		$builder = $this->db->table('purchase');
        $query = $builder->select('purchase.*, customers.customerName')
							 ->orWhere('purchase.customerRowId', $request->getPost('hatana'))
							 ->orWhere('purchase.customerRowId', $request->getPost('karna'))
                             ->join('customers','customers.customerRowId = purchase.customerRowId')
                             ->orderBy('purchaseRowId')
                             ->get();
        return($query->getResultArray());
	}
	public function showSale($request)
	{
		$builder = $this->db->table('db');
        $query = $builder->select('db.*, customers.customerName')
							 ->orWhere('db.customerRowId', $request->getPost('hatana'))
							 ->orWhere('db.customerRowId', $request->getPost('karna'))
                             ->join('customers','customers.customerRowId = db.customerRowId')
                             ->orderBy('dbRowId')
                             ->get();
        return($query->getResultArray());
	}

	public function replaceNow($request)
	{
        $this->db->transStart();
		$where = array('customerRowId' => $request->getPost('hatana'));
		/////Quotation
		$data = array(
	        'customerRowId' => $request->getPost('karna')
		);
		$this->db->table('quotation')->update($data, $where);


		/////Ledger
		$data = array(
	        'customerRowId' => $request->getPost('karna')
		);
		$this->db->table('ledger')->update($data, $where);


		/////purchase
		$data = array(
	        'customerRowId' => $request->getPost('karna')
		);
		$this->db->table('purchase')->update($data, $where);

		/////db
		$data = array(
	        'customerRowId' => $request->getPost('karna')
		);
		$this->db->table('db')->update($data, $where);


		///Deleting from Items Table
		$this->db->table('customers')->delete($where); 

		$this->db->transComplete();		
	}


}