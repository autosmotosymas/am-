<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PwaIcons extends Command
{
    protected $signature   = 'pwa:icons';
    protected $description = 'Genera iconos PWA desde el logo principal (GD)';

    // Tamaños requeridos: [ancho, alto, nombre, propósito]
    private array $sizes = [
        [72,  72,  'icon-72.png',   'any'],
        [96,  96,  'icon-96.png',   'any'],
        [128, 128, 'icon-128.png',  'any'],
        [144, 144, 'icon-144.png',  'any'],
        [152, 152, 'icon-152.png',  'any'],
        [192, 192, 'icon-192.png',  'any maskable'],
        [384, 384, 'icon-384.png',  'any'],
        [512, 512, 'icon-512.png',  'any maskable'],
    ];

    public function handle(): int
    {
        $source = public_path('img/logo_amm.png');

        if (! file_exists($source)) {
            $this->error("No se encontró: {$source}");
            return self::FAILURE;
        }

        $dir = public_path('img/icons');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $src = imagecreatefrompng($source);
        if (! $src) {
            $this->error('No se pudo leer el logo como PNG.');
            return self::FAILURE;
        }

        $srcW = imagesx($src);
        $srcH = imagesy($src);

        foreach ($this->sizes as [$w, $h, $name]) {
            // Fondo naranja (para maskable safe zone)
            $dst = imagecreatetruecolor($w, $h);
            $orange = imagecolorallocate($dst, 232, 113, 10);
            imagefill($dst, 0, 0, $orange);

            // Padding del 20% para maskable safe zone
            $pad  = (int) round($w * 0.15);
            $iw   = $w - $pad * 2;
            $ih   = $h - $pad * 2;

            // Escalar proporcionalmente
            $ratio  = min($iw / $srcW, $ih / $srcH);
            $dstW   = (int) round($srcW * $ratio);
            $dstH   = (int) round($srcH * $ratio);
            $dstX   = (int) round(($w - $dstW) / 2);
            $dstY   = (int) round(($h - $dstH) / 2);

            // Preservar transparencia del logo
            imagealphablending($dst, true);
            imagesavealpha($dst, true);

            imagecopyresampled($dst, $src, $dstX, $dstY, 0, 0, $dstW, $dstH, $srcW, $srcH);
            imagepng($dst, "{$dir}/{$name}");
            imagedestroy($dst);

            $this->line("  ✓ {$name} ({$w}×{$h})");
        }

        imagedestroy($src);
        $this->info('Iconos PWA generados en public/img/icons/');

        return self::SUCCESS;
    }
}
