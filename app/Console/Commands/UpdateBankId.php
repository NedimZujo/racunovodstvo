<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\Enums\UserIdEnum;
use App\Amount;
use App\User;

class UpdateBankId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:bankid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for updating bankid';

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
        $amounts=Amount::where('user_id', 100)->get();
        $user = User::find(100);

        foreach ($amounts as $amount)
        {
            $amount->user_id=UserIdEnum::banka;
            $amount->save();
        }

        $user->id = UserIdEnum::banka;
        $user->save();
    }
}
