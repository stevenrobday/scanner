<?php

class Order
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function findCompletedOrder($orderNumber)
    {
        $this->db->query("SELECT id FROM completed_orders WHERE order_number = :order_number");
        $this->db->bind(':order_number', $orderNumber);

        $row = $this->db->single();

        //Check Rows
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function findLocalOrder($orderNumber)
    {
        $this->db->query("SELECT order_id FROM orders WHERE order_number = :order_number");
        $this->db->bind(':order_number', $orderNumber);

        $row = $this->db->single();

        //Check Rows
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function insertLocalOrder($orderNumber, $json)
    {
        $this->db->query("INSERT INTO orders (order_number, json) VALUES (:order_number, :json)");
        $this->db->bind(':order_number', $orderNumber);
        $this->db->bind(':json', $json);
        $this->db->execute();
    }

    public function updateLocalOrder($orderNumber, $json)
    {
        $this->db->query("UPDATE orders SET json = :json WHERE order_number = :order_number");
        $this->db->bind(':json', $json);
        $this->db->bind(':order_number', $orderNumber);
        $this->db->execute();
    }

    public function insertCompletedOrder($user_id, $orderNumber, $createdAt)
    {
        $this->db->query("INSERT INTO completed_orders (user_id, order_number, created_at) VALUES (:user_id, :order_number, :created_at)");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':order_number', $orderNumber);
        $this->db->bind(':created_at', $createdAt);
        $this->db->execute();
    }

    public function deleteSavedOrder($orderNumber)
    {
        if ($id = $this->findSavedOrder($orderNumber)) {
            $this->db->query("DELETE FROM saved_orders WHERE id = :id ORDER BY id LIMIT 1");
            $this->db->bind(':id', $id);
            $this->db->execute();
        }
    }

    public function getOrderJson($orderNumber)
    {
        $this->db->query("SELECT json FROM orders WHERE order_number = :order_number");
        $this->db->bind(':order_number', $orderNumber);

        $row = $this->db->single();

        //Check Rows
        if ($this->db->rowCount() > 0) {
            return json_decode($row->json, true);
        } else {
            return false;
        }
    }

    public function getSavedOrder($orderNumber)
    {
        $this->db->query("SELECT skus FROM saved_orders WHERE order_number = :order_number");
        $this->db->bind(':order_number', $orderNumber);

        $row = $this->db->single();

        //Check Rows
        if ($this->db->rowCount() > 0) {
            return $row->skus;
        } else {
            return false;
        }
    }

    public function addSavedOrder($orderNumber, $skus)
    {
        if ($id = $this->findSavedOrder(($orderNumber))) {
            $this->updateSavedOrder($id, $skus);
        } else {
            $this->insertSavedOrder($orderNumber, $skus);
        }
    }

    private function findSavedOrder($orderNumber)
    {
        $this->db->query("SELECT id FROM saved_orders WHERE order_number = :order_number");
        $this->db->bind(':order_number', $orderNumber);

        $row = $this->db->single();

        //Check Rows
        if ($this->db->rowCount() > 0) {
            return $row->id;
        } else {
            return false;
        }
    }

    private function insertSavedOrder($orderNumber, $skus)
    {
        $this->db->query("INSERT INTO saved_orders (order_number, skus) VALUES (:order_number, :skus)");
        $this->db->bind(':order_number', $orderNumber);
        $this->db->bind(':skus', $skus);
        $this->db->execute();
    }

    private function updateSavedOrder($id, $skus)
    {
        $this->db->query("UPDATE saved_orders SET skus = :skus WHERE id = :id");
        $this->db->bind(':skus', $skus);
        $this->db->bind(':id', $id);
        $this->db->execute();
    }
}