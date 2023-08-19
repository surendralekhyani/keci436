----------------------------------------PhpMyAdmin settings to import large file
Add this line to xampp\phpmyadmin\config.inc.php

$cfg['ExecTimeLimit'] = 6000;
And Change xampp\php\php.ini to

post_max_size = 750M 
upload_max_filesize = 750M   
max_execution_time = 5000
max_input_time = 5000
memory_limit = 1000M
---------------------------------------------------
----Writing in text file
$myfile = fopen("masala/tmpi.txt", "w") or die("Unable to open file!");
$txt = "Hello";
$txt .= " - " . "Hello";
fwrite($myfile, $txt);
fclose($myfile);

---- agar log file m likhna ho to pahle CONFIG.PHP m ye line set karo:
    $config['log_threshold'] = 1;

    ab kisi b model m ye line likh do:
        log_message('info', "DDDD");
    



------ Merging / Appending rows from different Tables in Rows Array (Like Union) eg. Prod System > Stock Ledger
////// FETCHING product List
        $this->db->select('prod_products.productRowId, prod_brands.brandName, prod_shapes.shapeName, prod_items.itemName, prod_colours.colourName, prod_designs.designName');
        if( $this->input->post('productName') != "ALL" )
        {
            $this->db->where('prod_products.productRowId', $this->input->post('productRowId'));
        }
        $this->db->join('prod_brands','prod_brands.brandRowId = prod_products.brandRowId');
        $this->db->join('prod_shapes','prod_shapes.shapeRowId = prod_products.shapeRowId');
        $this->db->join('prod_items','prod_items.itemRowId = prod_products.itemRowId');
        $this->db->join('prod_colours','prod_colours.colourRowId = prod_products.colourRowId');
        $this->db->join('prod_designs','prod_designs.designRowId = prod_products.designRowId');
        $this->db->order_by('brandName, shapeName, itemName, colourName, designName');
        $query = $this->db->get('prod_products');
        $rows = array();
        foreach ($query->result_array() as $row)
        {
            /////////// Data FROM 1st TABLE (Op. Balance of this Product)
            $this->db->select_Sum('qtyIn');
            $this->db->select_Sum('qtyOut');
            $this->db->select('prod_productledger.productRowId, prod_brands.brandName, prod_shapes.shapeName, prod_items.itemName, prod_colours.colourName, prod_designs.designName, " " as dt, vType, "S1" as sn');
            $this->db->from('prod_productledger');
            $this->db->where('prod_productledger.productRowId', $row['productRowId']);
            $this->db->where('prod_productledger.dt <', date('Y-m-d', strtotime($this->input->post('dtFrom'))));
            $this->db->join('prod_products','prod_products.productRowId = prod_productledger.productRowId');
            $this->db->join('prod_brands','prod_brands.brandRowId = prod_products.brandRowId');
            $this->db->join('prod_shapes','prod_shapes.shapeRowId = prod_products.shapeRowId');
            $this->db->join('prod_items','prod_items.itemRowId = prod_products.itemRowId');
            $this->db->join('prod_colours','prod_colours.colourRowId = prod_products.colourRowId');
            $this->db->join('prod_designs','prod_designs.designRowId = prod_products.designRowId');
            $this->db->group_by('prod_productledger.productRowId');
            $queryOPBal = $this->db->get();
            if ($queryOPBal->num_rows() == 0)        //// agar sum nahi aaya to 1 row create kr rahe h 
            {
                $rowOpBal["qtyIn"] = '0';
                $rowOpBal["qtyOut"] = '0';
                $rowOpBal["productRowId"] = $row['productRowId'];
                $rowOpBal["brandName"] = $row['brandName'];
                $rowOpBal["shapeName"] = $row['shapeName'];
                $rowOpBal["itemName"] = $row['itemName'];
                $rowOpBal["colourName"] = $row['colourName'];
                $rowOpBal["designName"] = $row['designName'];
                $rowOpBal["dt"] = ' ';
                $rowOpBal["vType"] = 'Op.Bal.';
                $rowOpBal["sn"] = 'S1';

                $rows[] = $rowOpBal;
            }
            foreach ($queryOPBal->result_array() as $rowOpBal)  /// ye loop 1 bar hi chalega kyuk sum aayega
            {
                $rows[] = $rowOpBal;              
            }

        /////////// Data FROM 2nd TABLE (data Within date Range of this product)
            $this->db->select('prod_productledger.qtyIn, prod_productledger.qtyOut, prod_productledger.productRowId, prod_brands.brandName, prod_shapes.shapeName, prod_items.itemName, prod_colours.colourName, prod_designs.designName, dt, vType, "S2" as sn');
            $this->db->from('prod_productledger');
            $this->db->where('prod_productledger.productRowId', $row['productRowId']);
            $this->db->where('prod_productledger.dt >=', date('Y-m-d', strtotime($this->input->post('dtFrom'))));
            $this->db->where('prod_productledger.dt <=', date('Y-m-d', strtotime($this->input->post('dtTo'))));
            $this->db->join('prod_products','prod_products.productRowId = prod_productledger.productRowId');
            $this->db->join('prod_brands','prod_brands.brandRowId = prod_products.brandRowId');
            $this->db->join('prod_shapes','prod_shapes.shapeRowId = prod_products.shapeRowId');
            $this->db->join('prod_items','prod_items.itemRowId = prod_products.itemRowId');
            $this->db->join('prod_colours','prod_colours.colourRowId = prod_products.colourRowId');
            $this->db->join('prod_designs','prod_designs.designRowId = prod_products.designRowId');
            $this->db->order_by('prod_productledger.productRowId, prod_productledger.dt, prod_productledger.rowId');
            $queryRange = $this->db->get();
            foreach ($queryRange->result_array() as $rowRange)
            {
                $rows[] = $rowRange;                 //// is table ki sari rows as it is array m...
            }
        }
        return $rows;


---------------------------------UNION :
public function getDetail()
{
	$this->db->select('acclusters.*, acgroups.acgname as name, "G" as accountorgroup');
	$this->db->where('acclusterrowid', $this->input->post('clusterrowid'));
	$this->db->where('accountorgroup', 'G');
	$this->db->from('acclusters');
	$this->db->join('acgroups','acgroups.acgrowid = acclusters.refrowid');
	$this->db->order_by('acgname');
	$query1 = $this->db->get()->result();

	$this->db->select('acclusters.*, vacnames.acname as name, "A" as accountorgroup');
	$this->db->where('acclusterrowid', $this->input->post('clusterrowid'));
	$this->db->where('accountorgroup', 'A');
	$this->db->from('acclusters');
	$this->db->join('vacnames','vacnames.acrowid = acclusters.refrowid');
	$this->db->order_by('acname');
	$query2 = $this->db->get()->result();

	$query = array_merge($query1, $query2);
	return $query;
}



-------------------------------------------- Time taken / Took for operation (Server process time) (controller code)--RPTclearance
        $timeStart = microtime(TRUE);

        $data['records'] = $this->Rptclearance_model->showData();
        $data['totalWt'] = $this->Rptclearance_model->showDataTotalWt();

        $timeEnd = microtime(TRUE);
        $data['timeTook'] = round( ($timeEnd - $timeStart), 2 ) ;
        
        echo json_encode($data);