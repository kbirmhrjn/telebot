<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Console\Commands\Manager;
use ZipArchive;
use RecursiveIteratorIterator ,RecursiveDirectoryIterator;
class DownloadManager extends Command
{
    use Manager;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download:video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $urls = [
        'lightroom-presents' => 'https://sleeklens.com/?download_file=148&order=wc_order_57b061cb5185f&email=kabeerdarocker%40gmail.com&key=53758905ea56ea872ed2939b989e0d6f'
    ];

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
        if(! is_dir('./downloads') ) mkdir('./downloads');
        $links = collect($this->urls);
        $links->each(function($link, $key){
            $this->download('./downloads/'.$key . '.zip', $link);
        });
        $this->zipDir();
        $this->info('Done!!!');
    }

    protected function zipDir()
    {
        $rootPath = realpath( './downloads');

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open('videos.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
    }
}
