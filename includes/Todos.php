<?php
namespace Exam\Test;

class Todos
{
    private $db_connect;

    public function __construct()
    {
        $db = Database::getInstance();
        $this->db_connect = $db->getConnection();
    }

    public function getAllTodos($status){
        $sql_query = "SELECT id, title, status FROM todos where status={$status}";
        $result = $this->db_connect->query($sql_query);
        if ( $result->num_rows > 0) {
          return  $result->fetch_all(MYSQLI_ASSOC);
        }
        return false;

    }

    public function addTodos($data){
        $name = $this->db_connect->escape_string($data->titleData);
        $query = "INSERT INTO todos (title) VALUES ('{$name}')";
        $result = $this->db_connect->query($query);
        if($result){
            $last_id = mysqli_insert_id($this->db_connect);
            return array('status'=>'200','id'=>$last_id,'message'=>'Data has been inserted successfully');
        }else{
            return array('error'=>true,'status'=>'301','message'=>'Something error');
        }

    }

    public function updateTodo($data){
        $id = $data->id;
        $name = $this->db_connect->escape_string($data->title);
        $query = "UPDATE todos SET title='{$name}' WHERE id={$id}";
        $result = $this->db_connect->query($query);
        if($result){
            return array('status'=>'200','action'=>'update','message'=>'Data has been updated successfully');
        }else{
            return array('error'=>true,'status'=>'301','message'=>'Something error');
        }
    }
    public function updateStatus($data){
        $query = "UPDATE todos SET status={$data['status']} WHERE id IN({$data['ids']})";
        $result = $this->db_connect->query($query);
        if($result){
            return array('status'=>'200','action'=>'update','message'=>'Status has been updated successfully');
        }else{
            return array('error'=>true,'status'=>'301','message'=>'Something error');
        }
    }

    public function deleteTodo(){
        $query = "DELETE FROM `todos` WHERE `status`=3";
        $result = $this->db_connect->query($query);
        if($result){
            return array('status'=>'200','message'=>'Data clear successfully');
        }else{
            return array('error'=>true,'status'=>'301','message'=>'Something error');
        }

    }

}

