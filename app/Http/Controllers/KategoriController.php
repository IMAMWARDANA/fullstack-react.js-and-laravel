<?php

namespace App\Http\Controllers;
use Exception;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return response()->json($kategoris);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $kategoris = Kategori::create([
                'name' => $request->name,

            ]);

            return response()->json([
                'message' => 'Berhasil Menambahkan Kategori',
                'kategori' => $kategoris
            ], 201);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Database error occurred while creating kategori',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Log::error('General error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'An error occurred while creating the kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $kategoris = Kategori::find($id);
        if (!$kategoris) {
            return response()->json([
                'message' => 'Kategori not found'
            ], 404);
        }
        return response()->json([
            'kategori' => $kategoris
        ]);
    }

    public function update(Request $request, $id)
    {
        $kategoris = Kategori::find($id);
        if (!$kategoris) {
            return response()->json([
                'message' => 'Kategori not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $kategoris->fill([
                'name' => $request->name,
            ])->save();

            return response()->json([
                'message' => 'Berhasil memperbarui data kategori'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Ada yang tidak beres saat memperbarui data kategori'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $kategoris = Kategori::find($id);
        if (!$kategoris) {
            return response()->json([
                'message' => 'Kategori not found'
            ], 404);
        }

        try {
            $kategoris->delete();
            return response()->json([
                'message' => 'Berhasil menghapus data kategori'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Ada yang tidak beres saat menghapus data kategori'
            ], 500);
        }
    }
}
