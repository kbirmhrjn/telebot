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
        curl_setopt( $ch, CURLOPT_NOPROGRESS, false );
        curl_setopt( $ch, CURLOPT_PROGRESSFUNCTION, function( $resource, $downloadTotal, $downloadNow, $uploadTotal, $uploadNow ){
            $progress = 0;
            if( !$this->progress instanceof ProgressBar)
            {
                $this->progress = new ProgressBar($this->getOutput(), $downloadTotal );
                $this->progress->start();
            }
            $this->progress->advance();
        });
        curl_setopt( $ch, CURLOPT_FILE, $targetFile );
        curl_exec( $ch );
        fclose( $targetFile );
    }
}