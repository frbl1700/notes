<?php

interface INotesManagement {
    function getNotes($user_id);
    function createNote($user_id, $text);
    function updateNote($user_id, $note_id, $text);
    function deleteNote($user_id, $note_id);
}

class NotesManager implements INotesManagement {
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

    function getNotes($user_id) {
        $sql = "SELECT note_id, user_id, text, created, updated FROM notes WHERE user_id = ?";
        $notes = [];

        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();

            if ($result = $stmt->get_result()) {
                while ($row = $result->fetch_assoc()) {
                    $note = new Note();
                    $note->note_id = intval($row['note_id']);
                    $note->user_id = intval($row['user_id']);
                    $note->text = $row['text'];
                    $notes[] = $note;
                }
            }

            $stmt->close();
        }

        return $notes;
    }

    function createNote($user_id, $text) {
        $id = 0;
        $sql = "INSERT notes (user_id, text, created) VALUES (?, ?, NOW())";

        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param('is', $user_id, $text);
            $stmt->execute();
            $id = $stmt->insert_id;
            $stmt->close();
        }

        return $id;
    }

    function updateNote($user_id, $note_id, $text) {
        $sql = "UPDATE notes SET text = ? WHERE note_id = ? AND user_id = ?";

        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param('sii', $text, $note_id, $user_id);
            $stmt->execute();
            $stmt->close();

            return true;
        }

        return false;
    }

    function deleteNote($user_id, $note_id) {
        $sql = "DELETE FROM notes WHERE note_id = ? AND user_id = ?";

        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param('ii', $note_id, $user_id);
            $stmt->execute();
            $stmt->close();

            return true;
        }

        return false;
    }
}
?>