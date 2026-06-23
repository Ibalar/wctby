<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AdminImportExportController extends Controller
{
    public function export()
    {
        $path = 'exports/products_' . now()->format('Ymd_His') . '.csv';
        Artisan::call('products:export', ['--path' => $path]);

        Log::info('[AdminImportExport] Export triggered', ['path' => $path]);

        return Storage::download($path);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $path = 'imports/' . uniqid('products_', true) . '.csv';
        Storage::put($path, file_get_contents($request->file('file')->path()));

        Artisan::call('products:import', ['file' => $path]);

        Log::info('[AdminImportExport] Import triggered', ['path' => $path]);

        return back()->with('success', 'Импорт выполнен. Проверьте результат в логах.');
    }

    public function showForm()
    {
        return view('admin.import');
    }
}
