<?php
class Cron extends Controller
{
    private $shipStation, $orderModel;

    public function __construct()
    {
        // Load Models
        $this->shipStation = $this->api('ShipStation');
        $this->orderModel = $this->model('Order');
    }

    public function index()
    {
        // get orders awaiting shipment from ShipStation
        $orders = $this->shipStation->getNewOrders();
        if (isset($orders['orders'])) {
            $length = count($orders['orders']);
            for ($i = 0; $i < $length; $i++) {
                $orderNumber = $orders['orders'][$i]['orderNumber'];
                if (!$this->orderModel->findLocalOrder($orderNumber)) {
                    $order = $orders['orders'][$i];
                    $order = json_encode($order);
                    $this->orderModel->insertLocalOrder($orderNumber, $order);
                }
            }
        }
    }
}