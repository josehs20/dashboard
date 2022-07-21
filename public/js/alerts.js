
function aletMessage(msg, tipo, texto) {
  Swal.fire({
    position: 'top-end',
    icon: tipo,
    title: msg,
    text: texto,
    showConfirmButton: false,
    timer: 2500
  })
}


function confirmDelete(e,formId, text, icon) {
  e.preventDefault();
  Swal.fire({
    text: text,
    icon: icon,
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, excluir!'
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById(formId).submit();
    }
  })

}