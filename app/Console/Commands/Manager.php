<?php
namespace App\Console\Commands;

trait Manager{
    protected $progress;
    protected $previousProgress = 0;

    public function download($fileName, $url)
    {
        $targetFile = fopen( $fileName, 'w' );
        $ch = curl_init( $url );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_FILE, $targetFile );
        curl_exec( $ch );
        fclose( $targetFile );
    }
}