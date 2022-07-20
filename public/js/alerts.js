
function aletMessage(msg, tipo, texto){
    Swal.fire({
        position: 'top-end',
        icon: tipo,
        title: msg,
        text: texto,
        showConfirmButton: false,
        timer: 2500
      })
}