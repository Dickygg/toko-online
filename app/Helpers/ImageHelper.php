<?php

namespace App\Helpers;

class ImageHelper
{
    public static function uploadAndRisize($file, $directory, $filename, $width = null, $height = null)
    {
        $destinationPath = public_path($directory);
        $extension = strtolower($file->getClientOriginalExtension());
        $image = null;

        //tentukan metode pembuatan gambar berdasarkan ekstensi file 
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png':
                $image = imagecreatefrompng($file->getRealPath());
                break;
            case 'gif':
                $image = imagecreatefromgif($file->getRealPath());
                break;
            default:
                throw new \Exception('Tipe File tidak support');
        }

        //Risize Gambar Jika lebar diset
        if ($width) {
            $oldwidth = imagesx($image);
            $oldheight = imagesy($image);
            $aspecRatio = $oldwidth / $oldheight;

            if (!$height) {
                $height = $width / $aspecRatio; //hitung tinggi dengan mempertahankan aspek ratio
            }
            $newimage = imagecreatetruecolor($width, $height);
            imagecopyresampled($newimage, $image, 0, 0, 0, 0, $width, $height, $oldwidth, $oldheight);
            imagedestroy($image);
            $image = $newimage;
        }

        //simpan gambar dengan kualitas asli
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($image, $destinationPath . '/' . $filename);
                break;
            case 'png':
                imagepng($image, $destinationPath . '/' . $filename);
                break;
            case 'gif':
                imagegif($image, $destinationPath . '/' . $filename);
                break;
        }
        imagedestroy($image);
        return $filename;
    }
}
