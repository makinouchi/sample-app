<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * コマンドの署名（実行時のコマンド名）
     *
     * @var string
     */
    protected $signature = 'test:run {name? : ユーザー名}';

    /**
     * コマンドの説明
     *
     * @var string
     */
    protected $description = 'テストコマンドを実行します';

    /**
     * コマンドを実行
     */
    public function handle()
    {
        $name = $this->argument('name') ?? 'ユーザー';

        $this->info('🎉 コマンドが実行されました！');
        $this->line('');
        $this->line("こんにちは、{$name}さん！");
        $this->line('');
        $this->comment('このコマンドは正常に完了しました。');

        return Command::SUCCESS;
    }
}
