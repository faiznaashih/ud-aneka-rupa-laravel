<?php

namespace App\Support;

class StatusBadge
{
    public static function map(string $status): array
    {
        $map = [
            'pending'    => ['bg' => 'warning', 'text' => 'Menunggu'],
            'diproses'   => ['bg' => 'info',     'text' => 'Diproses'],
            'dikirim'    => ['bg' => 'primary',  'text' => 'Dikirim'],
            'selesai'    => ['bg' => 'success',  'text' => 'Selesai'],
            'dibatalkan' => ['bg' => 'danger',   'text' => 'Dibatalkan'],
        ];

        return $map[$status] ?? ['bg' => 'secondary', 'text' => ucfirst($status)];
    }
}
