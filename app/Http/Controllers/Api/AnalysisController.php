<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;
use App\Services\AnalysisService;

class AnalysisController extends Controller
{
    private $analysisService;
    //コンストラクタ
    public function __construct(AnalysisService $analysisService)
    {
        $this->analysisService = $analysisService;
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

        // Ajax通信のためJsonで返却する
        return response()->json([
            'data' => $data,
            'type' => $request->type,
            'labels' => $labels,
            'totals' => $totals,
        ], Response::HTTP_OK);

    }

}
