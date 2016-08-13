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
    'taylor-otwell-laravel-53-overview' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/314/7/176570224/574203158.mp4?token=57af4f6a_0xe798cd30a6dbe84d6307438e6ac2e4f8be8301b2',
    'adam-wathan-test-driven-laravel' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/315/7/176577242/574207190.mp4?token=57af3c0f_0x28a94689aa842bf351ac7ccd61a3198a94149f48',
    'matthew-machuga-tests-should-tell-a-story' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/336/7/176683961/574120844.mp4?token=57af0e80_0x3bb2002e127d2cb1125c6501a4ded0a6a48c1931',
    'adam-wathan-zollections' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/351/7/176757569/574437719.mp4?token=57aef4ff_0xe6871cf984f96acb41c401ed2411455ed0dfb041',
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
            $this->download('./downloads/'.$key . '.mp4', $link);
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
