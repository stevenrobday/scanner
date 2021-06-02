<?php require APPROOT . '/views/inc/header.php'; ?>
<div id="headerWrap">
  <div id="header">
    <div>
      Welcome, <b><?php echo $_SESSION["username"]; ?></b>
    </div>
    <div>
      <a href="<?php echo URLROOT; ?>/users/logout">(Sign Out)</a>
    </div>
  </div>
</div>
<form id="searchOrders" action="" method="get">
  <input type="hidden" name="action" value="searchOrders">
  <div>
    <input placeholder="Date" type="text" name="datepicker" id="datepicker">
  </div>
  <div>
    <select name="users" id="users">
      <option disabled selected hidden>User</option>
      <?php $users = $data['users'];
        foreach($users as $user) {
          ?><option value="<?php echo $user->id; ?>"><?php echo ucwords($user->username); ?></option><?php
        } 
      ?>
    </select>
  </div>
  <div>
    <input placeholder="Order Number" type="text" name="orderNumber" id="orderNumber">
  </div>
  <div>
    <button id="search">SEARCH</button>
  </div>
</form>
<?php
if (!empty($data['rows'])) {
  $rows = $data['rows'];
  ?>
  <div id="results">
    <div class="rowWrap">
      <div class="row">
        <span class="user"><b>User</b></span>
        <span class="orderNum"><b>Order Number</b></span>
        <span class="date"><b>Date</b></span>
      </div>
    </div>
    <?php
    foreach ($rows as $row) { ?>
      <div class="rowWrap">
        <div class="row">
          <span class="user"><?php echo ucwords($row->username); ?></span>
          <span class="orderNum">
            <span class="orderNumSelect"><?php echo $row->order_number; ?></span>
          </span>
          <span class="date"><?php echo $row->created_at;  ?></span>
        </div>
      </div>
    <?php
    } ?>
  </div>
  <form id="getOrder" action="" method="get" style="display: none;">
    <input type="hidden" name="action" value="getOrder">
  </form>
<?php
} else { ?>
  <div id="noResults">
    No results found<?php
      if (isset($data['datepicker']) && !empty($data['datepicker'])) {
        echo " on " . $data['datepicker'];
      }
      if (isset($data['username']) && !empty($data['username'])) {
        echo " for " . $data['username'];
        if (isset($data['orderNumber']) && !empty($data['orderNumber'])) echo " and Order# " . $data['orderNumber'];
      } elseif (isset($data['orderNumber']) && !empty($data['orderNumber'])) echo " for Order# " . $data['orderNumber'];
    ?>.
  </div>
<?php
}
?>
<div id="modalWrap" style="<?php echo $data['modalDisplay']; ?>">
  <div id="modal">
    <?php
    if ($data['order']) {
      $order = $data['order'];
    ?>
      <div id="orderInfo">
        <div id="col1">
          <div>
            <b>Order Number: </b><?php echo $order['orderNumber']; ?>
          </div>
          <div>
            <?php
            if ($order['shipDate'] != "") {
              $datetime = new DateTime($order['shipDate']);
              echo "<b>Ship date:</b> " . $datetime->format('m/d/Y');
            }
            ?>
          </div>
        </div>
        <div id="col2">
          <div>
            <?php echo $order['shipTo']['name']; ?>
          </div>
          <div>
            <?php echo $order['shipTo']['street1']; ?>
          </div>
          <div>
            <?php
            $street2 = $order['shipTo']['street2'];
            if (isset($street2) && $street2 != '') echo $street2;
            ?>
          </div>
          <div>
            <?php
            $street3 = $order['shipTo']['street2'];
            if (isset($street3) && $street3 != '') echo $street3;
            ?>
          </div>
          <div>
            <?php echo $order['shipTo']['city']; ?>
            <?php echo ", " . $order['shipTo']['state']; ?>
            <?php echo " " . $order['shipTo']['postalCode']; ?>
          </div>
        </div>
        <div id="col3">
          <div>
            <?php echo "<b>Shipping amount:</b> $" . $order['shippingAmount']; ?>
          </div>
          <div>
            <?php echo "<b>Total price:</b> $" . $order['orderTotal']; ?>
          </div>
          <div>
            <?php echo "<b>Weight:</b> " . $order['weight']['value'] . " " . $order['weight']['units']; ?>
          </div>
        </div>
      </div>
      <br style="clear: both;" />
      <div id="itemsWrap">
        <?php
        $length = count($order['items']);
        for ($i = 0; $i < $length; $i++) {
          $border = "";
          if ($i !== 0) $border = "noTop";
          $sku = $order['items'][$i]['sku'];
          $qty = $order['items'][$i]['quantity'];
          $price = $order['items'][$i]['unitPrice'];

        ?>
          <div class="item <?php echo $border; ?>" data-sku="<?php echo $sku; ?>" data-qty="<?php echo $qty; ?>">
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
              <div class="qty">
                $<?php echo $price; ?> x<?php echo $qty; ?>
              </div>
            </div>
          </div>
        <?php
        } ?>
      </div>
    <?php
    } else { ?>
      <div id="orderInfo">Order not found!</div>
    <?php } ?>
  </div>
</div>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="<?php echo URLROOT; ?>/js/reports.js"></script>
<?php require APPROOT . '/views/inc/footer.php'; ?>