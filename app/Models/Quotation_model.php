<?php namespace App\Models;
use CodeIgniter\Model;

class Quotation_model extends Model 
{
    var $table = 'customers';
    public function getCustomers()
    {
        $builder = $this->db->table('customers');
        $query = $builder->select('customers.*')
                             ->where('customers.deleted', 'N')
                             ->orderBy('customerName')
                             ->get();
        return($query->getResultArray());
    }   
    public function getCustomerInfo($request)
    {
        $builder = $this->db->table('customers');
        $query = $builder->select('customers.*')
                             ->where('customers.customerRowId', $request->getPost('customerRowId'))
                             ->get();
        return($query->getResultArray());
    }  
    public function getCustInfo($invNo)
    {
        $builder = $this->db->table('quotation');
        $query = $builder->select('quotation.customerRowId, quotation.quotationDt, customers.customerName, customers.address')
                             ->where('quotation.quotationRowId', $invNo)
                             ->join('customers', 'customers.customerRowId = quotation.customerRowId')
                             ->get();
        return($query->getResultArray());
    } 

    public function getItems()
	{
        $builder = $this->db->table('items');
        $query = $builder->select('itemRowId, itemName, sellingPrice as rate, pp')
                             ->where('items.deleted', 'N')
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
        

        ///Inserting in Quotation table
        $builder = $this->db->table('quotation');
        $builder->selectMax('quotationRowId');
        $query = $builder->get();
        $row = $query->getRow();
        $quotationRowId = $row->quotationRowId + 1;
        
        $dt = date('Y-m-d', strtotime($request->getPost('dt')));

        $data = array(
            'quotationRowId' => $quotationRowId
            , 'quotationDt' => $dt
            , 'customerRowId' => $customerRowId
            , 'totalAmount' => (float)$request->getPost('totalAmt')
            , 'remarks' => $request->getPost('remarks')
            , 'createdBy' => session('userRowId')
            , 'createdStamp' => date('Y-m-d H:i')
        );
        $this->db->table('quotation')->insert($data);

        /////Saving in QDetail
        $TableData = $request->getPost('TableDataItems');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);
        for ($i=0; $i < $myTableRows; $i++) 
        {
            $builder = $this->db->table('quotationdetail');
            $builder->selectMax('quotationDetailRowId');
            $query = $builder->get();
            $row = $query->getRowArray();
            $quotationDetailRowId = $row['quotationDetailRowId'] + 1;

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
                    'quotationDetailRowId' => $quotationDetailRowId
                    , 'quotationRowId' => $quotationRowId
                    , 'itemRowId' => $itemRowId
                    // , 'itemName' => $TableData[$i]['itemName']
                    , 'qty' => (float) $TableData[$i]['qty']
                    , 'rate' => (float) $TableData[$i]['rate']
                    , 'amt' => $TableData[$i]['amt']
            );
            $this->db->table('quotationdetail')->insert($data);
        }
        /////END - in Q Detail
        $this->db->transComplete();
        return $quotationRowId; 
	}

    public function checkForUpdate($request)
    {
        $builder = $this->db->table('ledger');
        $query = $builder->select('ledger.ledgerRowId')
                             ->where('vType', 'ADB')
                             ->where('quotationRowId', $request->getPost('globalrowid'))
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
        $customerRowId = $request->getPost('customerRowId');
        ///Updating in Quotation table
        $dt = date('Y-m-d', strtotime($request->getPost('dt')));
        $data = array(
             'quotationDt' => $dt
            , 'customerRowId' => $customerRowId
            , 'totalAmount' => (float)$request->getPost('totalAmt')
            , 'remarks' => $request->getPost('remarks')
        );
        $where = array('quotationRowId' => $request->getPost('globalrowid'));
        $this->db->table('quotation')->update($data, $where);

        /////Saving in QuotationDetail
        $where = array('quotationRowId' => $request->getPost('globalrowid'));
        $this->db->table('quotationdetail')->delete($where);

        $TableData = $request->getPost('TableDataItems');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);
        for ($i=0; $i < $myTableRows; $i++) 
        {
            $builder = $this->db->table('quotationdetail');
            $builder->selectMax('quotationDetailRowId');
            $query = $builder->get();
            $row = $query->getRowArray();
            $quotationDetailRowId = $row['quotationDetailRowId'] + 1;

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
                    'quotationDetailRowId' => $quotationDetailRowId
                    , 'quotationRowId' => $request->getPost('globalrowid')
                    , 'itemRowId' => $itemRowId
                    // , 'itemName' => $TableData[$i]['itemName']
                    , 'qty' => (float) $TableData[$i]['qty']
                    , 'rate' => (float) $TableData[$i]['rate']
                    , 'amt' => $TableData[$i]['amt']
            );
            $this->db->table('quotationdetail')->insert($data);    
        }
        /////END - in Detail        
        $this->db->transComplete();     
	}

	public function checkPossibility()
    {

    } 

	public function deleteNow($request)
    {
        $data = array(
                'deleted' => 'Y',
        );
        $where = array('quotationRowId' => $request->getPost('rowId'));
        $this->db->table('quotation')->update($data, $where);
    }



	public function getDataLimit()
    {
        $builder = $this->db->table('quotation');
        $query = $builder->select('quotation.*, customers.customerName')
                             ->join('customers', 'customers.customerRowId = quotation.customerRowId')
                             ->limit(15)
                             ->orderBy('quotationRowId desc')
                             ->get();
        return($query->getResultArray());
    }
    public function getDataAll()
    {
        $builder = $this->db->table('quotation');
        $query = $builder->select('quotation.*, customers.customerName')
                             ->join('customers', 'customers.customerRowId = quotation.customerRowId')
                             ->orderBy('quotationRowId desc')
                             ->get();
        return($query->getResultArray());
    }

    public function showDetail($request)
    {
        $builder = $this->db->table('quotationdetail');
        $query = $builder->select('quotationdetail.quotationDetailRowId, quotationdetail.quotationRowId, quotationdetail.itemRowId, quotationdetail.qty, quotationdetail.rate, quotationdetail.amt, items.itemName, items.pp')
                             ->where('quotationRowId', $request->getPost('globalrowid')) 
                             ->join('items', 'items.itemRowId = quotationdetail.itemRowId')
                             ->orderBy('quotationDetailRowId')
                             ->get();
        return($query->getResultArray());
    }

    public function getProducts($invNo)
    {
        $builder = $this->db->table('quotationdetail');
        $query = $builder->select('quotationdetail.quotationDetailRowId, quotationdetail.quotationRowId, quotationdetail.itemRowId, quotationdetail.qty, quotationdetail.rate, quotationdetail.amt, items.itemName, quotation.totalAmount as grandTotal')
                             ->where('quotationdetail.quotationRowId', $invNo)
                             ->join('quotation', 'quotation.quotationRowId = quotationdetail.quotationRowId')
                             ->join('items', 'items.itemRowId = quotationdetail.itemRowId')
                             ->orderBy('quotationDetailRowId')
                             ->get();
        return($query->getResultArray());
        
    }
}