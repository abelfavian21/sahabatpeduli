<?php

// Data lembaga untuk halaman Legalitas, Privasi, Syarat, dan footer.
// Isi lewat .env. Nilai bawaan sengaja berupa [placeholder] agar terlihat jelas kalau belum diisi.
return [
    'org_name' => env('ORG_NAME', '[Nama Yayasan/Perkumpulan]'),
    'org_legal' => env('ORG_LEGAL', '[Nomor SK pengesahan badan hukum]'),
    'org_address' => env('ORG_ADDRESS', '[Alamat lengkap lembaga]'),
    'email' => env('ORG_EMAIL', '[email@lembaga.id]'),
    'privacy_email' => env('ORG_PRIVACY_EMAIL', env('ORG_EMAIL', '[email@lembaga.id]')),
    'whatsapp' => env('ORG_WHATSAPP', '[08xxxxxxxxxx]'),
    'permit_no' => env('PUB_PERMIT_NO', '[Nomor izin PUB]'),
    'permit_by' => env('PUB_PERMIT_BY', '[Pejabat pemberi izin]'),
    'permit_date' => env('PUB_PERMIT_DATE', '[Tanggal izin]'),
    'permit_until' => env('PUB_PERMIT_UNTIL', '[Masa berlaku izin]'),
    'fee_percent' => env('PLATFORM_FEE_PERCENT', '[0]'),
    'retention_days' => env('REJECTED_DATA_RETENTION_DAYS', '30'),
    'updated' => env('LEGAL_UPDATED', '14 November 2026'),
];
