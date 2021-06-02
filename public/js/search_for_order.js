var input = document.getElementById('orderNumber');

document.addEventListener('keydown', function(e) {
//usually scanners throw an 'Enter' key at the end of read
    var form = document.getElementById("searchOrderForm");
    if (form != null && e.key == "Enter") {
        form.submit(); 
    }
});

printLabel();

function printLabel() {
    var printLabel = document.getElementById('printLabel');
    if (printLabel != null && printLabel != undefined) {
        var filename = printLabel.getAttribute('data-filename');
        window.open("../" + filename, "_blank");
    }
}