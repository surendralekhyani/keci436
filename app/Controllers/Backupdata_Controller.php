<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Customers_model;
use App\Models\Backupdata_model;
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
		$size = '400x400';
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
		imagepng($QR,$imagename);
		imagedestroy($QR);
		return $imagename;
	}
}
class Backupdata_Controller extends BaseController
{
	public function index()
	{
		// if(function_exists('gd_info'))
		// {
		// 	echo "h";
		// }
		// else{
		// 	echo "NHI";
		// }
		// echo extension_loaded('gd');
		// echo "DDDD";
		// return;
		///////Get User IP, Browser & OS Details in Codeigniter
		// $this->load->library('user_agent');
		$agent = $this->request->getUserAgent();
		  $data['browser'] = $agent->getBrowser();
		  $data['browser_version'] = $agent->getVersion();
		  $data['os'] = $agent->getPlatform();
		  $data['ip_address'] = $this->request->getIPAddress();
		  ///////END - Get User IP, Browser & OS Details in Codeigniter

		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('BackupData_view', $data);
		echo view('footer');
	}  


	function dbbackup()
	{
		$this->deleteOldFiles();
		// Try this one, this works FOR both codeigniter and core PHP
		date_default_timezone_set('GMT');
		$databaseName = $this->db->getDatabase();
		$userName = $this->db->username;
		$pwd = $this->db->password;
		// echo $databaseName;
		// echo " " . $userName;
		// echo " " . $pwd;
		// return;
		$con = mysqli_connect("localhost", $userName, $pwd, $databaseName);

		$tables = array();
		$query = mysqli_query($con, 'SHOW TABLES');
		while($row = mysqli_fetch_row($query)){
			$tables[] = $row[0];
		}

		$result = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";';
		$result .= 'SET time_zone = "+00:00";';

		foreach($tables as $table){
		$query = mysqli_query($con, 'SELECT * FROM `'.$table.'`');
		$num_fields = mysqli_num_fields($query);

		$result .= 'DROP TABLE IF EXISTS `'.$table.'`;';
		$row2 = mysqli_fetch_row(mysqli_query($con, 'SHOW CREATE TABLE `'.$table.'`'));
		$result .= "\n\n".$row2[1].";\n\n";

		for ($i = 0; $i < $num_fields; $i++) {
		while($row = mysqli_fetch_row($query)){
		$result .= 'INSERT INTO `'.$table.'` VALUES(';
			for($j=0; $j<$num_fields; $j++){
			// $row[$j] = addslashes($row[$j]);
			$row[$j] = addslashes($row[$j] ?? '');
			$row[$j] = str_replace("\n","\\n",$row[$j]);
			if(isset($row[$j])){
				$result .= '"'.$row[$j].'"' ; 
				}else{ 
					$result .= '""';
				}
				if($j<($num_fields-1)){ 
					$result .= ',';
				}
			}
			$result .= ");\n";
		}
		}
		$result .="\n\n";
		}
		//Create Folder
		$folder = 'database/';
		if (!is_dir($folder))
		mkdir($folder, 0777, true);
		chmod($folder, 0777);

		$date = date('m-d-Y'); 
		$filename = $folder."db_filename_".$date; 

		$handle = fopen($filename.'.sql','w+');
		fwrite($handle,$result);
		fclose($handle);

		return $this->response->download($filename.'.sql', null);
		

		
    } 

	function deleteOldFiles()  
	{
		$c=0;
	  	$path = 'database/';
		if ($handle = opendir($path)) 
		{
		    while (false !== ($file = readdir($handle))) 
		    { 
		    	$ext = pathinfo($path . $file, PATHINFO_EXTENSION);
		    	$primaryName = pathinfo($path . $file, PATHINFO_FILENAME);
		        // $filelastmodified = filemtime($path . $file);
		        //24 hours in a day * 3600 seconds per hour
		        // if((time() - $filelastmodified) > 90*24*3600)///before 90 days
		        if( substr($primaryName, 0, 4) == "db_f" )///before 90 days
		        {
		        	if( $ext == "sql")
		        	{
		           		unlink($path . $file);
		           		$c++;
		           	}
		        }
		    }
		    closedir($handle); 
		}
		// echo $c;
	}

	public function createDummyData()
	{
		// $model = new Backupdata_model();
		// $model->createDummyData($this->request);
		echo ("Done...");
	}

	public function qrCode()
	{
		// $model = new Backupdata_model();
		// $model->createDummyData($this->request);
		$upi=new Upi();
		$beneficiaryName='Rohit Arya';
		$upiID='rohitarya@upi';
		$amount=10.00;
		$logo=base_url() . "/public/images/logo.png";
		// $logo='https://bharatbills.com/wp-content/uploads/2019/05/Bharat-Bills_Loadeer_1.png';
		$link=$upi->linkWithAmount($beneficiaryName,$upiID,$amount);
		$imagename=$upi->genQRWithLogo($link,$logo);
		echo ($imagename);
	}

	
	public function setZero()
	{
		$model = new Backupdata_model();
		$model->setZero($this->request);
		// echo ("Done...");
		echo json_encode("Done...");
	}
	
	public function showRechargeLimit()
	{
		$model = new Backupdata_model();
		$data['records'] = $model->showRechargeLimit();
		echo json_encode($data);
	}
	
	public function plusTen()
	{
		$model = new Backupdata_model();
		$data['records'] = $model->plusTen();
		echo json_encode('data');
	}
	
	public function plusTwenty()
	{
		$model = new Backupdata_model();
		$data['records'] = $model->plusTwenty();
		echo json_encode('data');
	}
}

	
