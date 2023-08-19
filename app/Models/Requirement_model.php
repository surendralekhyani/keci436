<?php namespace App\Models;
use CodeIgniter\Model;

class Requirement_model extends Model 
{
    var $table = 'items';

    public function getItemList()
    {
        $builder = $this->db->table('items');
        $query = $builder->select('items.*')
                             ->where('deleted', 'N')
                             ->orderBy('itemName')
                             ->get();
        return($query->getResultArray());
    }   

    public function getDataLimit()
	{
        $builder = $this->db->table('requirement');
        $query = $builder->select('*')
                             ->orderBy('rowId')
                             ->get();
        return($query->getResultArray());
	}


	public function insertNow($request)
    {
        $this->db->transStart();
        //// ItemGroupName
        $builder = $this->db->table('itemgroups');
        $query = $builder->select('itemgroups.itemGroupName')
                             ->join('items', 'items.itemGroupRowId = itemgroups.itemGroupRowId')
                             ->where('items.itemRowId', $request->getPost('itemRowId'))
                             ->get();
        $row = $query->getLastRow('array');
        $itemGroupName="undef";
        if ($query->getNumRows() > 0)
        {
            $itemGroupName = " ~ " . $row['itemGroupName'];
        }
        //// Last min purchase rate
        $builder = $this->db->table('purchasedetail');
        $query = $builder->select('purchasedetail.*')
                             ->where('purchasedetail.itemRowId', $request->getPost('itemRowId'))
                             ->join('items', 'items.itemRowId = purchasedetail.itemRowId')
                             ->orderBy('purchaseRowId')
                             ->get();
        $row = $query->getLastRow('array');
        $lastRate=0;
        $purchaseDt="01-01-1111";
        $purchaseFrom="NA";
        if ($query->getNumRows() > 0)
        {
            if($row['qty'] == 0)
            {
                $lastRate = 0;
            }
            else
            {
                $lastRate = ($row['netAmt']) /  $row['qty'];
            }
            $purchaseRowId = $row['purchaseRowId'];
            ///////Purchase Dt n Supp. name
            $builder = $this->db->table('purchase');
            $query = $builder->select('purchaseDt, customerName')
                                 ->where('purchaseRowId', $purchaseRowId)
                                 ->join('customers', 'customers.customerRowId = purchase.customerRowId')
                                 ->get();
            $row = $query->getRowArray();
            $purchaseDt = $row['purchaseDt'];
            $purchaseFrom = $row['customerName'];
            /////// END - Purchase Dt n Supp. name
        }
        //////////////////////
        //// Avg Annual Qty
        $builder = $this->db->table('purchasedetail');
        $query = $builder->selectSum('purchasedetail.qty')
                             ->where('purchasedetail.itemRowId', $request->getPost('itemRowId'))
                            //  ->join('items', 'items.itemRowId = purchasedetail.itemRowId')
                            //  ->orderBy('purchaseRowId')
                             ->get();
        $row = $query->getRow();
        $avgAnnualQty = 0;
        if ($row->qty > 0)
        {
            $totalPurchaseQtyTillDate = $row->qty;
            $query = $builder->selectMin('purchase.purchaseDt', 'firstDt')
                                ->selectMax('purchase.purchaseDt', 'lastDt')
                                ->where('purchasedetail.itemRowId', $request->getPost('itemRowId'))
                                ->join('purchase', 'purchase.purchaseRowId = purchasedetail.purchaseRowId')
                                //  ->orderBy('purchaseRowId')
                                ->get();
            $row = $query->getRow();
            $firstPurchaseDate = $row->firstDt;
            $lastPurchaseDate = $row->lastDt;

            $daysDiff = strtotime($lastPurchaseDate) - strtotime($firstPurchaseDate);
            $daysDiff = round($daysDiff / (60 * 60 * 24));
            $noOfYears = $daysDiff / 365;
            if( $noOfYears > 0)
            {
                $avgAnnualQty = round($totalPurchaseQtyTillDate / $noOfYears);
            }
        }
        

        $builder = $this->db->table('requirement');
        $builder->selectMax('rowId');
        $query = $builder->get();
        $row = $query->getRow();
        $current_row = $row->rowId + 1;
		$data = array(
	        'rowId' => $current_row
	        , 'itemRowId' => $request->getPost('itemRowId')
            , 'itemName' => ucwords($request->getPost('itemName')) 
            , 'qty' => ($request->getPost('qty'))
	        , 'remarks' => ($request->getPost('remarks') . $itemGroupName)
	        , 'lastPurchasePrice' => (float)$lastRate
	        , 'lastPurchaseDate' => $purchaseDt
	        , 'lastPurchaseFrom' => $purchaseFrom . ' ---- Avg. Annual Qty: ' . $avgAnnualQty //. ' dt1 ' . $firstPurchaseDate . ' dt2 ' . $lastPurchaseDate . ' tqty ' . $totalPurchaseQtyTillDate
	        , 'createdBy' => session('userRowId')
            , 'createdStamp' => date('Y-m-d H:i')
		);
        $this->db->table('requirement')->insert($data);
        $this->db->transComplete();	
	}


	public function deleteNow($request)
	{
        $this->db->transStart();
        $where = array('rowId' => $request->getPost('rowId'));
        $this->db->table('requirement')->delete($where); 
        $this->db->transComplete(); 
	}

    public function deleteChecked($request)
    {
        $this->db->transStart();
        $TableData = $request->getPost('TableData');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);

        for ($i=0; $i < $myTableRows; $i++) 
        {
            $where = array('rowId' => $TableData[$i]['rowId']);
            $this->db->table('requirement')->delete($where);
        }
        $this->db->transComplete(); 
    }

    public function getPurchaseLog($request)
    {
        $builder = $this->db->table('purchasedetail');
        $query = $builder->select('purchasedetail.purchaseDetailRowId, purchasedetail.purchaseRowId, purchasedetail.itemRowId, purchasedetail.qty, purchasedetail.rate, purchasedetail.amt, purchasedetail.discountPer, purchasedetail.discountAmt, purchasedetail.pretaxAmt, purchasedetail.igst, purchasedetail.igstAmt, purchasedetail.cgst, purchasedetail.cgstAmt, purchasedetail.sgst, purchasedetail.sgstAmt, purchasedetail.netAmt, purchasedetail.sellingPricePer, purchasedetail.sp, purchasedetail.freight, purchasedetail.itemRemarks, items.itemName, customers.customerName, purchase.purchaseDt')
                             ->where('purchasedetail.itemRowId', $request->getPost('itemRowId'))
                             ->where('purchase.deleted', 'N')
                             ->join('purchase','purchase.purchaseRowId = purchasedetail.purchaseRowId')
                             ->join('items', 'items.itemRowId = purchasedetail.itemRowId')
                             ->join('customers','customers.customerRowId = purchase.customerRowId')
                             // ->orderBy('purchase.purchaseDt, purchase.purchaseRowId')
                             ->get();
        return($query->getResultArray());

     // $this->db->select('purchasedetail.purchaseDetailRowId, purchasedetail.purchaseRowId, purchasedetail.itemRowId, purchasedetail.qty, purchasedetail.rate, purchasedetail.amt, purchasedetail.discountPer, purchasedetail.discountAmt, purchasedetail.pretaxAmt, purchasedetail.igst, purchasedetail.igstAmt, purchasedetail.cgst, purchasedetail.cgstAmt, purchasedetail.sgst, purchasedetail.sgstAmt, purchasedetail.netAmt, purchasedetail.sellingPricePer, purchasedetail.sp, purchasedetail.freight, purchasedetail.itemRemarks, items.itemName, customers.customerName, purchase.purchaseDt');
     // $this->db->from('purchasedetail');
     // $this->db->join('purchase','purchase.purchaseRowId = purchasedetail.purchaseRowId');
     // $this->db->join('customers','customers.customerRowId = purchase.customerRowId');
     // $this->db->where('purchase.deleted', 'N');
     // $this->db->where('purchasedetail.itemRowId', $this->input->post('itemRowId'));
     // $this->db->order_by('purchase.purchaseDt, purchaseRowId');
     // $query = $this->db->get();
     // return($query->result_array());
    }
}