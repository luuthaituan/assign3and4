<?php
namespace libs;
use PDO;
 class DB
 {
     protected $db;
     protected $tableName;
     public function __construct()
     {
         $host = 'localhost';
         $dbname = 'mvc';
         $username = 'root';
         $password = 'Tuan@8999';
    
         try {
             $db = new PDO(
                 "mysql:host=$host;dbname=$dbname;charset=utf8",
                 $username,
                 $password
             );
             $this->db = $db;
         } catch (PDOException $e) {
             echo $e->getMessage();
         }
     }

     public function table($table) {
         $this->tableName = $table;
     }
     public function getData()
     {
         $sql = "SELECT * FROM $this->tableName";
         $result = $this->queryAll($sql);
         $rows = $result->fetchAll();
         return $rows;
     }
    
     public function insertData($data = [])
     {
         //$data = ['title' => 'title1', 'content' => 'content1' ];
         $sql = "INSERT INTO $this->tableName "; 
         $field = null;
         $values = null;
         foreach ($data as $fieldName => $value){
             $field .= "$fieldName, ";
             $values .= "'$value', ";
         }
         $field = trim($field, ", "); //loai ki tu thua 2 dau
         $values = trim($values, ", ");
         $sql.= "($field) values ($values) ";
         $result = $this->queryAll($sql);
         return $result;
     }
    
     public function deleteData($id)
     {
         $sql = "DELETE FROM $this->tableName WHERE id= $id " ;
         return  $this->queryAll($sql);
     }
    
     public function updateData($id, $data= [])
     {
        $sql = "UPDATE $this->tableName SET "; 
        $setValue = null;
        foreach ($data as $fieldName => $value){
            $setValue .= "$fieldName = '$value', ";
        }
        $setValue = trim($setValue, ", "); //loai ki tu thua 2 dau
        $sql.= "$setValue where id = $id";
        $result = $this->queryAll($sql);
        return $result;
     }
     public function queryAll($sql) {
        $start = microtime(true) * 1000;
        $result =  $this->db->query($sql);
        $end = microtime(true) * 1000;
        $executeTime = number_format($end - $start,2);
        $this->writeLog($executeTime, $sql);
        return $result;
     }

     public function writeLog($executeTime, $sql){
        $logs = [
            'logMessage' => 'SLOW SQL',
            'logDateTime' => date('Y-m-d H:i:s'),
            'data' => ['sql' => $sql, 'time' => "$executeTime ms"]
        ];
        $logs = json_encode($logs);
        $query = 'query_';
        if($executeTime > 50){
            $query = 'slow_';
        }
        
        if(!file_exists(dirname(__DIR__).'/logs/mysql')){
            mkdir(dirname(__DIR__).'/logs/mysql', 0777);
        }
        $path = dirname(__DIR__).'/logs/mysql/'.$query.date('Ymd_His').'.log';
        $path = str_replace('/', '\\', $path);
        file_put_contents($path, $logs);
    }


 }