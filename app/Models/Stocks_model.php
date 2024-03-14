<?php namespace App\Models;
use CodeIgniter\Model;

class Stocks_model extends Model 
{
    protected $table = 'stocks';
    
    public function getCurrentStocks($request)
    {
        $query = $this->db->table('stockscurrent')
            ->select('stockscurrent.*, (stockscurrent.qty * stockscurrent.avgRate) AS value')
            ->where('investor', $request->getPost('investor'))
            ->orderBy('stockCurrentRowId desc')
            ->get();

        return $query->getResultArray(); // Return the result Array
    }

    public function getDataLimit($request)
    {
        $builder = $this->db->table('stocks');
        $query = $builder->select('stocks.*')
                            ->where('investor', $request->getPost('investor'))
                            ->limit(30)
                            ->orderBy('stockRowId desc')
                            ->get();
        return($query->getResultArray());
    }

    public function getStockList()
    {
        $builder = $this->db->table('stocks');
        $builder->select('stockName');
        $builder->distinct(); // Ensures only unique values are fetched
        $query = $builder->get();
        return($query->getResultArray());
    }

    public function insertNow($request)
    {
        $this->db->transStart();

        ///// INSERT in STOCKS table
        $dt = date('Y-m-d', strtotime($request->getPost('dt')));
        $builder = $this->db->table('stocks');
        $builder->selectMax('stockRowId');
        $query = $builder->get();
        $row = $query->getRow();
        $stockRowId = $row->stockRowId + 1;
        $data = array(
            'stockRowId' => $stockRowId
            , 'stockName' => ($request->getPost('stockName'))
            , 'dt' => date('Y-m-d', strtotime($request->getPost('dt')))         
            , 'buySell' => $request->getPost('mode')
            , 'qty' => $request->getPost('qty')
            , 'rate' => $request->getPost('rate')
            , 'charges' => $request->getPost('charges')
            , 'investor' => $request->getPost('investor')
        );
        $this->db->table('stocks')->insert($data);	

        ///// INSERT or UPDATE in STOCKSCURRENT table
        $builder = $this->db->table('stockscurrent');
        $query = $builder->select('stockCurrentRowId, stockName, qty, avgRate')
			                 ->where('investor', $request->getPost('investor'))
			                 ->where('stockName', $request->getPost('stockName'))
			                 ->limit(1)
			                 ->get();
		$row = $query->getRow();
		if (isset($row)) // Stock Record Found
		{
            $stockCurrentRowId = $row->stockCurrentRowId;
            $stockName = $row->stockName;
            $existingQty = $row->qty;
            $existingAvgRate = $row->avgRate;
            if($request->getPost('mode') == "B")
            {
                $newQty = $existingQty + $request->getPost('qty');
                $newAvgRate = ( ($existingAvgRate * $existingQty) + ($request->getPost('rate') * $request->getPost('qty')) ) / $newQty;
            }
            else
            {
                $newQty = $existingQty - $request->getPost('qty');
                $newAvgRate = $existingAvgRate;
            }
            $data = array(
                'qty' => $newQty        
                , 'avgRate' => $newAvgRate	        
            );
            $where = array('stockCurrentRowId' => $stockCurrentRowId, 'investor' => $request->getPost('investor'));
            $this->db->table('stockscurrent')->update($data, $where);	
        }
        else // New Stock
        {
            $builder = $this->db->table('stockscurrent');
            $builder->selectMax('stockCurrentRowId');
            $query = $builder->get();
            $row = $query->getRow();
            $stockCurrentRowId = $row->stockCurrentRowId + 1;
            $data = array(
                'stockCurrentRowId' => $stockCurrentRowId
                , 'stockName' => ($request->getPost('stockName'))
                , 'qty' => $request->getPost('qty')
                , 'avgRate' => $request->getPost('rate')
                , 'investor' => $request->getPost('investor')
        );
            $this->db->table('stockscurrent')->insert($data);
        }

        /// delete 0 now stocks
        $where = array('qty' => 0, 'investor' => $request->getPost('investor'));
        $this->db->table('stockscurrent')->delete($where); 
        	
        /// mark settle for 0 qty stocks
        $stockName = $request->getPost('stockName');
        $investor = $request->getPost('investor');

        // Subquery to calculate sum of quantities bought
            $subQueryBuy = $this->db->query("
                SELECT SUM(qty) AS sum_buy
                FROM stocks
                WHERE stockName = '$stockName' AND investor = '$investor' AND buySell = 'B'
            ")->getRow()->sum_buy;

            // Subquery to calculate sum of quantities sold
            $subQuerySell = $this->db->query("
                SELECT SUM(qty) AS sum_sell
                FROM stocks
                WHERE stockName = '$stockName' AND investor = '$investor' AND buySell = 'S'
            ")->getRow()->sum_sell;

            if (($subQueryBuy - $subQuerySell) == 0) {
                // Perform update if the difference between sum_buy and sum_sell is zero
                $data = ['sattled' => 'Y'];
                $this->db->table('stocks')->update($data, ['stockName' => $stockName, 'investor' => $investor]);
            }
        ///END - mark settle for 0 qty stocks

        
        $this->db->transComplete();
	}


    
    public function saveEditedStock($request)
    {
        $this->db->transStart();
         
        $data = array(
	        'dt' => $request->getPost('dt')
	        , 'charges' => $request->getPost('charges')
	        , 'sattled' => $request->getPost('sattled')
		);
		$where = array('stockRowId' => $request->getPost('stockRowId'));
		$this->db->table('stocks')->update($data, $where);

        $this->db->transComplete();
    }

    public function getStockLedger($request)
    {
        $builder = $this->db->table('stocks');
        $query = $builder->select('stocks.*')
                             ->where('sattled', 'N')
                             ->where('investor', $request->getPost('investor'))
                             ->where('stockName', $request->getPost('stockName'))
                             ->orderBy('dt desc, stockRowId desc')
                             ->get();
        return($query->getResultArray());
    }

    public function loadAllOfThisStock($request)
    {
        $builder = $this->db->table('stocks');
        $query = $builder->select('stocks.*')
                             ->where('investor', $request->getPost('investor'))
                             ->where('stockName', $request->getPost('stockName'))
                             ->orderBy('dt desc, stockRowId desc')
                             ->get();
        return($query->getResultArray());
    }
    
    public function getProfitOfSattled($request)
    {
        $investor = $request->getPost('investor');
        $subQueryBuy = $this->db->query("
            SELECT SUM(qty * rate + charges) AS sum_buy
            FROM stocks
            WHERE investor = '$investor' AND buySell = 'B' AND sattled = 'Y'
        ")->getRow()->sum_buy;

        // Subquery to calculate sum of quantities sold
        $subQuerySell = $this->db->query("
            SELECT SUM(qty * rate - charges) AS sum_sell
            FROM stocks
            WHERE investor = '$investor' AND buySell = 'S' AND sattled = 'Y'
        ")->getRow()->sum_sell;

        return round(($subQuerySell - $subQueryBuy), 0); 
        // $builder = $this->db->table('stocks');
        // $query = $builder->select('stocks.*')
        //                      ->where('investor', $request->getPost('investor'))
        //                      ->orderBy('stockRowId desc')
        //                      ->get();
        // return($query->getResultArray());
    }

    public function loadAllRecords($request)
    {
        $builder = $this->db->table('stocks');
        $query = $builder->select('stocks.*')
                             ->where('investor', $request->getPost('investor'))
                             ->orderBy('stockRowId desc')
                             ->get();
        return($query->getResultArray());
    }

    public function editCurrentStocks($request)
    {
        $builder = $this->db->table('stockscurrent');
        $query = $builder->select('stockscurrent.*')
                             ->where('investor', $request->getPost('investor'))
                             ->orderBy('stockCurrentRowId desc')
                             ->get();
        return($query->getResultArray());
    }

    
    public function saveEditedCurrentStock($request)
    {
        $this->db->transStart();
         
        $data = array(
	        'qty' => $request->getPost('qty')
            , 'avgRate' => $request->getPost('avgRate')
		);
		$where = array('stockCurrentRowId' => $request->getPost('stockCurrentRowId'));
		$this->db->table('stockscurrent')->update($data, $where);

        $this->db->transComplete();
    }
}