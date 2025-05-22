<?php
include_once('Model/modelTable.php');
class controlTable {
    public function getAllTables() {
        $p = new modelTable(); 
        $kq = $p->selectAllTables(); 
        if ($kq && mysqli_num_rows($kq) > 0) {
            return $kq;  
        } else {
            return false; 
        }
    }
    public function getTableNumberById($id) {
    $p = new modelTable();
    $result = $p->selectTableById($id);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['table_number'];
    }
    return false;
}
    public function addTable($table_number, $status) {
        $p = new modelTable();
        return $p->insertTable($table_number, $status);
    }

    public function updateTableStatus($id, $status) {
        $p = new modelTable();
        return $p->updateTableStatus($id, $status);
    }
}
?>
