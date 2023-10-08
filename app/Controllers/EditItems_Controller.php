<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Edititems_model;

class EditItems_Controller extends BaseController
{
	public function index()
	{
		// $model = new Edititems_model();
		// $MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		$data['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
        $data['title'] = "Edit Items";
		// echo view('header');
		// echo view('menu', $MenuRights);
        echo view('EditItems_view', $data);
		// echo view('footer');
	}   

	public function showData()
	{
		$timeStart = microtime(TRUE);
		$model = new Edititems_model();
		$data['records'] = $model->getDataForReport($this->request);
		$timeEnd = microtime(TRUE);
		$data['timeTook'] = round( $timeEnd - $timeStart, 3 ) ;
		echo json_encode($data);
	}

	public function showDataWithDt()
	{
		$timeStart = microtime(TRUE);
		$model = new Edititems_model();
		$data['records'] = $model->getDataForReportWithDt($this->request);
		$timeEnd = microtime(TRUE);
		$data['timeTook'] = round( $timeEnd - $timeStart, 3 ) ;
		echo json_encode($data);
	}

	public function getClosingBalance()
	{
		$timeStart = microtime(TRUE);
		$model = new Edititems_model();
		$data['records'] = $model->getClosingBalance($this->request);
		$timeEnd = microtime(TRUE);
		$data['timeTook'] = round( $timeEnd - $timeStart, 3 ) ;
		echo json_encode($data);
	}

	public function showDataDeleted()
	{
		$model = new Edititems_model();
		$data['records'] = $model->getDataForReportDeleted($this->request);
		echo json_encode($data);
	}

	public function saveData()
	{
		$model = new Edititems_model();
		$model->insertNow($this->request);
		/// delete
		$model->deleteNow($this->request);
	}

	public function delete()
	{
		$model = new Edititems_model();
		$model->deleteNow($this->request);
		$data['records'] = $model->getDataForReport($this->request);
		echo json_encode($data);
	}

	public function undelete()
	{
		$model = new Edititems_model();
		$model->undelete($this->request);
	}

	public function showDataNew()
	{
		$timeStart = microtime(TRUE);
		$model = new Edititems_model();
		$data['records'] = $model->getDataForReportWithDt($this->request);
        // Prepare the HTML table
        $table = '<table class=\'table table-bordered\' id=\'tblItems\'>';
		$table .= '<thead><tr style=\'background-color: #F0F0F0;\'>';
            $table .= '<th>S.N.</th>';
            $table .= '<th>Id</th>';
            $table .= '<th>Item Name</th>';
            $table .= '<th>S.Price</th>';
            $table .= '<th style=\'display:none;\'>Flag</th>';
            $table .= '<th class=\'clsPp\'>P.Price</th>';
            $table .= '<th>Delete</th>';
            $table .= '<th>Last Used Dt.</th>';
            $table .= '<th>GST Rate</th>';
            $table .= '<th>HSN</th>';
            $table .= '<th>Op. Bal.</th>';
            $table .= '<th>Cl. Bal.</th>';
            $table .= '<th>Group</th>';
        $table .= '</tr></thead>';
		$sn=1;
        foreach ($data['records'] as $row) {
            $table .= '<tr>';
				$table .= '<td>' . $sn++ . '</td>';
				$table .= '<td>' . $row['itemRowId'] . '</td>';
				$table .= '<td contentEditable=true>' . $row['itemName'] . '</td>';
				$table .= '<td contentEditable=true style=\'text-align: right;\'>' . $row['sellingPrice'] . '</td>';
				$table .= '<td style=\'display:none;\'>' . 0 . '</td>';
				$table .= '<td contentEditable=true style=\'text-align: right;\' class=\'clsPp\'>' . $row['pp'] . '</td>';
				$table .= '<td style=\'text-align: center;\'>' . "<input type='checkbox' class='chk' style='width:20px;height:20px;' name='chkDelete'/>" . '</td>';
				$table .= '<td>PV: ' . $row['pvDt'] . ' SV: ' . $row['svDt'] . '</td>';
				$table .= '<td contentEditable=true style=\'text-align: right;\'>' . $row['gstRate'] . '</td>';
				$table .= '<td contentEditable=true>' . $row['hsn'] . '</td>';
				$table .= '<td contentEditable=true style=\'text-align: right;\'>' . $row['openingBalance'] . '</td>';
				$table .= '<td></td>'; // closing. balance
				$table .= '<td contentEditable=true>' . $row['itemGroupName'] . '</td>';

            // foreach ($row as $cell) {
                // $table .= '<td>' . $cell . '</td>';
            // }
            $table .= '</tr>';
        }
        $table .= '</table>';
		// $data = [
        //     ['ID', 'Name', 'Age'],
        //     [1, 'John', 30],
        //     [2, 'Jane', 25],
        //     [3, 'Bob', 35],
        // ];

        // // Prepare the HTML table
        // $table = '<table>';
        // foreach ($data as $row) {
        //     $table .= '<tr>';
        //     foreach ($row as $cell) {
        //         $table .= '<td>' . $cell . '</td>';
        //     }
        //     $table .= '</tr>';
        // }
        // $table .= '</table>';
		$timeEnd = microtime(TRUE);
		$data['timeTook'] = round( $timeEnd - $timeStart, 3 ) ;
		// echo json_encode($table);
		return $this->response->setJSON(['table' => $table, 'timeTook' => $data['timeTook']]);
	}
}
