<?php

class Users extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        redirect('welcome');
    }

    public function login()
    {
        // Check if logged in
        if ($this->isLoggedIn()) {
            redirect('orders');
        }

        // Check if POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => '',
            ];

            // Check for name
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter a username.';
            }

            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter a password.';
            }

            // Check for user
            if (!$this->userModel->findUsername($data['username'])) {
                $data['username_err'] = 'This username is not registered.';
            }

            // Make sure errors are empty
            if (empty($data['username_err']) && empty($data['password_err'])) {

                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                if ($loggedInUser) {
                    // User Authenticated!
                    $this->createUserSession($loggedInUser);

                } else {
                    $data['password_err'] = 'Password incorrect.';
                    // Load View
                    $this->view('users/login', $data);
                }

            } else {
                // Load View
                $this->view('users/login', $data);
            }

        } else {
            // If NOT a POST

            // Init data
            $data = [
                'username' => '',
                'password' => '',
                'username_err' => '',
                'password_err' => '',
            ];

            // Load View
            $this->view('users/login', $data);
        }
    }

    // Create Session With User Info
    public function createUserSession($user)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = ucwords($user->username);
        redirect('orders');
    }

    // Logout & Destroy Session
    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['username']);
        session_destroy();
        redirect('users/login');
    }

    // Check Logged In
    public function isLoggedIn()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }
}