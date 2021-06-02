<!DOCTYPE html>
<html lang="en" <?php if (isset($data['hiddenOverflow'])) echo $data['hiddenOverflow'];?>>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/main.css">
  <?php if(isset($_SESSION['user_id'])) {
          if ($_SESSION['username'] == 'xxxx') { ?>
            <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/reports.css">
          <?php } else { ?>    
            <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/scanner.css">
          <?php } 
        } ?>
  <title><?php echo SITENAME; ?></title>
</head>
<body <?php if (isset($data['hiddenOverflow'])) echo $data['hiddenOverflow'];?>>