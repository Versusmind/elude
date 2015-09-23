<?php namespace App\Jobs\Mails;

use App\User;
use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class LostPassword extends Job implements SelfHandling, ShouldQueue
{
    protected $user;

    protected $token;

    /**
     * @param User $user
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @param  Mailer $mailer
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send('mails.lostPassword', [
            'token' => $this->token,
            'username' => $this->user->username
        ], function ($message) {
            $message->to($this->user->email, $this->user->username)->subject('Lost password');
        });
    }
}