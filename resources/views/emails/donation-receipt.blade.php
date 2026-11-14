@php
    $types = ['qris' => 'QRIS', 'bank_transfer' => 'Transfer bank (Virtual Account)', 'echannel' => 'Mandiri Bill', 'gopay' => 'GoPay', 'shopeepay' => 'ShopeePay', 'credit_card' => 'Kartu kredit/debit', 'cstore' => 'Gerai retail', 'akulaku' => 'Akulaku', 'dana' => 'DANA'];
    $method = $d->payment_type ? ($types[$d->payment_type] ?? ucfirst(str_replace('_', ' ', $d->payment_type))) : 'Midtrans';
    $when = ($d->paid_at ?? now())->timezone(config('app.timezone'))->translatedFormat('d F Y, H:i T');
    $rows = [
        ['No. kwitansi', $d->order_id],
        ['Tanggal', $when],
        ['Kampanye', $c->title],
        ['Penggalang', $c->organizer],
        ['Metode pembayaran', $method],
        ['Status', 'Lunas'],
    ];
    $url = route('campaigns.show', $c);
    $wa = 'https://wa.me/?text=' . urlencode('Yuk bantu: ' . $c->title . ' ' . $url);
@endphp
<!doctype html>
<html lang="id">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Terima kasih atas donasimu</title></head>
<body style="margin:0;background:#f4f4f5;font-family:Arial,Helvetica,sans-serif;color:#14161a;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f5;padding:24px 12px;">
<tr><td align="center">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#ffffff;border-radius:24px;overflow:hidden;">
    <tr><td style="background:#c4ee2f;padding:20px 28px;font-size:18px;font-weight:bold;">Sahabat Peduli</td></tr>
    @if($c->image_url)<tr><td><img src="{{ $c->image_url }}" width="560" alt="" style="display:block;width:100%;height:auto;border:0;"></td></tr>@endif
    <tr><td style="padding:28px;">
        <h1 style="margin:0 0 12px;font-size:24px;line-height:1.3;">Terima kasih, {{ $d->name }}.</h1>
        <p style="margin:0 0 20px;font-size:16px;line-height:1.6;color:#404040;">Donasimu untuk <b>{{ $c->title }}</b> sudah kami terima. Kebaikanmu sangat berarti bagi mereka yang dibantu.</p>

        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f5;border-radius:16px;padding:6px 18px;">
            <tr><td colspan="2" style="padding:14px 0 6px;font-size:12px;letter-spacing:1px;text-transform:uppercase;color:#737373;">Kwitansi donasi</td></tr>
            @foreach($rows as [$k, $v])
                <tr><td style="padding:8px 0;font-size:14px;color:#737373;vertical-align:top;width:40%;">{{ $k }}</td><td style="padding:8px 0;font-size:14px;font-weight:bold;text-align:right;">{{ $v }}</td></tr>
            @endforeach
            <tr><td colspan="2" style="border-top:1px solid #e5e5e5;padding:0;height:1px;"></td></tr>
            <tr><td style="padding:14px 0;font-size:15px;">Nominal donasi</td><td style="padding:14px 0;font-size:22px;font-weight:bold;text-align:right;color:#3b8eff;">Rp {{ number_format($d->amount, 0, ',', '.') }}</td></tr>
        </table>

        @if($d->message)
            <p style="margin:20px 0 0;padding:14px 16px;border-left:4px solid #c4ee2f;background:#fafafa;font-size:14px;line-height:1.6;color:#404040;"><b>Doa dan pesanmu:</b><br>{{ $d->message }}</p>
        @endif

        <h2 style="margin:28px 0 8px;font-size:16px;">Perkembangan kampanye</h2>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#e5e7eb;border-radius:99px;"><tr>
            <td width="{{ max(2, $c->percent) }}%" style="background:#3b8eff;height:10px;border-radius:99px;font-size:0;line-height:0;">&nbsp;</td>
            @if($c->percent < 100)<td style="font-size:0;line-height:0;">&nbsp;</td>@endif
        </tr></table>
        <p style="margin:8px 0 0;font-size:14px;color:#404040;"><b>Rp {{ number_format($c->collected, 0, ',', '.') }}</b> terkumpul dari Rp {{ number_format($c->target, 0, ',', '.') }} ({{ $c->percent }}%)</p>

        <table role="presentation" cellpadding="0" cellspacing="0" style="margin:24px 0 0;"><tr>
            <td style="background:#3b8eff;border-radius:99px;"><a href="{{ $url }}" style="display:inline-block;padding:14px 26px;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;">Lihat kampanye</a></td>
            <td width="10"></td>
            <td style="border:1px solid #d4d4d4;border-radius:99px;"><a href="{{ $wa }}" style="display:inline-block;padding:13px 22px;font-size:15px;color:#14161a;text-decoration:none;">Ajak teman lewat WhatsApp</a></td>
        </tr></table>

        <p style="margin:28px 0 0;font-size:14px;line-height:1.6;color:#404040;">Dana akan disalurkan sesuai tujuan kampanye. Kabar terbaru bisa kamu pantau di halaman kampanye. Simpan email ini sebagai bukti donasi.</p>
    </td></tr>
    <tr><td style="background:#14161a;padding:22px 28px;font-size:12px;line-height:1.7;color:#a3a3a3;">
        <b style="color:#ffffff;">{{ $o['org_name'] }}</b><br>
        Izin PUB: {{ $o['permit_no'] }} · {{ $o['permit_by'] }}<br>
        Pertanyaan atau koreksi data? Balas email ini atau hubungi {{ $o['email'] }}.<br>
        Email ini dikirim otomatis karena kamu berdonasi di Sahabat Peduli.
    </td></tr>
</table>
</td></tr>
</table>
</body>
</html>
