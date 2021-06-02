$( "#datepicker" ).datepicker({});

$(".orderNumSelect").on('click', function(e){
    var form = document.getElementById("getOrder");
    var orderNumber = document.createElement("input");

    orderNumber.name = "orderNumber";
    orderNumber.value = this.textContent;

    form.appendChild(orderNumber);
    form.submit();
});

$('#modalWrap, #closeButton').on('click', closeModal);

function closeModal(e) {
    if (e.target.id == "modal") return false;
    document.getElementById("modalWrap").style.display = 'none';
    document.documentElement.style.overflow = "visible";
    document.body.style.overflow = "visible";
}

document.addEventListener('keyup', function(e) {
    if (e.key == "Escape") {
        document.getElementById("modalWrap").style.display = 'none';
        document.documentElement.style.overflow = "visible";
        document.body.style.overflow = "visible";
    }
});