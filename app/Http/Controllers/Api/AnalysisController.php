<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;
use App\Services\AnalysisService;
use App\Services\DecileService;

class AnalysisController extends Controller
{
    private $analysisService;
    private $decileService;
    //コンストラクタ
    public function __construct(AnalysisService $analysisService, DecileService $decileService)
    {
        $this->analysisService = $analysisService;
        $this->decileService = $decileService;
    }
    public function index(Request $request)
    {
        $subQuery = Order::betweenDate($request->startDate, $request->endDate);

        if($request->type === 'perDay'){
            list($data, $labels, $totals) = $this->analysisService->perDay($subQuery);
        }

        if($request->type === 'perMonth'){
            list($data, $labels, $totals) = $this->analysisService->perMonth($subQuery);
        }

        if($request->type === 'perYear'){
            list($data, $labels, $totals) = $this->analysisService->perYear($subQuery);
        }

        if($request->type === 'decile'){
            list($data, $labels, $totals) = $this->decileService->decile($subQuery);
        }
        // Ajax通信のためJsonで返却する
        return response()->json([
            'data' => $data,
            'type' => $request->type,
            'labels' => $labels,
            'totals' => $totals,
        ], Response::HTTP_OK);

    }

}
