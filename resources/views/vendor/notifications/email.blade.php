@component('mail::message')
{{-- Header Custom --}}
<table width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td style="text-align: center;">
            <img src="https://marsip.bbwscitanduy.id/img/citanduy.png" alt="Logo M-Arsip" width="90"
                style="margin-bottom: 20px;">
        </td>
    </tr>
</table>

# 🔐 Reset Password M-Arsip

Halo {{ $user->name ?? 'Pengguna' }},

Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda di **Aplikasi M-Arsip**.
Silakan klik tombol di bawah untuk melanjutkan proses reset password:

@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
Reset Password
@endcomponent

Link ini akan **berlaku selama 60 menit**.
Jika Anda tidak meminta reset password, abaikan saja email ini — akun Anda tetap aman.

Terima kasih,
**Admin Aplikasi M-Arsip**

<hr>

@slot('subcopy')
Jika tombol di atas tidak berfungsi, salin dan buka tautan berikut di browser Anda:<br>
[{{ $actionUrl }}]({{ $actionUrl }})
@endslot
@endcomponent