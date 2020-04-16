<?php

class Downloader
{

    public static function download()
    {

        $fileName = 'latest.zip';
        $path     = dirname(__FILE__);;

        copy('https://wordpress.org/latest.zip', $fileName);


        $zip = new ZipArchive();

        $res = $zip->open($fileName);
        if ($res === TRUE) {
            $zip->extractTo($path);
            $zip->close();
            echo 'woot!';

            Downloader::rcopy('wordpress', $path, $path);

            Downloader::rrmdir('wordpress');

        } else {
            echo 'doh!';
        }


    }


    // Function to remove folders and files
    static function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $files = scandir($dir);
            foreach ($files as $file)
                if ($file != "." && $file != "..") rrmdir("$dir/$file");
            rmdir($dir);
        } else if (file_exists($dir)) unlink($dir);
    }

// Function to Copy folders and files
    static function rcopy($src, $dst, $skip)
    {
//    if (file_exists($dst))
//        Downloader::rrmdir($dst);
        if (is_dir($src)) {
            if ($dst != $skip)
                mkdir($dst);
            $files = scandir($src);
            foreach ($files as $file)
                if ($file != "." && $file != "..")
                    Downloader::rcopy("$src/$file", "$dst/$file", $skip);
        } else if (file_exists($src))
            copy($src, $dst);
    }

}