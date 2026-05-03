<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SheetController extends Controller
{
    public function index()
    {
        $url = "https://sheets.googleapis.com/v4/spreadsheets/1uHySGl-eSUzvkKlEiDpvsxfA0B2MG8iRC-1kaIBGOeg/values/form1?key=AIzaSyCUrIXiuJZfOsQATJGGKs1btSfN9dAMcFw";

        $response = Http::get($url);

        if ($response->failed()) {
            return view('google-sheet-list-b', [
                'error' => 'Google Sheetsデータの取得に失敗しました'
            ]);
        }

        $data = $response->json();
        return view('google-sheet-list-b', [
            'sheetData' => $data
        ]);
    }

    public function apiIndex()
    {
        $url = "https://sheets.googleapis.com/v4/spreadsheets/1uHySGl-eSUzvkKlEiDpvsxfA0B2MG8iRC-1kaIBGOeg/values/form1?key=AIzaSyCUrIXiuJZfOsQATJGGKs1btSfN9dAMcFw";

        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([
                'error' => '取得失敗'
            ], 500);
        }

        return $response->json();
    }
}
