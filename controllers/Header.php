<?php
require_once __DIR__ . '/DB.php';

class Header extends DB
{
    public $response = "false";

    public function __construct()
    {
        parent::__construct();
    }

    public function getvalue()
    {
        $query = "SELECT bookings.id as id,
            product_category.transfer as transfers, 
            -- product_category.boat as boats,
            booking_transfer.pickup_type as pickup_type,
            booking_order_boat.id as boat_id,
            booking_order_transfer.id as transfer_id
        FROM bookings
        LEFT JOIN booking_products
            ON bookings.id = booking_products.booking_id
        LEFT JOIN booking_product_rates
            ON booking_products.id = booking_product_rates.booking_products_id	
        LEFT JOIN product_category
            ON booking_product_rates.category_id = product_category.id
        LEFT JOIN booking_order_boat
            ON bookings.id = booking_order_boat.booking_id
        LEFT JOIN booking_transfer
            ON booking_products.id = booking_transfer.booking_products_id
        LEFT JOIN booking_order_transfer
            ON booking_transfer.id = booking_order_transfer.booking_transfer_id	
        WHERE bookings.id > 0
        AND bookings.booking_status_id != 3
        AND bookings.booking_status_id != 4
        AND booking_products.is_deleted = 0
        AND (booking_order_boat.id IS NULL OR booking_order_transfer.id IS NULL)
        AND (booking_products.travel_date = '" . date("Y-m-d") . "' OR booking_products.travel_date = '" .  date("Y-m-d", strtotime(" +1 day"))  . "')
        ";

        $statement = $this->connection->prepare($query);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        return $data;
    }
}
