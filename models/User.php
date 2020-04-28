<?php
    class User {
        // DB Stuff
        private $conn;
        private $table = 'users';

        // Properties
        private $id;
        private $hashedPwd;
        public $pwd;
        public $uid;
        public $email;
        public $category_id;
        public $post_id;

        // Constructor with DB
        public function __construct($db) {
            $this->conn = $db;
        }

        public function signUp(){
            // Error handlers
            // Check fro empty fields
            if(empty($this->uid) || empty($this->email) || empty($this->pwd)) {
                // header()
                exit();
            } else {
                if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                    // header()
                    exit();
                } else {
                    if($this->checkIfUserExists()) {
                        // header()
                        exit();
                    } else {
                          // Hashing the password
                          $this->hashedPwd = password_hash($this->pwd, PASSWORD_DEFAULT);

                          // Insert the user into the database

                          // Create query
                          $query = 'INSERT INTO ' . $this->table . '
                               SET
                                   uid = :uid,
                                   email = :email,
                                   pwd = :hashedpwd';

                          // Prepare statement
                          $stmt = $this->conn->prepare($query);

                          // Bind data
                          $stmt->bindParam(':uid', $this->uid);
                          $stmt->bindParam(':email', $this->email);
                          $stmt->bindParam(':hashedpwd', $this->hashedPwd);

                          // Execute query
                          if($stmt->execute()) {
                              return true;
                          }

                          // Print error if smth goes wrong
                          printf("Error: %s. \n", $stmt->error);

                          return false;
                    }
                }
            }
        }

        private function checkIfUserExists() {
            // Create query
            $query = 'SELECT * FROM ' . $this->table . '
            WHERE uid = :uid && email = :email';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Bind data
            $stmt->bindParam(':uid', $this->uid);
            $stmt->bindParam(':email', $this->email);

            // Execute query
            $stmt->execute();

            // Row count to see if user exists
            $resultCheck = $stmt->rowCount();

            if($resultCheck > 0) {
                return true; // User does exist
            }

            return false; // User does not exist

    }
}

 ?>
