<?php
declare(strict_types=1);

class Downloader
{
    public static function download()
    {
        $fileName = 'latest.zip';

        $path = dirname(__FILE__);

        copy('https://wordpress.org/latest.zip', $fileName);

        $zip = new \ZipArchive();

        $res = $zip->open($fileName);
        if ($res === false) {
            echo 'doh!';
            return;
        }
        $zip->extractTo($path);
        $zip->close();
        echo 'woot!';

        Downloader::rCopy('wordpress', $path, $path);

        Downloader::rRmdir('wordpress');
    }

    // Function to remove folders and files
    private static function rRmdir(string $dir)
    {
        if (!is_dir($dir)) {
            if (file_exists($dir)) {
                unlink($dir);
                return;
            }
        }
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file !== "." && $file !== "..") {
                Downloader::rRmdir("$dir/$file");
            }
        }
        rmdir($dir);
    }

    // Function to Copy folders and files
    private static function rCopy(string $src, string $dst, string $skip)
    {
        if (!is_dir($src)) {
            if (file_exists($src)) {
                copy($src, $dst);
            }
            return;
        }
        if ($dst !== $skip) {
            mkdir($dst);
        }
        $files = scandir($src);
        foreach ($files as $file) {
            if ($file !== "." && $file !== "..") {
                Downloader::rCopy("$src/$file", "$dst/$file", $skip);
            }
        }
    }
}
