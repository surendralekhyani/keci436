<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Quotation_model;

class Quotation_Controller extends BaseController
{
	public function index()
	{
		$model = new Quotation_model();
		$data['customers'] = $model->getCustomers();
		$data['items'] = $model->getItems();
		// dd($data['items']);
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Quoatation_view', $data);
		echo view('footer');
	}  

	
	public function insert()
	{
		if( trim($this->request->getPost('customerRowId')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Quotation_model();
		$quotationRowId = $model->insertNow($this->request);
		$this->printNow('Save', $quotationRowId);
	}


	public function showDetailOnUpdate()
	{
		$model = new Quotation_model();
		$data['records'] = $model->showDetail($this->request);
		$data['customerInfo'] = $model->getCustomerInfo($this->request);
		echo json_encode($data);
	}

	public function checkForUpdate()
	{
		$model = new Quotation_model();
		if($model->checkForUpdate($this->request) == 1)
        {
        	$data = "cant";
        	echo json_encode($data);
        }
	}
	
	public function update()
	{
		if( trim($this->request->getPost('customerRowId')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Quotation_model();
		$model->updateNow($this->request);
		$this->printNow('Update');
		// $this->printToPdf('Update');
	}

	public function delete()
	{
		$model = new Quotation_model();
		if($model->checkPossibility() == 1)
        {
        	$data = "yes";
        	echo json_encode($data);
        }
        else
        {
			$model->deleteNow($this->request);
			$data['records'] = $model->getDataLimit();
			echo json_encode($data);
		}
	}

	public function loadAllRecords()
	{
		$model = new Quotation_model();
		$model->deleteNow($this->request);
		$data['records'] = $model->getDataAll();
		echo json_encode($data);
	}


	public function printNow($arg, $quotationRowId=0)
	{
		$model = new Quotation_model();
		$data['records'] = $model->getDataLimit();
		$rowId = -10;
		if($arg == "Update")
		{
			$rowId = $this->request->getPost('globalrowid');
		}
		else
		{
			$rowId = $quotationRowId;
		}
		

		$data['org'] = $this->modelUtil->getOrg();
		$orgName = $data['org'][0]['orgName'];
		$orgAddress1 = $data['org'][0]['add1'];
		$orgAddress2 = $data['org'][0]['add2'];
		$orgAddress3 = $data['org'][0]['add3'];
		$orgAddress4 = $data['org'][0]['add4'];

		$html='<table border="0" id="tblMain"><thead><tr><td>
				    <div class="header-space">&nbsp;</div>
				  </td></tr></thead><tbody><tr><td>';
		
		$html .='<table border="0" id="tblHeader">
					<tr>
						<td id="orgName">'. $orgName .'</td>
					</tr>
					<tr>
						<td class="normal">'. $orgAddress1 .'</td>
					</tr>
					<tr>
						<td class="normal">'. $orgAddress2 .'</td>
					</tr>
					<tr>
						<td class="normal">'. $orgAddress3 .'</td>
					</tr>
			</table>';
		$html .= "<p align='center' id='billOfSupply'>Quotation</p>";
		
		$data['custInfo'] = $model->getCustInfo($rowId);

		$html .= '<table id="tblCustInfo" border=0>
					<tr>
						<td class="tdFirstColOfTblCustInfo" align="left">Name: <span id="custName">'.$data['custInfo'][0]['customerName'].'</span></td>
						<td class="tdSecondColOfTblCustInfo" align="right">Date: '. date('d-M-Y', strtotime($data['custInfo'][0]['quotationDt'] )).'</td>
					</tr>
					<tr>
						<td id="tdCustAddress" class="tdFirstColOfTblCustInfo" align="left">Address: '.$data['custInfo'][0]['address'].'</td>
						<td class="tdSecondColOfTblCustInfo" align="right">No.: '. str_pad($rowId, 5, '0', STR_PAD_LEFT) . '</td>
					</tr>
				</table>';

		//////////// Items table
		$data['products'] = $model->getProducts($rowId);
		// echo json_encode($data['products']);
		$sn=1;
		$itemRows ="";
		foreach ($data['products'] as $row) {
			$itemName = $row['itemName'];
			$itemRows .= "<tr>";
				$itemRows .= "<td class='clsProductsSn'>". $sn++ ."</td>";
				$itemRows .= "<td class='clsProductsDescription'>". $itemName . "</td>";
				$itemRows .= "<td class='clsProductsQty'>". number_format((float)$row['qty'], 2) ."</td>";
				$itemRows .= "<td class='clsProductsRate'>". number_format((float)$row['rate'], 2) ."</td>";
				$itemRows .= "<td class='clsProductsAmt'>". number_format((float)$row['amt'], 2) ."</td>";
			$itemRows .= "</tr>";
		}

		$html .= '<table id="tblProducts">
					<tr>
						<th id="thSn" class="clsProductsSn">#</th>
						<th id="thDescription" class="clsProductsDescription">Description</th>
						<th id="thQty" class="clsProductsQty">Qty.</th>
						<th id="thRate" class="clsProductsRate">Rate</th>
						<th id="thAmt" class="clsProductsAmt">Amt.</th>
					</tr>'. $itemRows .
			'</table>';


		$html .= '<p>.......</p>';

		$col3 = $data['products'][0]['grandTotal'];

		$col1 = '[ '. $this->numberTowords($col3) .' ]'. '<br>';
		$col2 = "Net Amt.:";
		$html .= '<table border="0" id="tblNetAmt">
					<tr>
						<td class="tdTblNetAmtOne">'. $col1 .'</td>
						<td id="tdTblNetAmtTwo" class="normal">'. $col2 .'</td>
						<td id="tdTblNetAmtThree">'. $col3 .'</td>
					</tr>
					<tr>
						<td class="tdTblNetAmtOne">Not eligible to collect tax on supplies (sale under 20 lakhs).</td>
						<td class="normal"></td>
						<td class="normal"></td>
					</tr>
			</table>';

		$col1 = "";
		$col1 .= "Terms & Conditions:" . '<br>';
		$col1 .="<ol id='termsList'><li>The discrepancy if any in the bill should be brought to our notice within a week from the date here of.</li><li>All disputes will have to be settled at Ajmer.</li><li>Goods supplied will not be refunded.</li><li>Service within warranty period will be provided by manufacturer.</li><li>E. & O.E.</li>";
		

		$col2 = "";
		$col2 .= "For: " . $orgName . '<br>' . '<br>' . '<br>' . '<br>';
		$col2 .= "Authorised Signatory";
		$html .= '<table border="0" id="tblTerms">
			<tr>
				<td id="tdTblTermsOne">'. $col1 .'</td>
				<td id="tdTblTermsTwo">'. $col2 .'</td>
			</tr>
		</table>';

		$html .= '<table id="tblBank">
					<tr>
						<td id="tdTblBankOne">Bank Name: Equitas Small Fin. Bank Ltd., Lohagal-Ajmer, Current Ac.No.: 200001335265, IFSC: ESFB0016021</td>
					</tr>
			</table>';


		$html .= '</td></tr></tbody>
				  <tfoot><tr><td>
				    <div class="footer-space">&nbsp;</div>
				  </td></tr></tfoot>
				</table>';

		$data['html'] = $html;
		echo json_encode($data);
	}


	function numberTowords($number)
	{
		$no = round($number);
	    $decimal = round($number - ($no = floor($number)), 2) * 100;    
	    $digits_length = strlen($no);    
	    $i = 0;
	    $str = array();
	    $words = array(
	        0 => '',
	        1 => 'One',
	        2 => 'Two',
	        3 => 'Three',
	        4 => 'Four',
	        5 => 'Five',
	        6 => 'Six',
	        7 => 'Seven',
	        8 => 'Eight',
	        9 => 'Nine',
	        10 => 'Ten',
	        11 => 'Eleven',
	        12 => 'Twelve',
	        13 => 'Thirteen',
	        14 => 'Fourteen',
	        15 => 'Fifteen',
	        16 => 'Sixteen',
	        17 => 'Seventeen',
	        18 => 'Eighteen',
	        19 => 'Nineteen',
	        20 => 'Twenty',
	        30 => 'Thirty',
	        40 => 'Forty',
	        50 => 'Fifty',
	        60 => 'Sixty',
	        70 => 'Seventy',
	        80 => 'Eighty',
	        90 => 'Ninety');
	    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
	    while ($i < $digits_length) {
	        $divider = ($i == 2) ? 10 : 100;
	        $number = floor($no % $divider);
	        $no = floor($no / $divider);
	        $i += $divider == 10 ? 1 : 2;
	        if ($number) {
	            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
	            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
	        } else {
	            $str [] = null;
	        }  
	    }
	    
	    $Rupees = implode(' ', array_reverse($str));
	    $paise = ($decimal) ? "And Paise " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])  : '';
	    return ($Rupees ? 'Rupees ' . $Rupees : '') . $paise . " Only";
	}
}
