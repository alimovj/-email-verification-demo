<?php
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Notifications\VerifyReminderNotification;

class DeleteUnverifiedUsers extends Command
{
    protected $signature = 'users:delete-unverified';
    protected $description = '3 kun ichida email tasdiqlamagan foydalanuvchilarni o‘chirish';

    public function handle()
    {
              
$remindThreshold = Carbon::now()->subDays(2);

$users = User::whereNull('email_verified_at')
    ->whereDate('created_at', $remindThreshold->toDateString())
    ->where('created_at', '<=', now()->subDays(3))

    ->get();
    

      foreach ($users as $user) {
    $user->notify(new VerifyReminderNotification);
}





        $threshold = Carbon::now()->subDays(3);

        $users = User::whereNull('email_verified_at')
            ->where('created_at', '<', $threshold)
            ->get();

        foreach ($users as $user) {
            Log::info("O'chirildi: {$user->email}");
            $user->delete();
        }

        $this->info("{$users->count()} ta foydalanuvchi o‘chirildi.");
    }



}
