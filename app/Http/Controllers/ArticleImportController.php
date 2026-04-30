<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ArticlesImport;
use Maatwebsite\Excel\Facades\Excel;

class ArticleImportController extends Controller
{
    public function index()
    {
        return view('dashboard.articles.import');
    }


    public function import(Request $request)
    {
        // ✅ validation du fichier
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {

            Excel::import(new ArticlesImport, $request->file('file'));

            return back()->with('success', 'Importation réussie');

        } catch (\Exception $e) {

            return back()->with('success', 'Erreur lors de l\'import : ' . $e->getMessage());
        }
    }
}
