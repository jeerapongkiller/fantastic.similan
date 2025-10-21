<?php
require_once __DIR__ . '/DB.php';

class Quotation extends DB
{
    public $response = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function showlist()
    {
        $query = "SELECT *
            FROM quotations 
            WHERE id > 0
        ";

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function get_data(int $id)
    {
        $query = "SELECT *
        FROM quotations 
        WHERE id = ?
        ";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
        } else {
            $data = false;
        }

        return $data;
    }

    public function get_datas(string $select, string $from, string $where)
    {
        $query = "SELECT $select
            FROM $from 
            WHERE $where
        ";

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $data = false;
        }

        return $data;
    }

    public function checkinvno()
    {
        $query = "SELECT MAX(quo_no) as max_quo_no
            FROM quotations 
        ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();

        return $data;
    }

    public function showbankaccount()
    {
        $query = "SELECT BAA.*,
            BAN.id as banID, BAN.name as banName 
            FROM bank_account BAA
            LEFT JOIN banks BAN
                ON BAA.bank_id = BAN.id
            WHERE BAA.id > 0
            AND BAA.is_approved = 1
            AND BAA.is_deleted = 0
        ";
        $query .= " ORDER BY BAA.account_name ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }

    public function insert_data(int $quo_no, string $quo_full, int $title, string $name, string $date_quo, string $seller, string $cus_name, string $bank_id)
    {
        $query = "INSERT INTO `quotations`(`quo_no`, `quo_full`, `title`, `name`, `date_quo`, `seller`, `cus_name`, `bank_id`, `created_at`, `updated_at`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $statement = $this->connection->prepare($query);
        $statement->bind_param("isissssi", $quo_no, $quo_full, $title, $name, $date_quo, $seller, $cus_name, $bank_id);

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function insert_quotation_detail(string $name, string $detail, int $qty, string $cost, string $discount, int $quotation_id)
    {
        $bind_types = "";
        $params = array();

        $query = "INSERT INTO `quotation_detail`(`name`, `detail`, `qty`, `cost`, `discount`, `quotation_id`, `created_at`)
        VALUES (?, ?, ?, ?, ?, ?, now())";

        $bind_types .= "s";
        array_push($params, $name);

        $bind_types .= "s";
        array_push($params, $detail);

        $bind_types .= "i";
        array_push($params, $qty);

        $bind_types .= "d";
        array_push($params, $cost);

        $bind_types .= "d";
        array_push($params, $discount);

        $bind_types .= "i";
        array_push($params, $quotation_id);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = $this->connection->insert_id;
        }

        return $this->response;
    }

    public function update_data(int $title, string $name, string $date_quo, string $seller, string $cus_name, string $bank_id, int $id)
    {
        $bind_types = "";
        $params = array();

        $query = "UPDATE quotations SET";

        $query .= " title = ?,";
        $bind_types .= "i";
        array_push($params, $title);

        $query .= " name = ?,";
        $bind_types .= "s";
        array_push($params, $name);

        $query .= " date_quo = ?,";
        $bind_types .= "s";
        array_push($params, $date_quo);

        $query .= " seller = ?,";
        $bind_types .= "s";
        array_push($params, $seller);

        $query .= " cus_name = ?,";
        $bind_types .= "s";
        array_push($params, $cus_name);

        $query .= " bank_id = ?,";
        $bind_types .= "i";
        array_push($params, $bank_id);

        $query .= " updated_at = now()";

        $query .= " WHERE id = ?";
        $bind_types .= "i";
        array_push($params, $id);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function update_quotation_detail(string $name, string $detail, int $qty, string $cost, string $discount, int $id)
    {
        $bind_types = "";
        $params = array();

        $query = "UPDATE quotation_detail SET";

        $query .= " name = ?,";
        $bind_types .= "s";
        array_push($params, $name);

        $query .= " detail = ?,";
        $bind_types .= "s";
        array_push($params, $detail);

        $query .= " qty = ?,";
        $bind_types .= "i";
        array_push($params, $qty);

        $query .= " cost = ?,";
        $bind_types .= "d";
        array_push($params, $cost);

        $query .= " discount = ?";
        $bind_types .= "d";
        array_push($params, $discount);

        $query .= " WHERE id = ?";
        $bind_types .= "i";
        array_push($params, $id);

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';

        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function delete_data(int $id)
    {
        $query = "DELETE FROM quotations WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        if ($statement->execute()) {
            $this->response = true;

            $query = "DELETE FROM quotation_detail WHERE quotation_id = ?";
            $statement = $this->connection->prepare($query);
            $statement->bind_param("i", $id);
            $statement->execute();
            if ($statement->execute()) {
                $this->response = true;
            }
        }

        return $this->response;
    }

    public function delete_quotation_detail(int $id)
    {
        $query = "DELETE FROM quotation_detail WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        if ($statement->execute()) {
            $this->response = true;
        }

        return $this->response;
    }

    public function search($title, string $name, string $date_quo, string $cus_name)
    {
        $bind_types = "";
        $params = array();

        $query = "SELECT *
            FROM quotations 
            WHERE id > 0
        ";

        if (isset($title) && $title != "all") {
            $query .= " AND title = ?";
            $bind_types .= "i";
            array_push($params, $title);
        }

        if (!empty($name)) {
            $query .= " AND name LIKE ?";
            $bind_types .= "s";
            array_push($params, '%' . $name . '%');
        }

        if (!empty($date_quo) && ($date_quo != '0000-00-00')) {
            $query .= " AND date_quo = ?";
            $bind_types .= "s";
            array_push($params, $date_quo);
        }

        if (!empty($cus_name)) {
            $query .= " AND cus_name LIKE ?";
            $bind_types .= "s";
            array_push($params, '%' . $cus_name . '%');
        }

        $statement = $this->connection->prepare($query);
        !empty($bind_types) ? $statement->bind_param($bind_types, ...$params) : '';
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }
}
