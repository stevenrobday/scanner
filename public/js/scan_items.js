var okBtnClick;
var skuArray = Array();
var input = document.getElementById('skuToFind');
var itemQty = document.querySelectorAll('.item').length;
var scannedItems = document.querySelectorAll('.isScanned');
scannedItems.forEach(e => skuArray.push(e.getAttribute('data-sku')));
var scanCount = 0;
if (skuArray.length) scanCount = skuArray.length;

function findSku() {
    var sku = input.value;
    var el = document.querySelectorAll(`[data-sku='${sku}']`)[0];
    var modal = document.getElementById('modal');
    var modalInner = document.createElement("div");
    var skuLine = document.createElement("div");
    var msg = document.createElement("div");
    var btnWrap = document.createElement("div");
    var btn = document.createElement("button");

    skuLine.id = "skuLine";
    skuLine.innerText = sku;
    msg.className = "msg";
    modalInner.appendChild(skuLine);
    btnWrap.id = "btnWrap";
    btn.id = "okBtn";
    btn.innerText = "OK";

    if (el === undefined) {
        msg.innerHTML = "Item not found!<br/>Sorry, try again.";
        modal.style.backgroundColor = "#fbd9db";
        btn.style.backgroundColor = "#a0001d";
        okBtnClick = () => okBtnHandler(el, false);
    }
    else if (!skuArray.includes(sku)) {
        skuArray.push(sku);
        msg.innerHTML = "Item found!<br/>Take " + el.getAttribute('data-qty') + " from inventory.";
        modal.style.backgroundColor = "#d8f7c6";
        btn.style.backgroundColor = "#007728";
        scanCount++;
        var override = el.querySelector('.override');
        override.style.display = "none";
        okBtnClick = () => okBtnHandler(el);
    }
    else {
        msg.innerHTML = "Item already scanned!<br/>Take " + el.getAttribute('data-qty') + " from inventory.";
        modal.style.backgroundColor = "#fffac5";
        btn.style.backgroundColor = "#c5963b";
        okBtnClick = () => okBtnHandler(el, false);
    }

    btn.addEventListener('click', okBtnClick);

    modalInner.appendChild(msg);
    btnWrap.appendChild(btn);
    modalInner.appendChild(btnWrap);
    modal.innerHTML = "";
    modal.appendChild(modalInner);
    document.documentElement.style.overflow = "hidden";
    document.body.style.overflow = "hidden";
    document.getElementById("modalShade").style.display = "block";
    document.getElementById("modalWrap").style.display = "block";
}

function okBtnHandler(el, addScanned = true) {
    var btn = document.getElementById('okBtn');
    if (addScanned) el.classList.add("isScanned");
    btn.removeEventListener('click', okBtnClick);
    document.getElementById('skuToFind').value = "";
    document.documentElement.style.overflow = "visible";
    document.body.style.overflow = "visible";
    document.getElementById("modalShade").style.display = "none";
    document.getElementById("modalWrap").style.display = "none";
    input.value = "";
    input.focus();
    if (itemQty !== 0 && scanCount === itemQty) completeOrder();
}

function cancelBtnClick() {
    var btn = document.getElementById('cancelBtn');
    btn.removeEventListener('click', cancelBtnClick);
    document.documentElement.style.overflow = "visible";
    document.body.style.overflow = "visible";
    document.getElementById("modalShade").style.display = "none";
    document.getElementById("modalWrap").style.display = "none";
    input.focus();
}

document.addEventListener('keydown', function(e) {
    if (e.key == "Enter") {
        findSku();
    }
});

// function showOrderCompleteModal() {
//     var modal = document.getElementById('modal');
//     var modalInner = document.createElement("div");
//     var msg = document.createElement("div");
//     var btnWrap = document.createElement("div");
//     var btn = document.createElement("button");

//     btnWrap.id = "btnWrap";
//     btn.id = "okBtn";
//     btn.innerText = "OK";
//     btn.addEventListener('click', completeOrder);

//     msg.className = "msg";
//     msg.innerHTML = "Order finished!<br/>Please click OK below to complete.";
//     msg.style.marginTop = "20px";
//     modal.style.backgroundColor = "#d8f7c6";
//     btn.style.backgroundColor = "#007728";

//     modalInner.appendChild(msg);
//     btnWrap.appendChild(btn);
//     modalInner.appendChild(btnWrap);
//     modal.innerHTML = "";
//     modal.appendChild(modalInner);
//     document.documentElement.style.overflow = "hidden";
//     document.body.style.overflow = "hidden";
//     document.getElementById("modalShade").style.display = "block";
//     document.getElementById("modalWrap").style.display = "block";
// }

function completeOrder() {
    //myInputButton.disabled = "disabled";
    document.getElementById('okBtn').style.display = "none";
    document.getElementById('completeOrder').submit();
}

function saveOrder() {
    if (skuArray.length) {
        document.getElementById('saveOrder').style.display = "none";
        var form = document.getElementById('saveOrderForm');
        var input = document.createElement("input");
        
        input.name="skus";
        input.type = "hidden";
        input.value = skuArray.join();        
        
        form.appendChild(input);
        form.submit();
    }
}

function override(el) {
    el.style.display = "none";
    parentEl = el.parentElement.parentElement;
    var sku = parentEl.getAttribute('data-sku');
    var modal = document.getElementById('modal');
    var modalInner = document.createElement("div");
    var skuLine = document.createElement("div");
    var msg = document.createElement("div");
    var btnWrap = document.createElement("div");
    var btn = document.createElement("button");

    skuLine.id = "skuLine";
    skuLine.innerText = sku;
    msg.className = "msg";
    modalInner.appendChild(skuLine);
    btnWrap.id = "btnWrap";
    btn.id = "okBtn";
    btn.innerText = "OK";
    okBtnClick = () => okBtnHandler(parentEl);
    btn.addEventListener('click', okBtnClick);

    skuArray.push(sku);
    msg.innerHTML = "Item verified!<br/>Take " + parentEl.getAttribute('data-qty') + " from inventory.";
    modal.style.backgroundColor = "#d8f7c6";
    btn.style.backgroundColor = "#007728";
    scanCount++;

    modalInner.appendChild(msg);
    btnWrap.appendChild(btn);
    modalInner.appendChild(btnWrap);
    modal.innerHTML = "";
    modal.appendChild(modalInner);
    document.documentElement.style.overflow = "hidden";
    document.body.style.overflow = "hidden";
    document.getElementById("modalShade").style.display = "block";
    document.getElementById("modalWrap").style.display = "block";
}

function changeQty(el) {
    parentEl = el.parentElement.parentElement;
    var sku = parentEl.getAttribute('data-sku');
    var modal = document.getElementById('modal');
    var modalInner = document.createElement("div");
    var skuLine = document.createElement("div");
    var msg = document.createElement("div");
    var inputLine = document.createElement("div");
    var inputQty = document.createElement("input");
    var btnWrap = document.createElement("div");
    var cancelBtn = document.createElement("button");
    var okBtn = document.createElement("button");

    skuLine.id = "skuLine";
    skuLine.innerText = sku;
    modalInner.appendChild(skuLine);
    msg.className = "msg";
    msg.innerHTML = "Change quantity of item:";
    modalInner.appendChild(msg);
    inputLine.id = "inputLine";
    inputQty.setAttribute('size', '2');
    inputQty.id = "inputQty";
    inputLine.appendChild(inputQty);
    modalInner.appendChild(inputLine);
    btnWrap.id = "btnWrap";
    okBtn.id = "okQtyBtn";
    okBtn.innerText = "OK";
    okBtn.addEventListener('click', submitQtyChange);

    cancelBtn.id = "cancelBtn";
    cancelBtn.innerText = "Cancel";
    cancelBtn.addEventListener('click', cancelBtnClick);

    modal.style.backgroundColor = "#fffac5";
    okBtn.style.backgroundColor = "#c5963b";
    cancelBtn.style.backgroundColor = "#c5963b";

    btnWrap.appendChild(cancelBtn);
    btnWrap.appendChild(okBtn);
    modalInner.appendChild(btnWrap);
    modal.innerHTML = "";
    modal.appendChild(modalInner);
    document.documentElement.style.overflow = "hidden";
    document.body.style.overflow = "hidden";
    document.getElementById("modalShade").style.display = "block";
    document.getElementById("modalWrap").style.display = "block";
    inputQty.focus();
    inputQty.readOnly = true;
    setTimeout(function() {
        inputQty.readOnly = false;
    }, 100);
}

function submitQtyChange() {
    var form = document.getElementById('saveOrderForm');
    var action = document.createElement("input");
    var skuInput = document.createElement("input");
    var qtyInput = document.createElement("input");

    var sku = document.getElementById('skuLine').innerText;
    var qty = document.getElementById('inputQty').value;

    document.getElementById('okQtyBtn').disabled = "disabled";
    document.getElementById('cancelBtn').disabled = "disabled";

    action.name= "action";
    action.type = "hidden";
    action.value = "changeQty";  

    skuInput.name= "sku";
    skuInput.type = "hidden";
    skuInput.value = sku; 

    qtyInput.name= "qty";
    qtyInput.type = "hidden";
    qtyInput.value = qty; 

    form.appendChild(action);
    form.appendChild(skuInput);
    form.appendChild(qtyInput);

    if (skuArray.length) saveOrder();
    else form.submit();
}