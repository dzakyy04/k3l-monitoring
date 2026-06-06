<script>
document.addEventListener('DOMContentLoaded', function() {
    // Custom SweetAlert defaults matching our theme
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
        customClass: {
            popup: 'swal-toast-custom'
        }
    });

    // Session flash messages
    @if(session('success'))
        Toast.fire({ icon: 'success', title: @js(session('success')) });
    @endif

    @if(session('error'))
        Toast.fire({ icon: 'error', title: @js(session('error')) });
    @endif

    @if(session('warning'))
        Toast.fire({ icon: 'warning', title: @js(session('warning')) });
    @endif

    @if(session('info'))
        Toast.fire({ icon: 'info', title: @js(session('info')) });
    @endif

    @if(session('status') === 'profile-updated')
        Toast.fire({ icon: 'success', title: 'Profil berhasil diperbarui.' });
    @endif

    @if(session('status') === 'password-updated')
        Toast.fire({ icon: 'success', title: 'Password berhasil diperbarui.' });
    @endif

    @if(session('status') === 'verification-link-sent')
        Toast.fire({ icon: 'success', title: 'Link verifikasi baru telah dikirim ke email Anda.' });
    @endif

    // Confirm delete forms
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const message = this.dataset.confirm;
            const form = this.closest('form');
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
        });
    });

    // Confirm logout forms
    document.querySelectorAll('[data-confirm-logout]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Logout',
                text: 'Yakin ingin keluar dari akun?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0284C7',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Ya, logout',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
        });
    });
});
</script>
