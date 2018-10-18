<?php

class NotesManager {
    private $db;

    function __construct($db_host, $db_name, $db_user, $db_password) {		
        $this->db = new mysqli($db_host, $db_user, $db_password, $db_name);
    
        if ($this->db->connect_errno > 0) {
            die('Anslutningsfel ' . $this->db->connect_error);
        }
    }
    
    function __destruct() {
        $this->db->close();
    }
}
?>