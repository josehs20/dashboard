function marcaTodasLojas() {
    var todas = document.getElementById('todasLojas');
    var divLojas = document.getElementById('divLojas').querySelectorAll('input');
    if (todas.checked) {
        divLojas.forEach(element => {
            console.log(element.checked = true);
        });

    } else {
        divLojas.forEach(element => {
            console.log(element.checked = false);
        });
    }
}

function desmarcaTodas() {
    var divLojas = document.getElementById('divLojas').querySelectorAll('input');
    var inputsC = divLojas.length
    var count = 0
    
    divLojas.forEach(element => {
        if (element.checked) {
            count++
        } 
    });
    
    if (inputsC === count) {
        document.getElementById('todasLojas').checked = true
    }else{
        document.getElementById('todasLojas').checked = false
    }

}
$(function (){
    desmarcaTodas()
})

