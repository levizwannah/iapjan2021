<?php
    
    interface Account{

        public function register (PDO $pdo);
        public function login(PDO $pdo);
        public function changePassword(PDO $pdo);
        public function logout (PDO $pdo);
        
    }

?>