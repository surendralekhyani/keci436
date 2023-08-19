<?php namespace App\Models;
use CodeIgniter\Model;

class Purchase_model extends Model 
{
    var $table = 'purchase';

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
        $q = 'SELECT items.itemRowId, items.itemName, items.hsn,purchaseDetailRowId, rate,igst,cgst,sgst,sellingPricePer,sp,discountPer
                FROM items
                LEFT Join
                (SELECT S.purchaseDetailRowId, S.itemRowId, S.rate,S.igst,S.cgst,S.sgst,sellingPricePer,sp,discountPer
                FROM purchasedetail AS S
                Inner Join
                (SELECT Max(S2.purchaseDetailRowId) AS MaxOfStatusID, S2.itemRowId
                FROM purchasedetail AS S2
                GROUP BY S2.itemRowId) As S3
                ON S.itemRowId=S3.itemRowId And  S.purchaseDetailRowId= S3.MaxOfStatusID) As S4
                On items.itemRowId=S4.itemRowId  WHERE items.deleted="N" ORDER BY items.itemName';

        $query = $this->db->query($q);
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
        

        ///Inserting in PV table
        $builder = $this->db->table('purchase');
        $builder->selectMax('purchaseRowId');
        $query = $builder->get();
        $row = $query->getRow();
        $purchaseRowId = $row->purchaseRowId + 1;


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
            'purchaseRowId' => $purchaseRowId
            , 'purchaseDt' => $dt
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
            , 'freightTotal' => (float)$request->getPost('totalFreight')
            , 'totalQty' => (float)$request->getPost('totalQty')
            , 'createdBy' => session('userRowId')
            , 'createdStamp' => date('Y-m-d H:i')
        );
        $this->db->table('purchase')->insert($data);

        /////Saving in purchasedetail
        $TableData = $request->getPost('TableDataItems');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);
        for ($i=0; $i < $myTableRows; $i++) 
        {
            $builder = $this->db->table('purchasedetail');
            $builder->selectMax('purchaseDetailRowId');
            $query = $builder->get();
            $row = $query->getRowArray();
            $itemRowId = -2;
            $purchaseDetailRowId = $row['purchaseDetailRowId']+1;
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
                    , 'hsn' => $TableData[$i]['hsn']
                    , 'gstRate' => (float)($TableData[$i]['igst']) + (float)($TableData[$i]['cgst']) + (float)($TableData[$i]['sgst']) 
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
                    'purchaseDetailRowId' => $purchaseDetailRowId
                    , 'purchaseRowId' => $purchaseRowId
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
                    , 'sellingPricePer' => $TableData[$i]['sellingPricePer']
                    , 'sp' => $TableData[$i]['sellingPrice']
                    , 'freight' => $TableData[$i]['freightPerItem']
                    , 'itemRemarks' => $TableData[$i]['itemRemarks']
            );
            $this->db->table('purchasedetail')->insert($data);

            ///// Updating rate in Item Master
            $data = array(
                    'sellingPrice' => $TableData[$i]['sellingPrice']
                    , 'pp' => ($TableData[$i]['netAmt'] / $TableData[$i]['qty']) + $TableData[$i]['freightPerItem']
                    , 'hsn' => $TableData[$i]['hsn']

            );
            $where = array('itemRowId' => $itemRowId);
            $this->db->table('items')->update($data, $where);
            ///// END - Updating rate in Item Master  
        }
        /////END - in pv Detail


    ////////////// LEDGER ENTRY .
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
            , 'amt' => $request->getPost('advancePaid')
            , 'orderRowId' => -111
            , 'reminder' => $dueDate
            , 'dbRowId' => $purchaseRowId
            , 'remarks' => $request->getPost('remarks')
        );
        $this->db->table('ledger')->insert($data);
        ////////////// END - LEDGER ENTRY   

        ////////////// LEDGER ENTRY .
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
            , 'recd' => $request->getPost('netAmt')
            , 'orderRowId' => -111
            , 'dbRowId' => $purchaseRowId
            , 'remarks' => $request->getPost('remarks')
        );
        $this->db->table('ledger')->insert($data);
        ////////////// END - LEDGER ENTRY   

        $this->db->transComplete();
        return $purchaseRowId; 
	}

    public function checkForUpdate()
    {
       
    }

	public function updateNow($request)
    {
        $this->db->transStart();
        $customerRowId = $request->getPost('customerRowId');

        ///Updating in PV table
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
             'purchaseDt' => $dt
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
            , 'freightTotal' => (float)$request->getPost('totalFreight')
            , 'totalQty' => (float)$request->getPost('totalQty')
        );
        $where = array('purchaseRowId' => $request->getPost('globalrowid'));
        $this->db->table('purchase')->update($data, $where);

        /////Saving in PvDetail
        $where = array('purchaseRowId' => $request->getPost('globalrowid'));
        $this->db->table('purchasedetail')->delete($where);

        $TableData = $request->getPost('TableDataItems');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);
        for ($i=0; $i < $myTableRows; $i++) 
        {
            $builder = $this->db->table('purchasedetail');
            $builder->selectMax('purchaseDetailRowId');
            $query = $builder->get();
            $row = $query->getRowArray();
            $itemRowId = -2;
            $purchaseDetailRowId = $row['purchaseDetailRowId']+1;
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
                    'purchaseDetailRowId' => $purchaseDetailRowId
                    , 'purchaseRowId' => $request->getPost('globalrowid')
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
                    , 'sellingPricePer' => $TableData[$i]['sellingPricePer']
                    , 'sp' => $TableData[$i]['sellingPrice']
                    , 'freight' => $TableData[$i]['freightPerItem']
                    , 'itemRemarks' => $TableData[$i]['itemRemarks']
            );
            $this->db->table('purchasedetail')->insert($data);

            ///// Updating rate in Item Master
            $data = array(
                    'sellingPrice' => $TableData[$i]['sellingPrice']
                    , 'pp' => ($TableData[$i]['netAmt'] / $TableData[$i]['qty']) + $TableData[$i]['freightPerItem']
                    , 'hsn' => $TableData[$i]['hsn']
            );
            $where = array('itemRowId' => $itemRowId);
            $this->db->table('items')->update($data, $where);
            ///// END - Updating rate in Item Master  
        }
        /////END - in Pv Detail


    ////////////// LEDGER ENTRY Dr.
        $data = array(
             'refDt' => $dt
            , 'amt' => $request->getPost('advancePaid')
            // , 'bal' => $this->input->post('balance')
            , 'orderRowId' => -111
            , 'reminder' => $dueDate
            , 'remarks' => $request->getPost('remarks')
        );
        $where = array('vType' => 'PV'
                        , 'refRowId' => $request->getPost('globalrowid')
                        , 'recd' => 0);
        $this->db->table('ledger')->update($data, $where);
        ////////////// END - LEDGER ENTRY   

        ////////////// LEDGER ENTRY Cr.
        $data = array(
             'refDt' => $dt
            , 'recd' => $request->getPost('netAmt')
            , 'orderRowId' => -111
            , 'remarks' => $request->getPost('remarks')
        );

        // $where = array('vType' => 'PV'
        //                 , 'refRowId' => $request->getPost('globalrowid')
        //                 , 'amt' => 0
        //                 , 'reminder' => null);

        $where = array('vType' => 'PV'
                        , 'refRowId' => $request->getPost('globalrowid')
                        , 'amt' => 0
                        , 'recd>' => 0);                        
        $this->db->table('ledger')->update($data, $where);
        ////////////// END - LEDGER ENTRY   
        $this->db->transComplete();     
	}

	public function checkPossibility()
    {
        
    } 

	public function deleteNow($request)
    {
        $this->db->transStart(); 

        $data = array(
                'deleted' => 'Y',
        );
        $where = array('vType' => 'PV'
                        , 'refRowId' => $request->getPost('rowId'));
        $this->db->table('ledger')->update($data, $where);

        $where = array('purchaseRowId' => $request->getPost('rowId'));
        $this->db->table('purchase')->update($data, $where);

        $this->db->transComplete();     
    }



	public function getDataLimit()
    {
        $builder = $this->db->table('purchase');
        $query = $builder->select('purchase.*, customers.customerName')
                             ->join('customers', 'customers.customerRowId = purchase.customerRowId')
                             ->limit(15)
                             ->orderBy('purchaseRowId desc')
                             ->get();
        return($query->getResultArray());
    }
    public function getDataAll()
    {
        $builder = $this->db->table('purchase');
        $query = $builder->select('purchase.*, customers.customerName')
                             ->join('customers', 'customers.customerRowId = purchase.customerRowId')
                             ->orderBy('purchaseRowId desc')
                             ->get();
        return($query->getResultArray());
    }

    public function showDetail($request)
    {
        $builder = $this->db->table('purchasedetail');
        $query = $builder->select('purchasedetail.purchaseDetailRowId, purchasedetail.purchaseRowId, purchasedetail.itemRowId, purchasedetail.qty, purchasedetail.rate, purchasedetail.amt, purchasedetail.discountPer, purchasedetail.discountAmt, purchasedetail.pretaxAmt, purchasedetail.igst, purchasedetail.igstAmt, purchasedetail.cgst, purchasedetail.cgstAmt, purchasedetail.sgst, purchasedetail.sgstAmt, purchasedetail.netAmt, purchasedetail.sellingPricePer, purchasedetail.sp, purchasedetail.freight, purchasedetail.itemRemarks, items.itemName, items.hsn')
                             ->where('purchaseRowId', $request->getPost('globalrowid'))
                             ->join('items', 'items.itemRowId = purchasedetail.itemRowId')
                             ->orderBy('purchaseDetailRowId')
                             ->get();
        return($query->getResultArray());
    }

    public function searchRecords($request)
    {
        $builder = $this->db->table('purchase');
        $query = $builder->select('purchase.*, customers.customerName')
                             ->like('purchase.remarks', $request->getPost('searchWhat'))
                             ->orLike('customers.customerName', $request->getPost('searchWhat'))
                             ->join('customers', 'customers.customerRowId = purchase.customerRowId')
                             ->orderBy('purchaseRowId desc')
                             ->get();
        return($query->getResultArray());
    }

    public function getPurchaseDetail($request)
    {
        $builder = $this->db->table('purchasedetail');
        $query = $builder->select('purchasedetail.purchaseDetailRowId, purchasedetail.purchaseRowId, purchasedetail.itemRowId, purchasedetail.qty, purchasedetail.rate, purchasedetail.amt, purchasedetail.discountPer, purchasedetail.discountAmt, purchasedetail.pretaxAmt, purchasedetail.igst, purchasedetail.igstAmt, purchasedetail.cgst, purchasedetail.cgstAmt, purchasedetail.sgst, purchasedetail.sgstAmt, purchasedetail.netAmt, purchasedetail.sellingPricePer, purchasedetail.sp, purchasedetail.freight, purchasedetail.itemRemarks, items.itemName')
                             ->where('purchasedetail.purchaseRowId', $request->getPost('purchaseRowId'))
                             ->join('items', 'items.itemRowId = purchasedetail.itemRowId')
                             ->orderBy('purchaseDetailRowId')
                             ->get();
        return($query->getResultArray());
    }

    public function getPurchaseLog($request)
    {
        $builder = $this->db->table('purchasedetail');
        $query = $builder->select('purchasedetail.purchaseDetailRowId, purchasedetail.purchaseRowId, purchasedetail.itemRowId, purchasedetail.qty, purchasedetail.rate, purchasedetail.amt, purchasedetail.discountPer, purchasedetail.discountAmt, purchasedetail.pretaxAmt, purchasedetail.igst, purchasedetail.igstAmt, purchasedetail.cgst, purchasedetail.cgstAmt, purchasedetail.sgst, purchasedetail.sgstAmt, purchasedetail.netAmt, purchasedetail.sellingPricePer, purchasedetail.sp, purchasedetail.freight, purchasedetail.itemRemarks, items.itemName, customers.customerName, purchase.purchaseDt')
                             ->where('purchase.deleted', 'N')
                            ->where('purchasedetail.itemRowId', $request->getPost('itemRowId'))
                             ->join('purchase','purchase.purchaseRowId = purchasedetail.purchaseRowId')
                             ->join('items', 'items.itemRowId = purchasedetail.itemRowId')
                             ->join('customers', 'customers.customerRowId = purchase.customerRowId')
                             ->orderBy('purchase.purchaseDt, purchaseRowId')
                             ->get();
        return($query->getResultArray());
    }

    

    // }
    // public function getProducts($invNo)
    // {
    //     $this->db->select('purchasedetail.*, purchase.netAmt as grandTotal');
    //     $this->db->from('purchasedetail');
    //     $this->db->where('purchasedetail.purchaseRowId', $invNo);
    //     $this->db->join('purchase','purchase.purchaseRowId = purchasedetail.purchaseRowId');
    //     $this->db->order_by('purchaseDetailRowId');
    //     $query = $this->db->get();

    //     return($query->result_array());
    // }
}