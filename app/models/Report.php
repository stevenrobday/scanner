<?php

class Report
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getOrders($date = '', $user_id = '', $orderNumber = '')
    {
        $sql = "SELECT order_number, c.created_at, username, u.id FROM completed_orders c JOIN users u ON user_id = u.id";
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d');
            $startTime = $date . " 00:00:00";
            $endTime = $date . " 23:59:59";
            $sql .= " WHERE (c.created_at >= :startTime AND c.created_at < :endTime)";
            if (!empty($user_id)) $sql .= " AND u.id = :user_id";
            if (!empty($orderNumber)) $sql .= " AND order_number = :order_number";

        } elseif (!empty($user_id)) {
            $sql .= " WHERE u.id = :user_id";
            if (!empty($orderNumber)) $sql .= " AND order_number = :order_number";
        } elseif (!empty($orderNumber)) {
            $sql .= " WHERE order_number = :order_number";
        }
        $sql .= " ORDER BY c.created_at DESC";

        $this->db->query($sql);

        if (!empty($date)) {
            $this->db->bind(':startTime', $startTime);
            $this->db->bind(':endTime', $endTime);
        }
        if (!empty($user_id)) $this->db->bind(':user_id', $user_id);
        if (!empty($orderNumber)) $this->db->bind(':order_number', $orderNumber);

        $rows = $this->db->resultset();
        foreach ($rows as &$row) {
            $datetime = new DateTime($row->created_at);
            $row->created_at = $datetime->format('m/d/Y h:i A');
        }

        return $rows;
    }
}
