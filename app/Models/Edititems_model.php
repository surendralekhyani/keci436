<?php namespace App\Models;
use CodeIgniter\Model;

class Edititems_model extends Model 
{
    var $table = 'items';
    
    public function getDataForReport($request)
    {
        $builder = $this->db->table($this->table);
        if($request->getPost('searchValue') == "" || strtoupper($request->getPost('searchValue')) == "ALL")
        {
            $query = $builder->select('items.*, itemgroups.itemGroupName')
                                ->where('items.deleted', 'N')
                                ->join('itemgroups', 'itemgroups.itemGroupRowId = items.itemGroupRowId')
                                ->orderBy('items.itemName')
                                ->get();   
        }
        else
        {
            $search = $request->getPost('searchValue');  
            $search = explode( ' ', $search );

            $sql = 'Select items.*, itemgroups.itemGroupName from items,itemgroups  where items.itemGroupRowId=itemgroups.itemGroupRowId AND items.deleted="N"';
            foreach($search as $text)
            {
                    $sql = $sql." AND items.itemName LIKE '%$text%'";
            }
            $sql .= "ORDER BY items.itemName";
            $query = $this->db->query($sql);
            // dd($sql);
        }
            
        return($query->getResultArray());
    }

       
    public function getDataForReportWithDt($request)
    {
        $builder = $this->db->table($this->table);
        if($request->getPost('searchValue') == "" || strtoupper($request->getPost('searchValue')) == "ALL")
        {
            $query = $builder->select('items.itemRowId, items.itemName, items.sellingPrice, items.pp, items.hsn, items.gstRate, items.openingBalance, itemgroups.itemGroupName')
                                ->where('items.deleted', 'N')
                                ->join('itemgroups', 'itemgroups.itemGroupRowId = items.itemGroupRowId')
                                ->orderBy('items.itemName')
                                ->get();   
        }
        else
        {
            $search = $request->getPost('searchValue');  
            $search = explode( ' ', $search );

            $sql = 'Select items.itemRowId, items.itemName, items.sellingPrice, items.pp, items.hsn, items.gstRate, items.openingBalance, itemgroups.itemGroupName from items,itemgroups  where items.itemGroupRowId=itemgroups.itemGroupRowId AND items.deleted="N"';
            foreach($search as $text)
            {
                    $sql = $sql." AND items.itemName LIKE '%$text%'";
            }
            $sql .= "ORDER BY items.itemName";
            $query = $this->db->query($sql);   
        }
        $rows = array();
        foreach($query->getResultArray() as $row)
        {
            $row["pvDt"] = "";
            $row["svDt"] = "";
            $builder = $this->db->table('purchase');
            $queryInner = $builder->select('purchaseDt')
                                ->join('purchasedetail', 'purchasedetail.purchaseRowId = purchase.purchaseRowId')
                                ->where('itemRowId', $row['itemRowId'])
                                ->orderBy('purchaseDt desc')
                                ->limit(1)
                                ->get();
            $innerRow = $queryInner->getRowArray();
            if (isset($innerRow))
		    {
                $row["pvDt"] = $innerRow['purchaseDt'];
            }

            ///sv
            $builder = $this->db->table('db');
            $queryInner = $builder->select('dbDt')
                                ->join('dbdetail', 'dbdetail.dbRowId = db.dbRowId')
                                ->where('itemRowId', $row['itemRowId'])
                                ->orderBy('dbDt desc')
                                ->limit(1)
                                ->get();
            $innerRow = $queryInner->getRowArray();
            if (isset($innerRow))
		    {
                $row["svDt"] = $innerRow['dbDt'];
            }




            $rows[] = $row;         //// adding updated row to array
        }

        return $rows;
    }

    public function getClosingBalance($request)
    {
        $builder = $this->db->table($this->table);
        $string = $request->getPost('TableData');
        $str_arr = explode (",", $string); 
        // $ids = [2414, 2593];
        $query = $builder->select('itemRowId, openingBalance')
                        ->whereIn('itemRowId', $str_arr)
                        ->orderBy('itemName')
                        ->get();   
        $rowsOpening = $query->getResultArray();
        // return $rows;
        ////// Purchase Data
        $builder = $this->db->table('purchasedetail');
        $query = $builder->selectSum('qty')
                        ->select('itemRowId')
                        ->whereIn('itemRowId', $str_arr)
                        ->where('purchase.deleted', 'N')
                        ->join('purchase','purchase.purchaseRowId = purchasedetail.purchaseRowId')
                        ->groupBy('itemRowId')
                        ->get();
        $rowsPurchase = $query->getResultArray();
        // return $rowsPurchase;

        ////// Sale Data
        $builder = $this->db->table('dbdetail');
        $query = $builder->selectSum('qty')
                        ->select('itemRowId')
                        ->whereIn('itemRowId', $str_arr)
                        ->where('db.deleted', 'N')
                        ->join('db','db.dbRowId = dbdetail.dbRowId')
                        ->groupBy('itemRowId')
                        ->get();
        $rowsSale = $query->getResultArray();
        // return $rowsSale;
        $rows = array();
        foreach($rowsOpening as $row)
        {
            // $key=-1;
            $row["purchaseQty"] = 0;
            $key = array_search($row['itemRowId'], array_column($rowsPurchase, 'itemRowId'), true);
            // $row["key"] = $key;
            
            if ($key !== false)         
            {
                $row["purchaseQty"] = $rowsPurchase[$key]['qty'];
            }

            $row["saleQty"] = 0;
            $key = array_search($row['itemRowId'], array_column($rowsSale, 'itemRowId'));
            if ($key !== false)         
            {
                $row["saleQty"] = $rowsSale[$key]['qty'];
            }

            $row["closingQty"] = $row["openingBalance"] + $row["purchaseQty"] - $row["saleQty"];


            $rows[] = $row;         //// adding updated row to array
        }

        return $rows;
    }

    public function getDataForReportDeleted()
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('*')
                                ->where('deleted', 'Y')
                                ->orderBy('itemName')
                                ->get();   
        return($query->getResultArray());
    }

    public function insertNow($request)
    {
        $this->db->transStart();

        $TableData = $request->getPost('TableData');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);

        for ($i=0; $i < $myTableRows; $i++) 
        {
            $data = array(
                'itemName' => $TableData[$i]['itemName']
                , 'sellingPrice' => (float)$TableData[$i]['sellingPrice']
                , 'pp' => (float)$TableData[$i]['pp']
                , 'gstRate' => (float)$TableData[$i]['gstRate']
                , 'hsn' => $TableData[$i]['hsn']
                , 'openingBalance' => $TableData[$i]['openingBalance']
            );
            $where = array('itemRowId' => $TableData[$i]['itemRowId']);
            $this->db->table($this->table)->update($data, $where);
        }
        $this->db->transComplete();
    }

    public function deleteNow($request)
    {
        $TableData = $request->getPost('TableDataDelete');
        // d($_SERVER);
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);
        for ($i=0; $i < $myTableRows; $i++) 
        {
            $data = array(
                    'deleted' => 'Y'
                    , 'deletedBy' => session('userRowId')
                    , 'deletedStamp' => date('Y-m-d H:i')
            );
            $where = array('itemRowId' => $TableData[$i]['itemRowId']);
            $this->db->table($this->table)->update($data, $where);
        }

        // $this->db->where('itemRowId', $this->input->post('rowId'));
        // $this->db->delete('items');
    }
    public function undelete($request)
    {
        $TableData = $request->getPost('TableData');
        $TableData = stripcslashes($TableData);
        $TableData = json_decode($TableData,TRUE);
        $myTableRows = count($TableData);
        for ($i=0; $i < $myTableRows; $i++) 
        {
            $data = array(
                    'deleted' => 'N',
                    'deletedBy' => session('userRowId')

            );
            $where = array('itemRowId' => $TableData[$i]['itemRowId']);
            $this->db->table($this->table)->update($data, $where);
        }
    }
}