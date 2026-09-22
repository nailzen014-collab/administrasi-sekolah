/* Konfirmasi & notifikasi CRUD memakai SweetAlert2.
   - Form hapus: cukup tambahkan atribut data-confirm, data-confirm-title, data-confirm-text.
   - Flash success/error: ditampilkan dari elemen #flash-data (data-success / data-error).
 */

(function () {
    'use strict';

    // Konfirmasi hapus lewat SweetAlert
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (form.matches && form.matches('form[data-confirm]')) {
            e.preventDefault();
            var title = form.getAttribute('data-confirm-title') || 'Hapus data';
            var text = form.getAttribute('data-confirm-text') || 'Yakin ingin menghapus data ini? Aksi tidak dapat dibatalkan.';
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ff6a1a',
                cancelButtonColor: '#3a312b',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });

    // Flash session -> toast SweetAlert
    var flash = document.getElementById('flash-data');
    if (flash) {
        var success = flash.getAttribute('data-success');
        var error = flash.getAttribute('data-error');
        if (success || error) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: success ? 'success' : 'error',
                title: success || error,
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        }
    }
})();