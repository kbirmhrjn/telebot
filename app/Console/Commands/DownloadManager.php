<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DownloadManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:gumroad';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        file_put_contents( 'progress.txt', '' );
        $targetFile = fopen( 'all.zip', 'w' );
        $ch = curl_init( 'https://d2dw6lv4z9w0e2.cloudfront.net/attachments/db2ce780e336a4bd7a63e4c0b573258a1/original/All%20Screencasts%20v1.1.0.zip?response-content-disposition=attachment&Expires=1468944477&Signature=XTITT3pZaPsTKz+9KTBMJe1Bqkv/iSJXMZpE4R3Db72yGeeKmr6pPJsKviNn1Ts9rw3vXzFi3VxliWIJeAaa9JL0TCTVp4eVfZcotkgsxgtzayF7vetn6fMu78/Q9JNpnwc+dh33/M8fRst/LMUQKNBJKAi0ilroW4J9FvS6G3k=&Key-Pair-Id=APKAISH5PKOS7WQUJ6SA' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_NOPROGRESS, false );
        curl_setopt( $ch, CURLOPT_PROGRESSFUNCTION, 'progressCallback' );
        curl_setopt( $ch, CURLOPT_FILE, $targetFile );
        curl_exec( $ch );
        fclose( $targetFile );
    }
}
