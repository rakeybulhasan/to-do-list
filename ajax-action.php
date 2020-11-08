<?php

require_once __DIR__ . '/vendor/autoload.php';
use Exam\Test\Todos;
use Exam\Test\Database;
$db = Database::getInstance();
$mysqli = $db->getConnection();

$result = array();
$action = '';

if(isset($_GET['action'])){
    $action=$_GET['action'];
}
$data = json_decode(file_get_contents("php://input"));
if($action==='INSERT'){
/*echo 'ok'.$data->titleData;
    return $data->titleData;*/
//    return array('status'=>'200','id'=>2,'message'=>'Data has been inserted successfully');
    /*$receiveData = json_decode(file_get_contents("php://input"));
    return $receiveData->title;*/
    $todoObj = new Todos();
   $result = $todoObj->addTodos($data);
}
if($action==='UPDATE'){
    $todoObj = new Todos();
    $result = $todoObj->updateTodo($data);
}
if($action==='UPDATE_STATUS'){

    $data= $_POST;
    $todoObj = new Todos();
    $result = $todoObj->updateStatus($data);
}
if($action==='DELETE'){
    $todoObj = new Todos();
    $result = $todoObj->deleteTodo();
}

if($action==='PRODUCT_LIST'){
    $todoObj = new Todos();
    $status = isset($_POST['status'])?$_POST['status']:'';
    $todos = $todoObj->getAllTodos($status);
    $output='';

    if($todos){
       $result['todos']= $todos;
    }

}
echo json_encode($result);

