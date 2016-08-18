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
    protected $description = 'Command description';

    protected $urls = [
    'The Global Cache Helper' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/281/7/176409321/572916396.mp4?token=57b59a3c_0x2846624035c23c7ce7e1df3e55dad6191eaacf2c',
    'The Query Builder Now Returns Collections' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/281/7/176406911/572905952.mp4?token=57b599b3_0x0e80e153078040c5bbb261f3a735aa0bbbd21342',
    'The JavaScript Suggestion' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/350/7/176751974/574396475.mp4?token=57b59a79_0x34164659cc05ba862be834b43249542baa7c2ec6',
    'Simpler Pagnation' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/352/7/176764828/574455500.mp4?token=57b59aa3_0x0c287649860039b3d6a8ed4502280d1f14ed9092',
    'Mailables' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/426/7/177133133/575811557.mp4?token=57b59b2c_0x8a8955a13fa6831a0df1f34b15c78be9ec58676e',
    'Foreach, and the Loop ' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/460/7/177303523/576543721.mp4?token=57b59ab7_0x41d2072987969b36e1980bc74d1a2d3310ddfbc2',
    'Toggle Pivot Table Records' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/609/7/178047892/579503207.mp4?token=57b59b05_0x055ff78e4b355fb76f22c03c1a74cdcef010c7fa',
    'Notification-email' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/645/7/178228527/580224306.mp4?token=57b59b2b_0x824970d5dadde0d262b4eaaaa005a5766025f4bf',
    'Notifications: Database' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/679/7/178396056/580982245.mp4?token=57b59bd7_0x44df9b071edb0d1624046227559c46e83a2bdc91',
    'Send Slack Notifications With Laravel in Minutes' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/809/7/179049532/583784730.mp4?token=57b59b89_0x3a315715206bdfe7f881138d6dd43ef4aad9614e',
    'Super Simple File Uploading' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/842/7/179214411/584489255.mp4?token=57b59ba0_0x8480c5682a4a4eb709dd602a4c6dd1b83ce99f19',
    'Laravel passport' => 'https://fpdl.vimeocdn.com/vimeo-prod-skyfire-std-us/01/849/7/179249176/584650551.mp4?token=57b59bb4_0x2dc9aca96f9a3ad5bcd6a99e417cd5bda601c0a0',

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
        dd($links);
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
        $name = 'videos-'. Carbon::now()->format('Y-m-d-H') . '.zip';
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
