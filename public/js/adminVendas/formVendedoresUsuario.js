function desmarcaLojaDupla(value) {

    var divLojas = document.getElementById('divLojas').querySelectorAll('input');

    divLojas.forEach(element => {
        if (element.id != value.id) {
            element.checked = false
        }
    });
}


