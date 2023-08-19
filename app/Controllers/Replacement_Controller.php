<?php 
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\Replacement_model;

class Replacement_Controller extends BaseController
{
	
	public function index()
	{
		$model = new Replacement_model();
		$data['customers'] = $model->getCustomers();
      	$data['items'] = $model->getItems();
      	$data['records'] = $model->getDataLimit();
		$data['recordsOld'] = $model->getDataLimitOld();
		$MenuRights['mr'] = $this->modelUtil->getUserRights();
        $data['errMsg'] = "";
		echo view('header');
		echo view('menu', $MenuRights);
        echo view('Replacement_view', $data);
		echo view('footer');
	}  
	

	public function insert()
	{
		if( trim($this->request->getPost('itemName')) == "" )
		{
			echo json_encode("Khali");
			return;
		}
		$model = new Replacement_model();
		$model->insertNow($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}

	

  public function update()
  {
	if( trim($this->request->getPost('itemName')) == "" )
	{
		echo json_encode("Khali");
		return;
	}
	  $model = new Replacement_model();
      $model->updateNow($this->request);
      $data['records'] = $model->getDataLimit();
      echo json_encode($data);
  }
	public function delete()
	{
		$model = new Replacement_model();
		$model->deleteNow($this->request);
		$data['records'] = $model->getDataLimit();
		echo json_encode($data);
	}

  public function setSent()
  {
	$model = new Replacement_model();
    $model->setSent($this->request);
    $data['records'] = $model->getDataLimit();
    echo json_encode($data);
  }

  public function setRecd()
  {
	$model = new Replacement_model();
    $model->setRecd($this->request);
    $data['records'] = $model->getDataLimit();
    echo json_encode($data);
  }

	public function loadAllRecords()
	{
		$model = new Replacement_model();
		$data['records'] = $model->getDataAllOld();
		echo json_encode($data);
	}

}
