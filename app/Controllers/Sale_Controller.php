<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Sale_model;
use App\Models\Quotation_model;


class Upi {
	
	/**
	 * Function to generate UPI Link With Amount
	 *
	 *
	 * @param pn Payee Name
	 * @param pa UPI Address of Payee
	 * @param am Amount
	 */
	public function linkWithAmount($pn=NULL,$pa=NULL,$am=NULL){
		if($pn==NULL || $pa==NULL || $am==NULL)
			return 'All fields are mandatory';
		else
		{
			$am=number_format($am,2,'.','');
			$data = "upi://pay?pn=".urlencode($pn)."&pa=";
            $data.=$pa;
            $data.='&cu=INR';
            $data.="&am=".$am;
			return $data;
		}
	}
	
	/**
	 * Function to generate UPI Link Without Amount
	 *
	 *
	 * @param pn Payee Name
	 * @param pa UPI Address of Payee
	 */
	public function linkWithoutAmount($pn=NULL,$pa=NULL){
		if($pn==NULL || $pa==NULL)
			return 'All fields are mandatory';
		else
		{
			$data = "upi://pay?pn=".urlencode($pn)."&pa=";
            $data.=$pa;
            $data.='&cu=INR';
			return $data;
		}
	}
	
	/**
	 * Function to generate QR Code using google chart
	 *
	 *
	 * @param data Link generated from above functions
	 * 
	 */
	public function genQR($data){
		$size = '400x400';
		$QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs='.$size.'&chl='.urlencode($data));
		$imagename="qr/".uniqid().'.png';
		imagepng($QR,$imagename);
		imagedestroy($QR);
		return $imagename;
	}
	
	/**
	 * Function to generate QR Code using google chart
	 *
	 *
	 * @param data Link generated from above functions
	 * @logo Address of image to be shown on payment QR
	 */
	public function genQRWithLogo($data,$logo){
		////////delete old png files
		$c=0;
	  	$path = 'qr/';
		if ($handle = opendir($path)) 
		{
		    while (false !== ($file = readdir($handle))) 
		    { 
				// dd($file);
				$ext = pathinfo($path . $file, PATHINFO_EXTENSION);
		    	$primaryName = pathinfo($path . $file, PATHINFO_FILENAME);
		        // if((time() - $filelastmodified) > 90*24*3600)///before 90 days
		        // if( substr($primaryName, 0, 4) == "db_f" )///before 90 days
		        {
		        	if( $ext == "png")
		        	{
		           		unlink($path . $file);
		           		$c++;
		           	}
		        }
		    }
		    closedir($handle); 
		}
		////////END - delete old png files

		$size = '200x200';
		$QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs='.$size.'&chl='.urlencode($data));
		if($logo !== FALSE){
            	$logo = imagecreatefromstring(file_get_contents($logo));
            
            	$QR_width = imagesx($QR);
            	$QR_height = imagesy($QR);
            	
            	$logo_width = imagesx($logo);
            	$logo_height = imagesy($logo);
            	
            	// Scale logo to fit in the QR Code
            	$logo_qr_width = $QR_width/3;
            	$scale = $logo_width/$logo_qr_width;
            	$logo_qr_height = $logo_height/$scale;
            	
            	imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
		$imagename="qr/".uniqid().'.png';
		// $imagename='qr/123.png';
		imagepng($QR,$imagename);
		// imagedestroy($QR);
		return $imagename;
	}
}


class Sale_Controller extends BaseController
{
	public function index()
	{
		$model = new Sale_model();
		$data['customers'] = $this->modelUtil->getCustomerWithBalance();
		$data['items'] = $model->getItems();
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Sale_view', $data);
		echo view('footer');
	}  

	public function insert()
	{
		if( trim($this->request->getPost('customerRowId')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Sale_model();
		$saleRowId = $model->insertNow($this->request);
		$this->printNow('Save', $saleRowId);
	}
	
	// public function getLastPurchasePrice()
	// {
	// 	$data['lastPurchasePrice'] = $this->Sale_model->getLastPurchasePrice();
	// 	echo json_encode($data);
	// }

	public function showDetailOnUpdate()
	{
		$model = new Sale_model();
		$data['records'] = $model->showDetail($this->request);
		$data['customerInfo'] = $model->getCustomerInfo($this->request);
		$data['customerBalance'] = $model->getThisCustomerWithBalance($this->request);
		echo json_encode($data);
	}

	public function checkForUpdate()
	{
		$model = new Sale_model();
		if($model->checkForUpdate() == 1)
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
		$model = new Sale_model();
		$model->updateNow($this->request);
		$this->printNow('Update', $this->request->getPost('globalrowid'));
	}

	public function printNow($arg, $saleRowId)
	{
		$model = new Sale_model();
		$data['records'] = $model->getDataLimit();
		$rowId = -10;
			$rowId = $saleRowId;

		$data['org'] = $this->modelUtil->getOrg();
		$orgName = $data['org'][0]['orgName'];
		$orgAddress1 = $data['org'][0]['add1'];
		$orgAddress2 = $data['org'][0]['add2'];
		$orgAddress3 = $data['org'][0]['add3'];
		$orgAddress4 = $data['org'][0]['add4'];

		$html="";
		// $html='<table border="0" id="tblMain"><thead><tr><td>
				    // <div class="header-space">&nbsp;</div>
				//   </td></tr></thead><tbody><tr><td>';
		
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


		$html .= "<p align='center' id='billOfSupply'>Bill of Supply</p>";
		
		$data['custInfo'] = $model->getCustInfo($rowId);
		$html .= '<table id="tblCustInfo" border=0>
					<tr>
						<td class="tdFirstColOfTblCustInfo" align="left">Name: <span id="custName">'.$data['custInfo'][0]['customerName'].'</span></td>
						<td class="tdSecondColOfTblCustInfo" align="right">Date: '. date('d-M-Y', strtotime($data['custInfo'][0]['dbDt'] )).'</td>
					</tr>
					<tr>
						<td id="tdCustAddress" class="tdFirstColOfTblCustInfo" align="left">Address: '.$data['custInfo'][0]['address'].'</td>
						<td class="tdSecondColOfTblCustInfo" align="right">No.: '. str_pad($rowId, 5, '0', STR_PAD_LEFT) . '</td>
					</tr>
				</table>';

		//////////// Items table
		$data['products'] = $model->getProducts($rowId);
		$sn=1;
		$itemRows ="";
		foreach ($data['products'] as $row) {
			if ( $row['itemRemarks'] != "" )
			{
				// if ( strlen($row['hsn']) > 3  )
				// {
				// 	$itemName = $row['itemName'] . " [" . ($row['hsn'])  . "]" . " [" . ($row['itemRemarks'])  . "]";
				// }
				// else
				{
					$itemName = $row['itemName'] . " [" . ($row['itemRemarks'])  . "]";
				}
			}
			else
			{
				// if ( strlen($row['hsn']) > 3  )
				// {
				// 	$itemName = $row['itemName'] . " [" . ($row['hsn'])  . "]";
				// }
				// else
				{
					$itemName = $row['itemName'];
				}
			}
			$itemRows .= "<tr>";
				$itemRows .= "<td class='clsProductsSn'>". $sn++ ."</td>";
				$itemRows .= "<td class='clsProductsDescription'>". $itemName . "</td>";
				$itemRows .= "<td class='clsProductsQty'>". number_format((float)$row['qty'], 2) ."</td>";
				$itemRows .= "<td class='clsProductsRate'>". number_format((float)$row['rate'], 2) ."</td>";
				$itemRows .= "<td class='clsProductsAmt'>". number_format((float)$row['netAmt'], 2) ."</td>";
			$itemRows .= "</tr>";
		}

		$html .= '<table id="tblProducts">
					<tr>
						<th id="thSn" class="clsProductsSn">#</th>
						<th id="thDescription" class="clsProductsDescription">Description [rem]</th>
						<th id="thQty" class="clsProductsQty">Qty.</th>
						<th id="thRate" class="clsProductsRate">Rate</th>
						<th id="thAmt" class="clsProductsAmt">Amt.</th>
					</tr>'. $itemRows .
			'</table>';

		$html .= '<p>.......</p>';

		// if($sn == 18)
		// {
		// 	$html .= '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>';
		// }
		// else if($sn == 19 || $sn == 20)
		// {
		// 	$html .= '<p>&nbsp;</p><p>&nbsp;</p>';
		// }

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
		
		$col11 = "";
// 		$col11 .= '<br>';
		$col11 .="";
		
		// ///// qr code
        // $upi=new Upi();
		// $beneficiaryName='Kamal Computers';
		// $upiID='Q441480091@ybl';
		// $amount=$col3;
		// $logo=base_url() . "/public/images/logo.png";
		
		// $link=$upi->linkWithAmount($beneficiaryName,$upiID,$amount);
		// $imagename=$upi->genQRWithLogo($link,$logo);
		// ///// qr code
		
		// $imgPath = "";
		$col2 = "";
		$col2 .= "For: " . $orgName . '<br>' . '<br>' . '<br>' . '<br>';
		$col2 .= "Authorised Signatory";
		$imgPath = "";
		// $imgPath = base_url() . "/public/" . $imagename;
		//<td id="tdTblTermsOneOne"><img src='. $imgPath .' alt="" style="width: 25mm; height: 25mm;" ></td>

		$html .= '<table border="0" id="tblTerms">
			<tr>
				<td id="tdTblTermsOne">'. $col1 .'</td>
				<td id="tdTblTermsOneOne"></td>
				<td id="tdTblTermsTwo">'. $col2 .'</td>
			</tr>
		</table>';

		$html .= '<table id="tblBank">
					<tr>
						<td id="tdTblBankOne">Bank Name: Equitas Small Fin. Bank Ltd., Lohagal-Ajmer, Current Ac.No.: 200001335265, IFSC: ESFB0016021<br><b>TollFree Nos. Bajaj: 18001025963, Usha: 18001808742, Crompton: 18004190506, Havells: 08045771313, Indo: 18001021818</b> </td>
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


	public function delete()
	{
		$model = new Sale_model();
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
		$model = new Sale_model();
		$data['records'] = $model->getDataAll();
		echo json_encode($data);
	}

	public function searchRecords()
	{
		$model = new Sale_model();
		$data['records'] = $model->searchRecords($this->request);
		echo json_encode($data);
	}


	public function getQuotations()
	{
		$model = new Quotation_model();
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}
	public function getAllQuotations()
	{
		$model = new Quotation_model();
		$data['records'] = $model->getDataAll();
		echo json_encode($data);
	}
	public function getQuotationProducts()
	{
		$model = new Sale_model();
		$data['records'] = $model->getQuotationProducts($this->request);
		echo json_encode($data);
	}
	public function getSaleDetial()
	{
		$model = new Sale_model();
		$data['saleDetail'] = $model->getSaleDetail($this->request);
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



	public function getSaleLog()
	{
		$model = new Sale_model();
		$data['records'] = $model->getSaleLog($this->request);
		$data['recordsPurchaseLog'] = $model->getPurchaseLog($this->request);
		echo json_encode($data);
	}	

	
	public function getCurrentQtyOfThisItem()
	{
		$model = new Sale_model();
		$data['records'] = $model->getCurrentQtyOfThisItem($this->request);
		echo json_encode($data);
	}	
}
