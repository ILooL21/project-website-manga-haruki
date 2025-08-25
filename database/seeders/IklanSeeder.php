<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\Iklan;

class IklanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create sample iklan entries and placeholder images
        $sections = ['sidebar', 'header', 'footer', 'homepage', 'mobile'];

        // ensure directory exists on public disk
        Storage::disk('public')->makeDirectory('iklan');

        foreach ($sections as $i => $section) {
            $filename = 'placeholder-' . ($i+1) . '.jpg';
            $path = 'iklan/' . $filename;

            // generate a simple placeholder image using GD
            $width = 800;
            $height = 300;
            $img = imagecreatetruecolor($width, $height);
            $bg = imagecolorallocate($img, 250, 250, 252);
            $textColor = imagecolorallocate($img, 50, 50, 60);
            imagefilledrectangle($img, 0, 0, $width, $height, $bg);
            $text = 'Iklan - ' . strtoupper($section);
            // calculate position
            $fontSize = 5; // built-in font size
            $textWidth = imagefontwidth($fontSize) * strlen($text);
            $x = intval(($width - $textWidth) / 2);
            $y = intval(($height - imagefontheight($fontSize)) / 2);
            imagestring($img, $fontSize, $x, $y, $text, $textColor);

            ob_start();
            imagejpeg($img, NULL, 85);
            $imgData = ob_get_clean();
            imagedestroy($img);

            Storage::disk('public')->put($path, $imgData);

            Iklan::create([
                'section' => $section,
                'image_path' => $path,
                'link' => 'https://example.com/' . $section,
            ]);
        }
    }
}
