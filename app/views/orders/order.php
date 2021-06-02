<?php require APPROOT . '/views/inc/header.php'; ?>
<div id="topBar">
    <input id="skuToFind" onfocus="inputOnFocus()" type="text">
</div>
<div id="items">
<?php 
$order = $data['order'];
$skus = $data['skus'];
if (!empty($skus)) {
    $skus = explode(",", $skus);
    $skus = array_flip($skus);
}
$length = count($order['items']);
for($i = 0; $i < $length; $i++){
    $border = "";
    if ($i !== 0) $border = "noTop";
    $sku = $order['items'][$i]['sku'];
    $qty = $order['items'][$i]['quantity'];

    $isScanned = "";
    if (!empty($sku)) {
        if (!empty($skus)) {
            if (array_key_exists($sku, $skus))
                $isScanned = "isScanned";
        }
?>
    <div class="item <?php echo $border . " " . $isScanned; ?>" data-sku="<?php echo $sku; ?>" data-qty="<?php echo $qty; ?>">
        <div class="imgContainer">
            <div class="imgWrap">
                <img class="itemImage" src="<?php echo $order['items'][$i]['imageUrl'] ?>">
            </div>
        </div>
        <div class="itemInfo">
            <div>
                <?php echo $order['items'][$i]['name']; ?>
            </div>
            <div class="sku">
                <?php echo $sku; ?>
            </div>
        </div>
        <div class="qtyWrap">
            <button class="qtyBtns changeQty" onclick="changeQty(this);">CHANGE</button>
            <div class="qty">
                <?php echo $qty; ?>
            </div>
            <?php if (empty($isScanned)) { ?>
                <button class="qtyBtns override" onclick="override(this);">VERIFY</button>
            <?php } ?>
        </div>
    </div>    
<?php } } ?>
</div>
<form id="completeOrder" action="<?php echo URLROOT; ?>/orders/index" method="post" style="display: none">
        <input type="hidden" name="orderNumber" value="<?php echo $data['orderNumber']; ?>">
</form>
<form id="saveOrderForm" action="<?php echo URLROOT; ?>/orders/order" method="post" style="display: none">
        <input type="hidden" name="orderNumber" value="<?php echo $data['orderNumber']; ?>">
</form>
<div id="saveOrderWrap">
    <button id="saveOrder" onclick="saveOrder();">SAVE</button>
</div>
<div id="modalShade">
</div>
<div id="modalWrap">
    <div id="modal"></div>
</div>
<script src="<?php echo URLROOT; ?>/js/scan_items.js"></script>
<script src="<?php echo URLROOT; ?>/js/hide_keyboard.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>