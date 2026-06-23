<script>
document.addEventListener('DOMContentLoaded', function() {
    // Detect dark mode
    function isDark() {
        return document.documentElement.classList.contains('dark');
    }

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
        background: isDark() ? '#1E293B' : '#fff',
        color: isDark() ? '#E2E8F0' : '#1E293B',
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

    // Dark-aware Swal dialog helper
    function swalDark(options) {
        const dark = isDark();
        return Swal.fire(Object.assign({
            background: dark ? '#1E293B' : '#fff',
            color: dark ? '#E2E8F0' : '#1E293B',
        }, options));
    }

    // Confirm delete forms
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const message = this.dataset.confirm;
            const form = this.closest('form');
            swalDark({
                title: 'Konfirmasi',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#DC2626',
                cancelButtonColor: isDark() ? '#475569' : '#64748B',
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
            swalDark({
                title: 'Logout',
                text: 'Yakin ingin keluar dari akun?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0284C7',
                cancelButtonColor: isDark() ? '#475569' : '#64748B',
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
