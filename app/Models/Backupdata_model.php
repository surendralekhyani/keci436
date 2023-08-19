<?php namespace App\Models;
use CodeIgniter\Model;

class Backupdata_model extends Model 
{
	var $table = 'customers';
        // public function __construct()
        // {
        //         $this->load->database('');
        // }


        // public function insert()
	    // {
		// 	$this->db->select_max('itemRowId');
		// 	$query = $this->db->get('items');
	    //     $row = $query->row_array();

	    //     $current_row = $row['itemRowId']+1;

		// 	$data = array(
		//         'itemRowId' => $current_row
		//         , 'itemName' => 'aa'
		//         // , 'sellingPrice' => $this->input->post('sellingPrice')
		//         // , 'createdBy' => $this->session->userRowId
		// 	);
		// 	$this->db->set('createdStamp', 'NOW()', FALSE);
		// 	$this->db->insert('items', $data);	
		// }

		// public function alterDb()
	    // {
		// 	$this->load->dbforge();
		// 	// $fields = array(
		// 	//         'rechargeLimit' => array('type' => 'INT', 'default' => 0, 'after' => 'electricianNo')
		// 	//         , 'rechargeMobile' => array('type' => 'varchar', 'constraint' => '50', 'after' => 'rechargeLimit')
		// 	// );
		// 	$fields = array(
		// 	        'rechargeLimit' => array('type' => 'INT', 'default' => 0, 'after' => 'electricianNo')
		// 	        , 'rechargeMobile' => array('type' => 'varchar', 'constraint' => '50', 'after' => 'rechargeLimit')
		// 	);
		// 	$this->dbforge->add_column('organisation', $fields);
		// 	// Executes: ALTER TABLE table_name ADD preferences TEXT
		// }

		// public function tableToArray()
	    // {
	    // 	$this->db->select('purchasedetail.purchaseDetailRowId, purchasedetail.itemRowId, purchase.customerRowId, purchase.purchaseDt');
		// 	$this->db->from('purchasedetail');
		// 	$this->db->join('purchase','purchase.purchaseRowId=purchasedetail.purchaseRowId');
		// 	$this->db->where('purchase.deleted', 'N');
		// 	$this->db->order_by('purchasedetail.purchaseDetailRowId, purchasedetail.purchaseRowId');
		// 	$query = $this->db->get();

		// 	$rows = array();
		// 	foreach($query->result_array() as $row)
		// 	{
		// 		///// CustomerInfo
		// 		$row['customerName'] = "";
		// 		$this->db->select('customers.customerName');
		// 		$this->db->from('customers');
		// 		$this->db->where('customers.customerRowId', $row['customerRowId']);
		// 		$queryCustomers = $this->db->get();
		// 		$rowCustomer = $queryCustomers->row_array();
		// 		if (isset($rowCustomer))
		// 		{
		// 		    $row['customerName'] = $rowCustomer['customerName'];
		// 		}
		// 	    ///// END - CustomerInfo

		// 		///// ItemInfo
		// 		$row['itemName'] = "";
		// 		$this->db->select('items.itemName');
		// 		$this->db->from('items');
		// 		$this->db->where('items.itemRowId', $row['itemRowId']);
		// 		$queryItems = $this->db->get();
		// 		$rowItems = $queryItems->row_array();
		// 		if (isset($rowItems))
		// 		{
		// 		    $row['itemName'] = $rowItems['itemName'];
		// 		}
		// 	    // $row['test'] = $row['customerRowId'];
		// 	    ///// END - ItemInfo
		// 	    $rows[] = $row;
		// 	}

		// 	return $rows;
	    // }

	    // public function copyItems()
	    // {
	    // 	$this->db->select('items.*');
		// 	$this->db->from('items');
		// 	// $this->db->where('purchase.deleted', 'N');
		// 	$this->db->order_by('items.itemRowId');
		// 	$query = $this->db->get();

		// 	foreach($query->result_array() as $row)
		// 	{
		// 		$this->db->select_max('itemRowId');
		// 		$query = $this->db->get('items');
		//         $row1 = $query->row_array();
		//         $current_row = $row1['itemRowId']+1;
		// 		$data = array(
		// 	        'itemRowId' => $current_row
		// 	        , 'itemName' => $row['itemName']
		// 	        , 'sellingPrice' => $row['sellingPrice']
		// 	        , 'createdBy' => $this->session->userRowId
		// 		);
		// 		$this->db->set('createdStamp', 'NOW()', FALSE);
		// 		$this->db->insert('items', $data);
		// 	}
	    // }

	public function createDummyData($request)
    {
        $this->db->transStart();
		for($loop=1; $loop<=10000; $loop++)
		{
			$r = rand(100, 3000);
			$builder = $this->db->table('db');
			$builder->select('*')->where('dbRowId', $r);
			$query = $builder->get();
			if($query->getNumRows() > 0)
			{
				$row = $query->getRow();
				$customerRowId = $row->customerRowId;
				$totalAmount = $row->totalAmount;
				$totalDiscount = $row->totalDiscount;
				$pretaxAmt = $row->pretaxAmt;
				$totalIgst = $row->totalIgst;
				$totalCgst = $row->totalIgst;
				$totalSgst = $row->totalIgst;
				$netAmt = $row->netAmt;
				$advancePaid = $row->advancePaid;
				$balance = $row->balance;
				$dueDate = $row->dueDate;
				$remarks = $row->remarks;
				$np = $row->np;

				///Inserting in DB table
				$builder = $this->db->table('db');
				$builder->selectMax('dbRowId');
				$query = $builder->get();
				$row = $query->getRow();
				$dbRowId = $row->dbRowId + 1;

				$dt = '2021-08-28';
				$data = array(
					'dbRowId' => $dbRowId
					, 'dbDt' => $dt
					, 'customerRowId' => $customerRowId
					, 'totalAmount' => $totalAmount
					, 'totalDiscount' => $totalDiscount
					, 'pretaxAmt' => $pretaxAmt
					, 'totalIgst' => $totalIgst
					, 'totalCgst' => $totalCgst
					, 'totalSgst' => $totalSgst
					, 'netAmt' => $netAmt
					, 'advancePaid' => $advancePaid
					, 'balance' => $balance
					, 'dueDate' => $dueDate
					, 'remarks' => $remarks
					, 'np' => $np
					, 'createdBy' => 1
					, 'createdStamp' => date('Y-m-d H:i')
				);
				$this->db->table('db')->insert($data);

				/////Saving in DbDetail
				
				$builder = $this->db->table('dbdetail');
				$builder->select('*')->where('dbRowId', $r);
				$query = $builder->get();

				foreach ($query->getResult() as $row)
				{
				// for ($i=0; $i < $query->getNumRows(); $i++) 
				// {
					// $row = $query->getRow();
					$builder = $this->db->table('dbdetail');
					$builder->selectMax('dbdRowId');
					$query = $builder->get();
					$row1 = $query->getRowArray();
					$itemRowId = -2;
					$dbdRowId = $row1['dbdRowId']+1;

					$data = array(
							'dbdRowId' => $dbdRowId
							, 'dbRowId' => $dbRowId
							, 'itemRowId' => $row->itemRowId
							, 'qty' => $row->qty
							, 'rate' => $row->rate
							, 'amt' => $row->amt
							, 'discountPer' => $row->discountPer
							, 'discountAmt' => $row->discountAmt
							, 'pretaxAmt' => $row->pretaxAmt
							, 'igst' => $row->igst
							, 'igstAmt' => $row->igstAmt
							, 'cgst' => $row->cgst
							, 'cgstAmt' => $row->cgstAmt
							, 'sgst' => $row->sgst
							, 'sgstAmt' => $row->sgstAmt
							, 'netAmt' => $row->netAmt
							, 'pp' => $row->pp
							, 'itemRemarks' => $row->itemRemarks
					);
					$this->db->table('dbdetail')->insert($data);
				}
				/////END - in Invoice Detail


				////////////// LEDGER ENTRY Dr.
				$builder = $this->db->table('ledger');
				$builder->selectMax('ledgerRowId');
				$query = $builder->get();
				$row = $query->getRowArray();
				$ledgerRowId = $row['ledgerRowId']+1;

				$data = array(
					'ledgerRowId' => $ledgerRowId
					, 'vType' => 'DB'
					, 'refRowId' => $dbRowId
					, 'refDt' => $dt
					, 'customerRowId' => $customerRowId
					, 'amt' => $netAmt
					, 'bal' => $balance
					, 'orderRowId' => -111
					, 'reminder' => $dueDate
					, 'dbRowId' => $dbRowId
					, 'remarks' => $remarks
				);
				$this->db->table('ledger')->insert($data);
				////////////// END - LEDGER ENTRY   

				////////////// LEDGER ENTRY Cr.
				$builder = $this->db->table('ledger');
				$builder->selectMax('ledgerRowId');
				$query = $builder->get();
				$row = $query->getRowArray();
				$ledgerRowId = $row['ledgerRowId']+1;
				$data = array(
					'ledgerRowId' => $ledgerRowId
					, 'vType' => 'DB'
					, 'refRowId' => $dbRowId
					, 'refDt' => $dt
					, 'customerRowId' => $customerRowId
					, 'recd' => $advancePaid
					, 'orderRowId' => -111
					, 'dbRowId' => $dbRowId
					, 'remarks' => $remarks
				);
				$this->db->table('ledger')->insert($data);
				////////////// END - LEDGER ENTRY   
			} /// outer db numrows if ends here
		}

		////// purchase
		for($loop=1; $loop<=10000; $loop++)
		{
			$r = rand(153, 1756);
			$builder = $this->db->table('purchase');
			$builder->select('*')->where('purchaseRowId', $r);
			$query = $builder->get();
			if($query->getNumRows() > 0)
			{
				$row = $query->getRow();
				$customerRowId = $row->customerRowId;
				$totalAmount = $row->totalAmount;
				$totalDiscount = $row->totalDiscount;
				$pretaxAmt = $row->pretaxAmt;
				$totalIgst = $row->totalIgst;
				$totalCgst = $row->totalIgst;
				$totalSgst = $row->totalIgst;
				$netAmt = $row->netAmt;
				$advancePaid = $row->advancePaid;
				$balance = $row->balance;
				$dueDate = $row->dueDate;
				$remarks = $row->remarks;

				///Inserting in P table
				$builder = $this->db->table('purchase');
				$builder->selectMax('purchaseRowId');
				$query = $builder->get();
				$row = $query->getRow();
				$purchaseRowId = $row->purchaseRowId + 1;

				$dt = '2021-08-29';
				$data = array(
					'purchaseRowId' => $purchaseRowId
					, 'purchaseDt' => $dt
					, 'customerRowId' => $customerRowId
					, 'totalAmount' => $totalAmount
					, 'totalDiscount' => $totalDiscount
					, 'pretaxAmt' => $pretaxAmt
					, 'totalIgst' => $totalIgst
					, 'totalCgst' => $totalCgst
					, 'totalSgst' => $totalSgst
					, 'netAmt' => $netAmt
					, 'advancePaid' => $advancePaid
					, 'balance' => $balance
					, 'dueDate' => $dueDate
					, 'remarks' => $remarks
					, 'createdBy' => 1
					, 'createdStamp' => date('Y-m-d H:i')
				);
				$this->db->table('purchase')->insert($data);

				/////Saving in purchaseDetail
				
				$builder = $this->db->table('purchasedetail');
				$builder->select('*')->where('purchaseRowId', $r);
				$query = $builder->get();

				foreach ($query->getResult() as $row)
				{
				// for ($i=0; $i < $query->getNumRows(); $i++) 
				// {
				// 	$row = $query->getRow();
					$builder = $this->db->table('purchasedetail');
					$builder->selectMax('purchaseDetailRowId');
					$query = $builder->get();
					$row1 = $query->getRowArray();
					$itemRowId = -2;
					$purchaseDetailRowId = $row1['purchaseDetailRowId']+1;

					$data = array(
							'purchaseDetailRowId' => $purchaseDetailRowId
							, 'purchaseRowId' => $purchaseRowId
							, 'itemRowId' => $row->itemRowId
							, 'qty' => $row->qty
							, 'rate' => $row->rate
							, 'amt' => $row->amt
							, 'discountPer' => $row->discountPer
							, 'discountAmt' => $row->discountAmt
							, 'pretaxAmt' => $row->pretaxAmt
							, 'igst' => $row->igst
							, 'igstAmt' => $row->igstAmt
							, 'cgst' => $row->cgst
							, 'cgstAmt' => $row->cgstAmt
							, 'sgst' => $row->sgst
							, 'sgstAmt' => $row->sgstAmt
							, 'netAmt' => $row->netAmt
							, 'sellingPricePer' => $row->sellingPricePer
							, 'itemRemarks' => $row->itemRemarks
					);
					$this->db->table('purchasedetail')->insert($data);
				}
				/////END - in Invoice Detail


				////////////// LEDGER ENTRY Dr.
				$builder = $this->db->table('ledger');
				$builder->selectMax('ledgerRowId');
				$query = $builder->get();
				$row = $query->getRowArray();
				$ledgerRowId = $row['ledgerRowId']+1;

				$data = array(
					'ledgerRowId' => $ledgerRowId
					, 'vType' => 'PV'
					, 'refRowId' => $purchaseRowId
					, 'refDt' => $dt
					, 'customerRowId' => $customerRowId
					, 'amt' => $netAmt
					, 'bal' => $balance
					, 'orderRowId' => -112
					, 'reminder' => $dueDate
					, 'dbRowId' => $purchaseRowId
					, 'remarks' => $remarks
				);
				$this->db->table('ledger')->insert($data);
				////////////// END - LEDGER ENTRY   

				////////////// LEDGER ENTRY Cr.
				$builder = $this->db->table('ledger');
				$builder->selectMax('ledgerRowId');
				$query = $builder->get();
				$row = $query->getRowArray();
				$ledgerRowId = $row['ledgerRowId']+1;
				$data = array(
					'ledgerRowId' => $ledgerRowId
					, 'vType' => 'PV'
					, 'refRowId' => $purchaseRowId
					, 'refDt' => $dt
					, 'customerRowId' => $customerRowId
					, 'recd' => $advancePaid
					, 'orderRowId' => -112
					, 'dbRowId' => $purchaseRowId
					, 'remarks' => $remarks
				);
				$this->db->table('ledger')->insert($data);
				////////////// END - LEDGER ENTRY   
			} /// outer db numrows if ends here
		}

		$this->db->transComplete();
		return; 
	}

	public function setZero($request)
    {
        // $this->db->transStart();
    	$data = array(
	        'rechargeLimit' => 0
		);
		$this->db->table('organisation')->update($data);			
		// $this->db->transComplete();		
	}
	public function showRechargeLimit()
    {
        $builder = $this->db->table('organisation');
		$query = $builder->select('rechargeLimit')
			                 ->get();
		// sleep(16);
		return($query->getResultArray());
	}
	public function plusTen()
    {
        // $this->db->transStart();
    	$builder = $this->db->table('organisation');
		$builder->selectMax('rechargeLimit');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->rechargeLimit + 10;
		sleep(16);
    	$data = array(
	        'rechargeLimit' => $current_row
		);
		$this->db->table('organisation')->update($data);
		// $this->db->transComplete();		
	}	
	public function plusTwenty()
    {
        // $this->db->transStart();
    	$builder = $this->db->table('organisation');
		$builder->selectMax('rechargeLimit');
		$query = $builder->get();
		$row = $query->getRow();
		$current_row = $row->rechargeLimit + 50;
    	$data = array(
	        'rechargeLimit' => $current_row
		);
		$this->db->table('organisation')->update($data);
		// $this->db->transComplete();		
	}	
}   