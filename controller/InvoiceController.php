<?php
require 'model/InvoiceModel.php';
require 'model/Invoice.php';
require_once 'Controller.php';
require_once 'Config.php';

session_status() === PHP_SESSION_ACTIVE ? TRUE : session_start();

class InvoiceController extends Controller
{
    // mvc handler request
    public function mvcHandler()
    {
        $act = isset($_GET['act']) ? $_GET['act'] : NULL;
        switch ($act) {
            case 'add':
                $this->insert();
                break;
            case 'update':
                $this->update();
                break;
            case 'delete':
                $this->delete();
                break;
            default:
                $this->index();
        }
    }
    // check validation
    public function checkValidation($invoice)
    {
        $noerror = true;
        // Validate category        
        if (empty($invoice->category)) {
            $invoice->category_msg = "Field is empty.";
            $noerror = false;
        } elseif (!filter_var($invoice->category, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
            $invoice->category_msg = "Invalid entry.";
            $noerror = false;
        } else {
            $invoice->category_msg = "";
        }
        // Validate name            
        if (empty($invoice->name)) {
            $invoice->name_msg = "Field is empty.";
            $noerror = false;
        } elseif (!filter_var($invoice->name, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
            $invoice->name_msg = "Invalid entry.";
            $noerror = false;
        } else {
            $invoice->name_msg = "";
        }
        return $noerror;
    }
    // add new record
    public function insert()
    {
        try {
            $invoice = new Invoice();
            if (isset($_POST['addbtn'])) {
                // read form value
                $invoice->category = trim($_POST['category']);
                $invoice->name = trim($_POST['name']);
                //call validation
                $chk = $this->checkValidation($invoice);
                if ($chk) {
                    //call insert record            
                    $pid = $this->objsm->insertRecord($invoice);
                    if ($pid > 0) {
                        $this->index();
                    } else {
                        echo "Somthing is wrong..., try again.";
                    }
                } else {
                    $_SESSION['invoicel0'] = serialize($invoice); //add session obj           
                    $this->pageRedirect("view/insert.php");
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    // update record
    public function update()
    {
        try {

            if (isset($_POST['updatebtn'])) {
                $invoice = unserialize($_SESSION['invoicel0']);
                $invoice->id = trim($_POST['id']);
                $invoice->category = trim($_POST['category']);
                $invoice->name = trim($_POST['name']);
                // check validation  
                $chk = $this->checkValidation($invoice);
                if ($chk) {
                    $res = $this->objsm->updateRecord($invoice);
                    if ($res) {
                        $this->index();
                    } else {
                        echo "Somthing is wrong..., try again.";
                    }
                } else {
                    $_SESSION['invoicel0'] = serialize($invoice);
                    $this->pageRedirect("view/update.php");
                }
            } elseif (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
                $id = $_GET['id'];
                $result = $this->objsm->selectRecord($id);
                $row = mysqli_fetch_array($result);
                $invoice = new Invoice();
                $invoice->id = $row["id"];
                $invoice->name = $row["name"];
                $invoice->category = $row["category"];
                $_SESSION['invoicel0'] = serialize($invoice);
                $this->pageRedirect('view/update.php');
            } else {
                echo "Invalid operation.";
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
                $res = $this->objsm->deleteRecord($id);
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
        $result = (new InvoiceModel)->selectRecord(0);
        include "view/index.php";
    }

    public function create()
    {
        include "view/create.php";
    }
}
