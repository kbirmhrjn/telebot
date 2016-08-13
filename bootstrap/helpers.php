<?php
use Symfony\Component\Console\Helper\ProgressBar;
function progressCallback( $resource, $download_size, $downloaded_size, $upload_size, $uploaded_size )
{
    // create a new progress bar (50 units)
    $output = $this->getOutput();
    $progress = new ProgressBar($output, 50);

    // start and displays the progress bar
    $progress->start();

    $i = 0;
    while ($i++ < 50) {
        // ... do some work

        // advance the progress bar 1 unit
        $progress->advance();

        // you can also advance the progress bar by more than 1 unit
        // $progress->advance(3);
        sleep(1);

    }

    // ensure that the progress bar is at 100%
    $progress->finish();

    static $previousProgress = 0;

    if ( $download_size == 0 )
        $progress = 0;
    else
        $progress = round( $downloaded_size * 100 / $download_size );

    if ( $progress > $previousProgress)
    {
        $previousProgress = $progress;
        $fp = fopen( 'progress.txt', 'a' );
        fputs( $fp, "$progress\n" );
        fclose( $fp );
    }
}