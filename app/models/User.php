<?php
  class User {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function findUsername($username){
      $this->db->query("SELECT id FROM users WHERE username = :username");
      $this->db->bind(':username', $username);

      $row = $this->db->single();

      //Check Rows
      if($this->db->rowCount() > 0){
        return true;
      } else {
        return false;
      }
    }

    // Login / Authenticate User
    public function login($username, $password){
      $this->db->query("SELECT id, username, password FROM users WHERE username = :username");
      $this->db->bind(':username', $username);

      $row = $this->db->single();
      
      $hashed_password = $row->password;
      if(password_verify($password, $hashed_password)){
        return $row;
      } else {
        return false;
      }
    }

    public function getUsernameById($id){
      $this->db->query("SELECT username FROM users WHERE id = :id");
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return ucwords($row->username);
    }

    public function getStandardUsers(){
      $this->db->query("SELECT username, id FROM users WHERE username != 'xxxx' ORDER BY username ASC");
      $users = $this->db->resultSet();
      return $users;
    }
  }