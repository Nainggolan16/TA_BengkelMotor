<?php

namespace App\Http\Controllers;

use App\Models\SukuCadang;
use App\Services\ActiveBatchService;
use Illuminate\Http\Request;

class RiwayatBatchStokController extends Controller
{
    protected $activeBatchService;

    public function __construct()
    {
        $this->activeBatchService = new ActiveBatchService();
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $sukuCadang = SukuCadang::when($search, function ($query) use ($search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                ->orWhere('kode', 'like', '%' . $search . '%');
        })
        ->orderBy('nama')
        ->get();

        return view('riwayat-batch-stok.index', compact('sukuCadang'));
    }

    public function show($id)
    {
        $sukuCadang = SukuCadang::findOrFail($id);
        $batchSummary = $this->activeBatchService->getBatchSummary($id);
        $activeBatch = $this->activeBatchService->getActiveBatch($id);

        return view('riwayat-batch-stok.show', compact('sukuCadang', 'batchSummary', 'activeBatch'));
    }
}
