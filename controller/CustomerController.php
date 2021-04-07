<?php
require 'model/CustomerModel.php';
require 'model/Customer.php';
require_once 'Controller.php';
require_once 'Config.php';

session_status() === PHP_SESSION_ACTIVE ? TRUE : session_start();

class CustomerController extends Controller
{
    private $customerModel;
    public function __construct()
    {
        $this->customerModel = new CustomerModel;
    }
    // check validation
    public function checkValidation($customer)
    {
        $noerror = true;
        $errors = [];
        // Validate name        
        if (empty($customer->name)) {
            $errors['name'] = "Name is required.";
            $noerror = false;
        } else {
            unset($errors['name']);
        }
        // Validate city            
        if (empty($customer->city)) {
            $errors['city'] = "City is required";
            $noerror = false;
        } else {
            unset($errors['city']);
        }
        if (!$noerror) {
            $_SESSION['errors'] = $errors;
        }
        return $noerror;
    }
    // add new record
    public function store()
    {
        try {
            $customer = new Customer();
            if (isset($_POST['addbtn'])) {
                // read form value
                $customer->name = trim($_POST['name']);
                $customer->phone = trim($_POST['phone']);
                $customer->email = trim($_POST['email']);
                $customer->address = trim($_POST['address']);
                $customer->city = trim($_POST['city']);
                $customer->created_at = date('Y-m-d H:i:s');
                //call validation
                $chk = $this->checkValidation($customer);
                if ($chk) {
                    //call insert record            
                    $pid = $this->customerModel->insertRecord($customer);
                    if ($pid > 0) {
                        unset($_SESSION['data']);
                        $this->pageRedirect('customers');
                    } else {
                        echo "Somthing is wrong..., try again.";
                    }
                } else {
                    $_SESSION['data'] = $_POST;
                    $this->pageRedirect("customers/create");
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    // update record
    public function update($id)
    {
        try {
            $customer = new Customer();
            if (isset($_POST['updatebtn'])) {
                $customer->id = trim($id);
                $customer->name = trim($_POST['name']);
                $customer->phone = trim($_POST['phone']);
                $customer->email = trim($_POST['email']);
                $customer->address = trim($_POST['address']);
                $customer->city = trim($_POST['city']);
                // check validation  
                $chk = $this->checkValidation($customer);
                if ($chk) {
                    $res = $this->customerModel->updateRecord($customer);
                    if ($res) {
                        unset($_SESSION['data']);
                        $this->pageRedirect('customers');
                    } else {
                        echo "Somthing is wrong..., try again.";
                    }
                } else {
                    $_SESSION['data'] = $_POST;
                    $this->pageRedirect("customers/edit/$id");
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    // delete record
    public function delete()
    {
        try {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $res = $this->customerModel->deleteRecord($id);
                if ($res) {
                    $this->pageRedirect('index.php');
                } else {
                    echo "Somthing is wrong..., try again.";
                }
            } else {
                echo "Invalid operation.";
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function index()
    {
        $result = (new CustomerModel)->selectRecord(0);
        include "view/customers/index.php";
    }

    public function create()
    {
        include "view/customers/create.php";
    }

    public function edit($id)
    {
        $result = $this->customerModel->selectRecord($id);
        include "view/customers/edit.php";
    }

    public function all()
    {
        $records = (new CustomerModel)->selectRecord(0);
        $customers = [];
        while ($row = mysqli_fetch_array($records)) {
            $customers[] = $row;
        }
        echo json_encode($customers);
    }
}
