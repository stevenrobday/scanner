<?php

class Reports extends Controller
{
    private $shipStation, $reportModel, $orderModel, $userModel;

    public function __construct()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        } elseif ($_SESSION['username'] != 'xxxx') {
            redirect('orders');
        }
        // Load Models
        $this->shipStation = $this->api('ShipStation');
        $this->reportModel = $this->model('Report');
        $this->orderModel = $this->model('Order');
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        $users = $this->userModel->getStandardUsers();
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
            $action = $_GET['action'] ?? 'getOrders';
            switch ($action) {
                case 'getOrders':
                    $date = new DateTime();
                    $date->setTimezone(new DateTimeZone('America/Phoenix'));
                    $rows = $this->reportModel->getOrders($date);

                    $_SESSION['rows'] = serialize($rows);

                    $data = [
                        'users' => $users,
                        'rows' => $rows,
                        'datepicker' => $date->format('m/d/Y'),
                        'modalDisplay' => "display: none"
                    ];
                    break;
                case 'searchOrders':
                    $datepicker = $_GET['datepicker'] ?? '';
                    $user_id = $_GET['users'] ?? '';
                    $orderNumber = $_GET['orderNumber'] ?? '';

                    $date = !empty($datepicker) ? DateTime::createFromFormat('m/d/Y', $datepicker) : '';

                    $rows = $this->reportModel->getOrders($date, $user_id, $orderNumber);

                    $_SESSION['rows'] = serialize($rows);

                    $username = !empty($user_id) ? $this->userModel->getUsernameById($user_id) : '';

                    $data = [
                        'users' => $users,
                        'rows' => $rows,
                        'username' => $username,
                        'datepicker' => $datepicker,
                        'orderNumber' => $orderNumber,
                        'modalDisplay' => "display: none"
                    ];
                    break;
                case 'getOrder':
                    $orderNumber = $_GET['orderNumber'];
                    $order = $this->orderModel->getOrderJson($orderNumber);
                    if (!$order) {
                        $orders = $this->shipStation->searchForOrder($orderNumber);
                        $length = count($orders['orders']);
                        for ($i = 0; $i < $length; $i++) {
                            if ($orders['orders'][$i]['orderNumber'] == $orderNumber) {
                                $order = $orders['orders'][$i];
                                break;
                            }
                        }

                        if (isset($order)) $this->orderModel->insertLocalOrder($orderNumber, json_encode($order));
                    }
                    $data = [
                        'users' => $users,
                        'rows' => unserialize($_SESSION['rows']),
                        'order' => $order,
                        'modalDisplay' => "display: block",
                        'hiddenOverflow' => "style='overflow: hidden;'"
                    ];
                    break;
            }
            $this->view('reports/index', $data);
        }
    }
}
