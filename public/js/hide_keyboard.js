function inputOnFocus() {
    input.readOnly = true;
    setTimeout(function() {
        input.readOnly = false;
    }, 100);
}

if (input !== null) input.focus();