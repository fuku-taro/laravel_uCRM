<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Order;
use App\Services\AnalysisService;
use App\Services\DecileService;
use App\Services\RFMService;

class AnalysisController extends Controller
{
    private $analysisService;
    private $decileService;
    private $rfmService;
    //コンストラクタ
    public function __construct(
        AnalysisService $analysisService,
        DecileService $decileService,
        RFMService $rfmService
        )
    {
        $this->analysisService = $analysisService;
        $this->decileService = $decileService;
        $this->rfmService = $rfmService;
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

        if($request->type === 'rfm'){
            list($data, $totals, $eachCount) = $this->rfmService->rfm($subQuery, $request->rfmPrms);

            return response()->json([
                'data' => $data,
                'type' => $request->type,
                'eachCount' => $eachCount,
                'totals' => $totals,
            ], Response::HTTP_OK);
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
