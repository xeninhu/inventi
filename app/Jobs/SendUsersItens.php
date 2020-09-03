<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserItensList;
use App\User;

class SendUsersItens implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $coordination_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($coordination_id)
    {
        $this->coordination_id = $coordination_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::with("itens")
            ->where("coordination_id",$this->coordination_id)
            ->get();
        foreach($users as $user)
            Mail::to($user->email)
                ->send(new UserItensList($user->itens));
    }
}
