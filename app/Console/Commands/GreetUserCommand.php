<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('greet:user {name? : ユーザー名}')]
#[Description('ユーザーに挨拶するコマンド')]
class GreetUserCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?? 'ゲスト';

        $this->info('👋 こんにちは！');
        $this->line("{$name}さん、ようこそ！");

        return Command::SUCCESS;
    }
}
