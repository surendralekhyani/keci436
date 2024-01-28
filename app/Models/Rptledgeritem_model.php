<?php namespace App\Models;
use CodeIgniter\Model;

class Rptledgeritem_model extends Model 
{
    var $table = 'items';


    public function getItemList()
    {
      $builder = $this->db->table($this->table);
      $query = $builder->select('items.*')
                           ->where('deleted', 'N')
                           ->orderBy('itemName')
                           ->get();
      return($query->getResultArray());
        // $arr = array();
        // $arr["-1"] = '--- Select ---';
        // foreach ($query->getResultArray() as $row)
        // {
        //     $arr[$row['itemRowId']]= $row['itemName'];
        // }

        // return $arr;
    }

    public function getItemName($request)
    {
      $builder = $this->db->table($this->table);
      $query = $builder->select('items.itemName')
                           ->where('itemRowId', $request->getPost('itemRowId'))
                           ->get();
      return($query->getResultArray());
    }
    public function getOpeningBal($request)
    {
         $opBal = 0;
         $builder = $this->db->table($this->table);
         $query = $builder->selectSum('openingBalance')
                           ->where('items.itemRowId', $request->getPost('itemRowId'))
                           ->get();
         if ($query->getNumRows() > 0)
         {
            $row = $query->getRowArray();
            $opBal = $row['openingBalance'];
         }

   //   return $opBal;
     ///Purchase During this periaod
     $purchaseQty = 0;
      $builder = $this->db->table('purchasedetail');
      $query = $builder->selectSum('qty')
                        ->where('purchasedetail.itemRowId', $request->getPost('itemRowId'))
                        ->where('purchase.purchaseDt <', date('Y-m-d', strtotime($request->getPost('dtFrom'))))
                        ->where('purchase.deleted', 'N')
                        ->join('purchase','purchase.purchaseRowId = purchasedetail.purchaseRowId')
                        ->get();
      if ($query->getNumRows() > 0)
      {
        $row = $query->getRowArray();
        $purchaseQty = $row['qty'];
      }

   //   ///Sale During this periaod (Invoice)
     $saleQty = 0;
      $builder = $this->db->table('dbdetail');
      $query = $builder->selectSum('qty')
                        ->where('dbdetail.itemRowId', $request->getPost('itemRowId'))
                        ->where('db.dbDt <', date('Y-m-d', strtotime($request->getPost('dtFrom'))))
                        ->where('db.deleted', 'N')
                        ->join('db','db.dbRowId = dbdetail.dbRowId')
                        ->get();
      if ($query->getNumRows() > 0)
      {
        $row = $query->getRowArray();
        $saleQty = $row['qty'];
     }


     $finalOpeningBalance = $opBal + $purchaseQty - $saleQty ;
   //   $finalOpeningBalance = $opBal + $purchaseQty - $saleQty - $cashSaleQty;

     return $finalOpeningBalance;

    }

    public function getPurchase($request)
    {
      $builder = $this->db->table('purchasedetail');
      $query = $builder->select('purchasedetail.*, customers.customerName, purchase.purchaseDt')
                        ->where('purchasedetail.itemRowId', $request->getPost('itemRowId'))
                        ->where('purchase.deleted', 'N')
                        ->where('purchase.purchaseDt <=', date('Y-m-d', strtotime($request->getPost('dtTo'))))
                        ->where('purchase.purchaseDt >=', date('Y-m-d', strtotime($request->getPost('dtFrom'))))
                        ->join('purchase','purchase.purchaseRowId = purchasedetail.purchaseRowId')
                        ->join('customers','customers.customerRowId = purchase.customerRowId')
                        ->orderBy('purchase.purchaseDt, purchaseRowId')
                        ->get();
      

     return($query->getResultArray());
    }

    public function getSale($request)
    {
   //  $this->db->select('dbdetail.*, customers.customerName, db.dbDt');
   //   $this->db->from('dbdetail');
   //   $this->db->join('db','db.dbRowId = dbdetail.dbRowId');
   //   $this->db->join('customers','customers.customerRowId = db.customerRowId');
   //   $this->db->where('db.deleted', 'N');
   //   $this->db->where('dbdetail.itemRowId', $this->input->post('itemRowId'));
   //   $this->db->where('db.dbDt <=', date('Y-m-d', strtotime($this->input->post('dtTo'))));
   //   $this->db->where('db.dbDt >=', date('Y-m-d', strtotime($this->input->post('dtFrom'))));
   //   $this->db->order_by('db.dbDt, dbRowId');
   //   $query = $this->db->get();
   //   return($query->result_array());

     $builder = $this->db->table('dbdetail');
      $query = $builder->select('dbdetail.*, customers.customerName, db.dbDt')
                        ->where('dbdetail.itemRowId', $request->getPost('itemRowId'))
                        ->where('db.deleted', 'N')
                        ->where('db.dbDt <=', date('Y-m-d', strtotime($request->getPost('dtTo'))))
                        ->where('db.dbDt >=', date('Y-m-d', strtotime($request->getPost('dtFrom'))))
                        ->join('db','db.dbRowId = dbdetail.dbRowId')
                        ->join('customers','customers.customerRowId = db.customerRowId')
                        ->orderBy('db.dbDt, dbRowId')
                        ->get();
      

     return($query->getResultArray());
    }

   //  public function getCashSale()
   //  {
   //   $this->db->select('cashsale.*');
   //   $this->db->from('cashsale');
   //   $this->db->where('cashsale.itemRowId', $this->input->post('itemRowId'));
   //   $this->db->where('cashsale.dt <=', date('Y-m-d', strtotime($this->input->post('dtTo'))));
   //   $this->db->where('cashsale.dt >=', date('Y-m-d', strtotime($this->input->post('dtFrom'))));
   //   $this->db->order_by('cashsale.dt, cashSaleRowId');
   //   $query = $this->db->get();
   //   return($query->result_array());
   //  }
}