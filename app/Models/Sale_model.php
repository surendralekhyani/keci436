<?php namespace App\Models;
use CodeIgniter\Model;

class Sale_model extends Model 
{
    var $table = 'db';

    public function getCustInfo($invNo)
    {
        $builder = $this->db->table('db');
        $query = $builder->select('db.customerRowId, db.dbDt, customers.customerName, customers.address')
                             ->where('db.dbRowId', $invNo)
                             ->join('customers','customers.customerRowId = db.customerRowId')
                             ->get();
        return($query->getResultArray());
    }

    public function getProducts($invNo)
    {
        $builder = $this->db->table('dbdetail');
        $query = $builder->select('dbdetail.dbdRowId, dbdetail.dbRowId, dbdetail.itemRowId, dbdetail.qty, dbdetail.rate, dbdetail.amt, dbdetail.discountPer, dbdetail.discountAmt, dbdetail.pretaxAmt, dbdetail.igst, dbdetail.igstAmt, dbdetail.cgst, dbdetail.cgstAmt, dbdetail.sgst, dbdetail.sgstAmt, dbdetail.netAmt, dbdetail.pp, dbdetail.itemRemarks, items.itemName, items.hsn, db.netAmt as grandTotal')
                             ->where('dbdetail.dbRowId', $invNo)
                             ->join('db','db.dbRowId = dbdetail.dbRowId')
                             ->join('items', 'items.itemRowId = dbdetail.itemRowId')
                             ->orderBy('dbdRowId')
                             ->get();
        return($query->getResultArray());
    }

    public function getCustomerInfo($request)
    {
        $builder = $this->db->table('customers');
        $query = $builder->select('customers.*')
                             ->where('customerRowId', $request->getPost('customerRowId'))
                             ->get();
        return($query->getResultArray());
    }   

    public function getItems()
	{
        $builder = $this->db->table('items');
        $query = $builder->select('itemRowId, itemName, sellingPrice as rate, pp, hsn')
                            ->where('deleted', 'N')
                            ->orderBy('itemName')
                            ->get();
        return($query->getResultArray());
	}	

    
	public function insertNow($request)
    {
        $this->db->transStart();
        $customerRowId = $request->getPost('customerRowId');
        if( $customerRowId == -1 ) ///new customer
        {
            $builder = $this->db->table('customers');
            $builder->selectMax('customerRowId');
            $query = $builder->get();
            $row = $query->getRow();
            $customerRowId = $row->customerRowId + 1;
            $data = array(
                'customerRowId' => $customerRowId
                , 'customerName' => $request->getPost('customerName')
                , 'address' => $request->getPost('address')         
                , 'mobile1' => $request->getPost('mobile1')
                , 'remarks' => $request->getPost('customerRemarks')
                , 'createdBy' => session('userRowId')
                , 'createdStamp' => date('Y-m-d H:i')
            );
            $this->db->table('customers')->insert($data);
        }
        

        ///Inserting in DB table
        $builder = $this->db->table('db');
        $builder->selectMax('dbRowId');
        $query = $builder->get();
        $row = $query->getRow();
        $dbRowId = $row->dbRowId + 1;
        // sleep(20);

        $dt = date('Y-m-d', strtotime($request->getPost('dt')));
        if($request->getPost('dueDate') == '')
        {
            $dueDate = null;
        }
        else
        {
            $dueDate = date('Y-m-d', strtotime($request->getPost('dueDate')));
        }
        $data = array(
            'dbRowId' => $dbRowId
            , 'dbDt' => $dt
            , 'customerRowId' => $customerRowId
            , 'totalAmount' => (float)$request->getPost('totalAmt')
            , 'totalDiscount' => (float)$request->getPost('totalDiscount')
            , 'pretaxAmt' => (float)$request->getPost('totalPretaxAmt')
            , 'totalIgst' => (float)$request->getPost('totalIgst')
            , 'totalCgst' => (float)$request->getPost('totalCgst')
            , 'totalSgst' => (float)$request->getPost('totalSgst')
            , 'netAmt' => (float)$request->getPost('netAmt')
            , 'advancePaid' => (float)$request->getPost('advancePaid')
            , 'balance' => (float)$request->getPost('balance')
            , 'dueDate' => $dueDate
            , 'remarks' => $request->getPost('remarks')
            , 'np' => (float)$request->getPost('np')
            , 'createdBy' => session('userRowId')
            , 'createdStamp' => date('Y-m-d H:i')
        );
        $this->db->table('db')->insert($data);

        /////Saving in DbDetail
        $TableData = $request->getPost('TableDataItems');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);
        for ($i=0; $i < $myTableRows; $i++) 
        {
            $builder = $this->db->table('dbdetail');
            $builder->selectMax('dbdRowId');
            $query = $builder->get();
            $row = $query->getRowArray();
            $itemRowId = -2;
            $dbdRowId = $row['dbdRowId']+1;
            if ( $TableData[$i]['itemRowId'] == "-1")
            {
                $builder = $this->db->table('items');
                $builder->selectMax('itemRowId');
                $query = $builder->get();
                $row = $query->getRowArray();
                $itemRowId = $row['itemRowId'] + 1;
                $data = array(
                    'itemRowId' => $itemRowId
                    , 'itemName' => ucwords($TableData[$i]['itemName'])
                    , 'createdBy' => session('userRowId')
                    , 'createdStamp' => date('Y-m-d H:i')
                );
                $this->db->table('items')->insert($data); 
            }
            else
            {
                $itemRowId = $TableData[$i]['itemRowId'];
            }

            $data = array(
                    'dbdRowId' => $dbdRowId
                    , 'dbRowId' => $dbRowId
                    , 'itemRowId' => $itemRowId
                    // , 'itemName' => $TableData[$i]['itemName']
                    , 'qty' => (float) $TableData[$i]['qty']
                    , 'rate' => (float) $TableData[$i]['rate']
                    , 'amt' => $TableData[$i]['amt']
                    , 'discountPer' => $TableData[$i]['discountPer']
                    , 'discountAmt' => $TableData[$i]['discountAmt']
                    , 'pretaxAmt' => $TableData[$i]['pretaxAmt']
                    , 'igst' => $TableData[$i]['igst']
                    , 'igstAmt' => $TableData[$i]['igstAmt']
                    , 'cgst' => $TableData[$i]['cgst']
                    , 'cgstAmt' => $TableData[$i]['cgstAmt']
                    , 'sgst' => $TableData[$i]['sgst']
                    , 'sgstAmt' => $TableData[$i]['sgstAmt']
                    , 'netAmt' => $TableData[$i]['netAmt']
                    , 'pp' => $TableData[$i]['pp']
                    , 'itemRemarks' => $TableData[$i]['itemRemarks']
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
            , 'amt' => $request->getPost('netAmt')
            , 'bal' => $request->getPost('balance')
            , 'orderRowId' => -111
            , 'reminder' => $dueDate
            , 'dbRowId' => $dbRowId
            , 'remarks' => $request->getPost('remarks')
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
            , 'recd' => $request->getPost('advancePaid')
            , 'orderRowId' => -111
            , 'dbRowId' => $dbRowId
            , 'remarks' => $request->getPost('remarks')
        );
        $this->db->table('ledger')->insert($data);
        ////////////// END - LEDGER ENTRY   
        $this->db->transComplete();
        return $dbRowId; 
	}

    public function getThisCustomerWithBalance($request)
    {
        $q = "Select sum(amt)-sum(recd) as balance, customers.customerRowId, customers.customerName from customers LEFT JOIN ledger  ON ledger.customerRowId=customers.customerRowId AND ledger.deleted='N' WHERE customers.deleted='N' AND customers.customerRowId=" . $request->getPost('customerRowId') . " group by customers.customerRowId, customers.customerName,customers.mobile1, customers.address,customers.remarks order by customerName";
        $query = $this->db->query($q);
        return($query->getResultArray());
    }

    public function checkForUpdate()
    {
        
    }

	public function updateNow($request)
    {
        // $this->db->query('LOCK TABLE db, dbdetail, ledger WRITE');
        $this->db->transStart();
        // sleep(20);
        $customerRowId = $request->getPost('customerRowId');


        ///Updating in DB table
        $dt = date('Y-m-d', strtotime($request->getPost('dt')));
        if($request->getPost('dueDate') == '')
        {
            $dueDate = null;
        }
        else
        {
            $dueDate = date('Y-m-d', strtotime($request->getPost('dueDate')));
        }
        $data = array(
             'dbDt' => $dt
            , 'customerRowId' => $customerRowId
            , 'totalAmount' => (float)$request->getPost('totalAmt')
            , 'totalDiscount' => (float)$request->getPost('totalDiscount')
            , 'pretaxAmt' => (float)$request->getPost('totalPretaxAmt')
            , 'totalIgst' => (float)$request->getPost('totalIgst')
            , 'totalCgst' => (float)$request->getPost('totalCgst')
            , 'totalSgst' => (float)$request->getPost('totalSgst')
            , 'netAmt' => (float)$request->getPost('netAmt')
            , 'advancePaid' => (float)$request->getPost('advancePaid')
            , 'balance' => (float)$request->getPost('balance')
            , 'dueDate' => $dueDate
            , 'remarks' => $request->getPost('remarks')
            , 'np' => (float)$request->getPost('np')
        );
        $where = array('dbRowId' => $request->getPost('globalrowid'));
        $this->db->table('db')->update($data, $where);

        /////Saving in DbDetail
        $where = array('dbRowId' => $request->getPost('globalrowid'));
        $this->db->table('dbdetail')->delete($where);

        $TableData = $request->getPost('TableDataItems');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);
        for ($i=0; $i < $myTableRows; $i++) 
        {
            $builder = $this->db->table('dbdetail');
            $builder->selectMax('dbdRowId');
            $query = $builder->get();
            $row = $query->getRowArray();
            $itemRowId = -2;
            $dbdRowId = $row['dbdRowId']+1;
            if ( $TableData[$i]['itemRowId'] == "-1")
            {
                $builder = $this->db->table('items');
                $builder->selectMax('itemRowId');
                $query = $builder->get();
                $row = $query->getRowArray();
                $itemRowId = $row['itemRowId'] + 1;
                $data = array(
                    'itemRowId' => $itemRowId
                    , 'itemName' => ucwords($TableData[$i]['itemName'])
                    , 'createdBy' => session('userRowId')
                    , 'createdStamp' => date('Y-m-d H:i')
                );
                $this->db->table('items')->insert($data); 
            }
            else
            {
                $itemRowId = $TableData[$i]['itemRowId'];
            }
            $data = array(
                    'dbdRowId' => $dbdRowId
                    , 'dbRowId' => $request->getPost('globalrowid')
                    , 'itemRowId' => $itemRowId
                    // , 'itemName' => $TableData[$i]['itemName']
                    , 'qty' => (float) $TableData[$i]['qty']
                    , 'rate' => (float) $TableData[$i]['rate']
                    , 'amt' => $TableData[$i]['amt']
                    , 'discountPer' => $TableData[$i]['discountPer']
                    , 'discountAmt' => $TableData[$i]['discountAmt']
                    , 'pretaxAmt' => $TableData[$i]['pretaxAmt']
                    , 'igst' => $TableData[$i]['igst']
                    , 'igstAmt' => $TableData[$i]['igstAmt']
                    , 'cgst' => $TableData[$i]['cgst']
                    , 'cgstAmt' => $TableData[$i]['cgstAmt']
                    , 'sgst' => $TableData[$i]['sgst']
                    , 'sgstAmt' => $TableData[$i]['sgstAmt']
                    , 'netAmt' => $TableData[$i]['netAmt']
                    , 'pp' => $TableData[$i]['pp']
                    , 'itemRemarks' => $TableData[$i]['itemRemarks']
            );
            $this->db->table('dbdetail')->insert($data);
        }
        /////END - in Invoice Detail


    ////////////// LEDGER ENTRY Dr.
        $data = array(
             'refDt' => $dt
            , 'amt' => $request->getPost('netAmt')
            , 'bal' => $request->getPost('balance')
            , 'orderRowId' => -111
            , 'reminder' => $dueDate
            , 'remarks' => $request->getPost('remarks')
        );
        $where = array('vType' => 'DB'
                        , 'refRowId' => $request->getPost('globalrowid')
                        , 'amt >' => 0);
        $this->db->table('ledger')->update($data, $where);

        ////////////// END - LEDGER ENTRY   

        ////////////// LEDGER ENTRY Cr.
        $data = array(
             'refDt' => $dt
            , 'recd' => $request->getPost('advancePaid')
            , 'orderRowId' => -111
            , 'remarks' => $request->getPost('remarks')
        );
        $where = array('vType' => 'DB'
                        , 'refRowId' => $request->getPost('globalrowid')
                        , 'amt' => 0);
        $this->db->table('ledger')->update($data, $where);
        ////////////// END - LEDGER ENTRY   
        $this->db->transComplete();	
        // $this->db->query('UNLOCK TABLES');
	}

	public function checkPossibility()
    {
        
    } 

	public function deleteNow($request)
    {
        $data = array(
                'deleted' => 'Y',
        );
        $where = array('vType' => 'DB'
                        , 'refRowId' => $request->getPost('rowId'));
        $this->db->table('ledger')->update($data, $where);

        $where = array('dbRowId' => $request->getPost('rowId'));
        $this->db->table('db')->update($data, $where);
    }



	public function getDataLimit()
    {
        $builder = $this->db->table('db');
        $query = $builder->select('db.*, customers.customerName')
                             ->join('customers', 'customers.customerRowId = db.customerRowId')
                             ->limit(15)
                             ->orderBy('dbRowId desc')
                             ->get();
        return($query->getResultArray());
    }
    public function getDataAll()
    {
        $builder = $this->db->table('db');
        $query = $builder->select('db.*, customers.customerName')
                             ->join('customers', 'customers.customerRowId = db.customerRowId')
                             ->orderBy('dbRowId desc')
                             ->get();
        return($query->getResultArray());
    }

    public function searchRecords($request)
    {
        $builder = $this->db->table('db');
        $query = $builder->select('db.*, customers.customerName')
                             ->join('customers', 'customers.customerRowId = db.customerRowId')
                             ->like('db.remarks', $request->getPost('searchWhat'))
                             ->orLike('customers.customerName', $request->getPost('searchWhat'))
                             ->orderBy('dbRowId desc')
                             ->get();
        return($query->getResultArray());
    }


    public function showDetail($request)
    {
        $builder = $this->db->table('dbdetail');
        $query = $builder->select('dbdetail.dbdRowId, dbdetail.dbRowId, dbdetail.itemRowId, dbdetail.qty, dbdetail.rate, dbdetail.amt, dbdetail.discountPer, dbdetail.discountAmt, dbdetail.pretaxAmt, dbdetail.igst, dbdetail.igstAmt, dbdetail.cgst, dbdetail.cgstAmt, dbdetail.sgst, dbdetail.sgstAmt, dbdetail.netAmt, dbdetail.pp, dbdetail.itemRemarks, items.itemName, items.pp')
                             ->where('dbRowId', $request->getPost('globalrowid'))
                             ->join('items', 'items.itemRowId = dbdetail.itemRowId')
                             ->orderBy('dbdRowId')
                             ->get();
        return($query->getResultArray());

        // $builder = $this->db->table('dbdetail');
        // $query = $builder->select('dbdetail.dbdRowId, dbdetail.dbRowId, dbdetail.itemRowId, dbdetail.qty, dbdetail.rate, dbdetail.amt, dbdetail.discountPer, dbdetail.discountAmt, dbdetail.pretaxAmt, dbdetail.igst, dbdetail.igstAmt, dbdetail.cgst, dbdetail.cgstAmt, dbdetail.sgst, dbdetail.sgstAmt, dbdetail.netAmt, dbdetail.pp, dbdetail.itemRemarks, items.itemName')
        //                      ->where('dbRowId', $request->getPost('globalrowid'))
        //                      ->join('items', 'items.itemRowId = dbdetail.itemRowId')
        //                      ->orderBy('dbdRowId')
        //                      ->get();
        // return($query->getResultArray());
    }


    public function getQuotationProducts($request)
    {
        $builder = $this->db->table('quotationdetail');
        $query = $builder->select('quotationdetail.quotationDetailRowId, quotationdetail.quotationRowId, quotationdetail.itemRowId, quotationdetail.qty, quotationdetail.rate, quotationdetail.amt, items.itemName, items.pp')
                             ->where('quotationRowId', $request->getPost('quotationRowId'))
                             ->join('items', 'items.itemRowId = quotationdetail.itemRowId')
                             ->orderBy('quotationRowId')
                             ->get();
        return($query->getResultArray());
    }

    public function getSaleDetail($request)
    {
        $builder = $this->db->table('dbdetail');
        $query = $builder->select('dbdetail.dbdRowId, dbdetail.dbRowId, dbdetail.itemRowId, dbdetail.qty, dbdetail.rate, dbdetail.amt, dbdetail.discountPer, dbdetail.discountAmt, dbdetail.pretaxAmt, dbdetail.igst, dbdetail.igstAmt, dbdetail.cgst, dbdetail.cgstAmt, dbdetail.sgst, dbdetail.sgstAmt, dbdetail.netAmt, dbdetail.pp, dbdetail.itemRemarks, items.itemName')
                             ->where('dbdetail.dbRowId', $request->getPost('dbRowId'))
                             ->join('items', 'items.itemRowId = dbdetail.itemRowId')
                             ->orderBy('dbdRowId')
                             ->get();
        return($query->getResultArray());
    }

    
    public function getSaleLog($request)
    {
        $builder = $this->db->table('dbdetail');
        $query = $builder->select('dbdetail.dbdRowId, dbdetail.dbRowId, dbdetail.itemRowId, dbdetail.qty, dbdetail.rate, dbdetail.amt, dbdetail.itemRemarks, items.itemName, customers.customerName, db.dbDt')
                             ->where('db.customerRowId', $request->getPost('customerRowId'))
                             ->where('db.deleted', 'N')
                            ->where('dbdetail.itemRowId', $request->getPost('itemRowId'))
                             ->join('db','db.dbRowId = dbdetail.dbRowId')
                             ->join('items', 'items.itemRowId = dbdetail.itemRowId')
                             ->join('customers', 'customers.customerRowId = db.customerRowId')
                             ->orderBy('db.dbDt, dbRowId')
                             ->get();
        return($query->getResultArray());
    }

}