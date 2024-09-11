<?php

    Class UserBalance{
        private $db;

        public function __construct($db){
            $this -> db = $db;
        }

        public function createUser($userId){
            try{
                $users = $this ->db->read('users', 'id', 'id = :id', ['id' => $userId]);

                if(count($users) !== 0){
                    return false;
                }

                $this->db->create('users',['id' => $userId,'balance' => 0]);
                return true;
            }catch(Exception $e){
                throw $e;
            }

        }

        public function getUserBalance($userId){
            try {
                $this->db->beginTransaction();

                $user = $this->db->read('users', 'id, balance', 'id = :id', ['id' => $userId]);
                
                if(count($user) === 0){
                    $this->db->rollBack();
                    return null;
                } 

                $this->db->commit();
                return $user[0]['balance'];
            }catch(Exception $e){
                throw $e;
            }
        }

        public function updateUserBalance($userId, $payment){
            try{
                $this->db->beginTransaction();

                $user = $this->db->read('users','id, balance', 'id = :id', ['id' => $userId]);

                if(count($user) === 0){
                    $this->db->rollBack();
                    return null;
                }

                $balance = $user[0]['balance'];
                $updatedBalance =  $balance + $payment;
                
                if($updatedBalance < 0){
                    $this->db->rollBack();
                    return null;
                }

                $this->db->update('users', array('balance' => $updatedBalance), 'id = :id', ['id' => $userId]);
                $this->db->commit();
                
                return $updatedBalance;
            }catch(Exception $e){
                throw $e;
            }
        }
    }

?>