<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AnalysisRepository
{
  public function getPerDay($subQuery)
  {
    $query = $subQuery->where('status', true)
            ->groupBy('id')
            ->selectRaw(
                'id,
                sum(subtotal) as totalPerPurchase,
                DATE_FORMAT(created_at, "%Y%m%d") as date' );

    $data = DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total')
            ->get();

    return $data;
  }

  public function getPerMonth($subQuery)
  {
    $query = $subQuery->where('status', true)
            ->groupBy('id')
            ->selectRaw(
                'id,
                sum(subtotal) as totalPerPurchase,
                DATE_FORMAT(created_at, "%Y%m") as date' );

    $data = DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total')
            ->get();

    return $data;
  }

  public function getPerYear($subQuery)
  {
    $query = $subQuery->where('status', true)
            ->groupBy('id')
            ->selectRaw(
                'id,
                sum(subtotal) as totalPerPurchase,
                DATE_FORMAT(created_at, "%Y") as date' );

    $data = DB::table($query)
            ->groupBy('date')
            ->selectRaw('date, sum(totalPerPurchase) as total')
            ->get();

    return $data;
  }
}