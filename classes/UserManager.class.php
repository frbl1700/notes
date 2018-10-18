<?php
interface IUserManagement {
    function createUser($email);
    function getUserId($email);
}

class UserManager implements IUserManagement {
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

    public function createUser($email) {
        $id = 0;

        $sql = 
            "INSERT users (email, created) \n" .
            "VALUES (?, NOW())";

        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param('s', $email);
            $stmt->execute();

            $id = $stmt->insert_id;
            $stmt->close();
        }

        return $id;
    }

    public function getUserId($email) {
        $id = 0;
        $sql = "SELECT user_id FROM users WHERE email = ?";

        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param('s', $email);
            $stmt->execute();

            if ($result = $stmt->get_result()) {
                while ($row = $result->fetch_assoc()) {
                    $id = intval($row['user_id']);
                }
            }

            $stmt->close();
        }

        return $id;
    }
}
?>