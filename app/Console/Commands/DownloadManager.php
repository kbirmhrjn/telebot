<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Console\Commands\Manager;
use ZipArchive;
use RecursiveIteratorIterator ,RecursiveDirectoryIterator;
use Carbon\Carbon;
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
    protected $description = 'Download Videos and all';

    protected $urls = [
        'stubbing-eloquent-relations-for-faster-tests' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/518/7/177591203/577715584.mp4?token=57b57606_0xda37e740c1856f9a352417130c07466fac3ed963',
        'approaches-to-testing-events-in-laravel' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/786/7/178931955/583267193.mp4?token=57b57a37_0xc1636108309748ddc75ead41bee52aac195d524e',
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
        $bar = $this->output->createProgressBar( $links->count() );
        $this->info('Download Status:');
        $links->each(function($link, $key) use($bar){
            $this->download('./downloads/'.$key . '.mp4', $link);
            $bar->advance();
        });
        $bar->finish(); $this->info('');
        $this->info('Now Zipping(tar):');
        $this->zipDir();
        $this->info('Done!!!');
    }

    protected function zipDir()
    {
        $rootPath = realpath( './downloads');

        // Initialize archive object
        $zip = new ZipArchive();
        $name = 'videos-'. Carbon::now()->format('Y-m-d-H-i') . '.zip';
        $zip->open($name, ZipArchive::CREATE | ZipArchive::OVERWRITE);

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
