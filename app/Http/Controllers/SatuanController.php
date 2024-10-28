<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;


class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::all();
        return response()->json($satuans);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $satuans = Satuan::create([
                'name' => $request->name,

            ]);

            return response()->json([
                'message' => 'Berhasil Menambahkan satuan',
                'satuan' => $satuans
            ], 201);
        } catch (QueryException $e) {
            Log::error('Database error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Database error occurred while creating satuan',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            Log::error('General error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'An error occurred while creating the satuan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $satuans = Satuan::find($id);
        if (!$satuans) {
            return response()->json([
                'message' => 'satuan not found'
            ], 404);
        }
        return response()->json([
            'satuan' => $satuans
        ]);
    }

    public function update(Request $request, $id)
    {
        $satuans = Satuan::find($id);
        if (!$satuans) {
            return response()->json([
                'message' => 'satuan not found'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $satuans->fill([
                'name' => $request->name,
            ])->save();

            return response()->json([
                'message' => 'Berhasil memperbarui data satuan'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Ada yang tidak beres saat memperbarui data satuan'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $satuans = Satuan::find($id);
        if (!$satuans) {
            return response()->json([
                'message' => 'satuan not found'
            ], 404);
        }

        try {
            $satuans->delete();
            return response()->json([
                'message' => 'Berhasil menghapus data satuan'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Ada yang tidak beres saat menghapus data satuan'
            ], 500);
        }
    }
}
