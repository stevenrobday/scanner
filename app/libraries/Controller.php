<?php
  /* 
   *  CORE CONTROLLER CLASS
   *  Loads Models & Views
   */
  class Controller {
    // Lets us load model from controllers
    public function model($model){
      // Require model file
      require_once '../app/models/' . $model . '.php';
      // Instantiate model
      return new $model();
    }

    public function api($apiName){
      // Require model file
      require_once '../app/api/' . $apiName . '.php';
      // Instantiate model
      return new $apiName();
    }

    // Lets us load view from controllers
    public function view($view, $data = []){
      // Check for view file
      if(file_exists('../app/views/'.$view.'.php')){
        // Require view file
        require_once '../app/views/'.$view.'.php';
      } else {
        // No view exists
        die('View does not exist');
      }
    }
  }