<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CommandController extends Controller
{
    /**
     * コマンド実行ページを表示
     */
    public function index()
    {
        return view('commands.index');
    }

    /**
     * Artisanコマンドを実行
     */
    public function run(Request $request)
    {
        $request->validate([
            'command' => 'required|string',
            'name' => 'required|string|max:255',
        ]);

        $command = $request->input('command');
        $name = $request->input('name');
        \Log::info('command: ' . $command);
        \Log::info('name: ' . $name);

        try {
            // コマンドを実行
            Artisan::call($command, ['name' => $name]);

            // 出力結果を取得
            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'コマンドが正常に実行されました',
                'output' => $output,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'エラーが発生しました: ' . $e->getMessage(),
            ], 500);
        }
    }
}
