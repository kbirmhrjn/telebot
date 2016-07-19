<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SaturdayNightLive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'saturday:live';

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
        $saturdays = collect([
                                'saturday-1' => ['2015-01','2015-02','2015-03','2015-04'],
                                'saturday-2' => ['2016-06','2016-07','2016-08','2016-09'],
                                'saturday-3' => ['2017-06','2017-07','2017-08','2017-09'],
                                'saturday-4' => ['2018-06','2018-07','2018-08','2018-09'],
                            ]);
        $maper = $saturdays->keys()->map(function($saturday) use ($saturdays){
            $string = '';
            $date = collect( $saturdays->get($saturday) )
                            ->each(function($text) use(&$string){
                                // $string .= "<a target='_blank' href='//google.com/{$text}/>{$text}</a>";
                                $string .= "$text + ";
                            });

            dd($string);
        });
        dd($maper);
    }
}
