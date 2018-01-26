<?php
/**
 * 邮件发送队列处理类
 * author:gym
 * date:2017-11-29
 */
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendFinanceMail  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tries = 5;
    protected $timeout = 300;
    protected $data = [];

    /**
     * Create a new job instance.
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);
        $data = $this->data;
        Mail::send('mailtemp.taskmail', $data, function($message) use($data) {
            $message->to($data['emails'])->subject($data['title']);
        });
        info('-------------------邮件队列-----------------');
    }
}
