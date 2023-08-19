<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Items_model;

class Items_Controller extends BaseController
{
	public function index()
	{
		$model = new Items_model();
		$data['records'] = $model->getDataLimit();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Items_view', $data);
		echo view('footer');
	}  


	public function insert()
	{
		if( trim($this->request->getPost('itemName')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Items_model();
		if($model->checkDuplicate($this->request) == 1)
        {
        	$data = "Duplicate record...";
        	echo json_encode($data);
        }
        else
        {
			$data['affectedRows'] = $model->insertNow($this->request);
			$data['records'] = $model->getDataLimit();
			echo json_encode($data);
        }
	}

	public function update()
	{
		if( trim($this->request->getPost('itemName')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
    	// sleep(22);
		$model = new Items_model();
		if($model->checkDuplicateOnUpdate($this->request) == 1)
        {
        	$data = "Duplicate record...";
        	echo json_encode($data);
        }
        else
        {
			$data['affectedRows'] = $model->updateNow($this->request);
			$data['records'] = $model->getDataLimit();
			echo json_encode($data);
        }
	}

	public function delete()
	{
    	// sleep(22);
		$model = new Items_model();
		if($this->modelUtil->isDependent('purchasedetail', 'itemRowId', $this->request->getPost('rowId')) == 1)
        {
        	$data['dependent'] = "yes";
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
		$model = new Items_model();
		$data['records'] = $model->getDataAll();
		echo json_encode($data);
	}


}
