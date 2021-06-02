<?php

class Orders extends Controller
{
    private $shipStation, $orderModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        } elseif ($_SESSION['username'] == 'xxxx') {
            redirect('reports');
        }
        // Load Models
        $this->shipStation = $this->api('ShipStation');
        $this->orderModel = $this->model('Order');
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $orderNumber = $_POST['orderNumber'];
            $user_id = $_SESSION['user_id'];
            $order = $_SESSION['order'];

            unset($_SESSION['order']);

            $data = [
                'msg' => '',
                'filename' => ''
            ];

            if (!$this->orderModel->findCompletedOrder($orderNumber)) {
                $date = new DateTime();
                $date->setTimezone(new DateTimeZone('America/Phoenix'));
                $createdAt = $date->format('Y-m-d H:i:s');
                $this->orderModel->insertCompletedOrder($user_id, $orderNumber, $createdAt);
                $this->orderModel->deleteSavedOrder($orderNumber);
                if ($order['weight']['value'] == 0) {
                    $message = "Hey xxxx,\r\n\r\n";
                    $message .= "Order# $orderNumber has a weight of zero\r\n\r\n";
                    $message .= "Please add a weight for the following items:\r\n\r\n";

                    $items = $order['items'];
                    foreach ($items as $item) {
                        $message .= "{$item['sku']} {$item['name']}\r\n";
                    }

                    mail("xxxx@xxxx.com", "Order# $orderNumber has a weight of zero", $message, "From: it@fivepints.com");

                    $data['msg'] = "Order# $orderNumber has a weight of zero. xxxx has been notified. Please weigh the order and print the label from ShipStation.";
                } else {
                    $shipDate = new DateTime();
                    $shipDate->setTimezone(new DateTimeZone('America/Phoenix'));
                    $shipDate = $shipDate->format('Y-m-d');
                    $insuranceOptions = $order['insuranceOptions']['insureShipment'] ? $order['insuranceOptions'] : null;
                    $internationalOptions = $order['internationalOptions']['contents'] ? $order['internationalOptions'] : null;
                    $json = array(
                        "orderId" => $order['orderId'],
                        "carrierCode" => "{$order['carrierCode']}",
                        "serviceCode" => "{$order['serviceCode']}",
                        "packageCode" => "{$order['packageCode']}",
                        "confirmation" => "{$order['confirmation']}",
                        "shipDate" => "{$shipDate}",
                        "weight" => $order['weight'],
                        "dimensions" => null,
                        "insuranceOptions" => $insuranceOptions,
                        "internationalOptions" => $internationalOptions,
                        "advancedOptions" => $order['advancedOptions'],
                        "testLabel" => true
                    );
                    $filename = $this->shipStation->getLabel($json, $orderNumber);
                    if (!$filename) {
                        $data['msg'] = "Error generating label. Please make a note of the order number and use ShipStation to print.";
                        $data['filename'] = '';
                    } else {
                        $data['filename'] = $filename;
                        $data['msg'] = "Order# $orderNumber complete. Please open shipping label from downloads to print.";
                    }
                }
            }
        } else {
            $data = [
                'msg' => '',
                'filename' => ''
            ];
        }
        $this->view('orders/index', $data);
    }

    public function order()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
            $orderNumber = $_GET['orderNumber'];

            if ($this->orderModel->findCompletedOrder($orderNumber)) {
                $data = [
                    'msg' => "Order #$orderNumber already completed.",
                    'filename' => ''
                ];
                $this->view('orders/index', $data);
            } else {
                $order = $this->orderModel->getOrderJson($orderNumber);
                if ($order) {
                    $_SESSION['order'] = $order;
                    $skus = $this->orderModel->getSavedOrder($orderNumber);

                    $data = [
                        'order' => $order,
                        'skus' => $skus,
                        'orderNumber' => $orderNumber
                    ];
                    $this->view('orders/order', $data);
                } else {
                    $orders = $this->shipStation->searchForOrder($orderNumber);
                    if (isset($orders['orders'])) {
                        $length = count($orders['orders']);
                        for ($i = 0; $i < $length; $i++) {
                            if ($orders['orders'][$i]['orderNumber'] == $orderNumber) {
                                $order = $orders['orders'][$i];
                                $_SESSION['order'] = $order;
                                break;
                            }
                        }

                        if (isset($order) && $order) {
                            $this->orderModel->insertLocalOrder($orderNumber, json_encode($order));
                            $skus = $this->orderModel->getSavedOrder($orderNumber);

                            $data = [
                                'order' => $order,
                                'skus' => $skus,
                                'orderNumber' => $orderNumber
                            ];
                            $this->view('orders/order', $data);
                        } else {
                            $data = [
                                'msg' => "Order #$orderNumber not found.",
                                'filename' => ''
                            ];
                            $this->view('orders/index', $data);
                        }
                    } else {
                        $data = [
                            'msg' => "Order #$orderNumber not found.",
                            'filename' => ''
                        ];
                        $this->view('orders/index', $data);
                    }
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $orderNumber = $_POST['orderNumber'];
            $action = $_POST['action'] ?? 'saveOrder';
            $skus = $_POST['skus'] ?? '';
            $order = $_SESSION['order'];

            if (!empty($skus)) $this->orderModel->addSavedOrder($orderNumber, $skus);

            if ($action == 'changeQty') {
                $sku = $_POST['sku'];
                $qty = $_POST['qty'];

                $length = count($order['items']);
                for ($i = 0; $i < $length; $i++) {
                    if ($sku == $order['items'][$i]['sku']) {
                        $order['items'][$i]['quantity'] = $qty;
                        break;
                    }
                }

                $order = $this->shipStation->updateOrder($order);

                $orderEncoded = json_encode($order);
                if (!$this->orderModel->findLocalOrder($orderNumber)) $this->orderModel->insertLocalOrder($orderNumber, $orderEncoded);
                else $this->orderModel->updateLocalOrder($orderNumber, $orderEncoded);
            }

            $data = [
                'order' => $order,
                'skus' => $skus,
                'orderNumber' => $orderNumber
            ];
            $this->view('orders/order', $data);
        }
    }
}
