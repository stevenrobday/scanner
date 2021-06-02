<?php require APPROOT . '/views/inc/header.php'; ?>
<div id="header">
    <div>
        Welcome <b><?php echo htmlspecialchars($_SESSION["username"]); ?>!</b>
    </div>
    <div>
        <a href="<?php echo URLROOT; ?>/users/logout">Sign Out</a>
    </div>
</div>
<div id="aboveInput">
    Please scan a packing slip.
</div>
<form action="<?php echo URLROOT; ?>/orders/order" id="searchOrderForm" method="get">
    <input type="text" id="orderNumber" name="orderNumber" onfocus="inputOnFocus()">
</form>
<?php if (!empty($data['msg'])) { ?>
    <div id="orderMsg"><?php echo $data['msg']; ?></div>
<?php } ?>
<?php if (!empty($data['filename'])) { ?>
    <div style="display: none" id="printLabel" data-filename="<?php echo $data['filename']; ?>"></div>
<?php } ?>
<script src="<?php echo URLROOT; ?>/js/search_for_order.js"></script>
<script src="<?php echo URLROOT; ?>/js/hide_keyboard.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>