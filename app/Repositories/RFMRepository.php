<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class RFMRepository
{
  public function getRFMSubQuery($subQuery, $rfmPrms)
  {
    // RFM分析
    // 1. 購買ID毎にまとめる
    $subQuery = $subQuery->groupBy('id')
    ->selectRaw('id, customer_id,
    customer_name,
    SUM(subtotal) as totalPerPurchase, created_at');

    // 2. 会員毎にまとめて最終購入日、回数、合計金額を取得
    $subQuery = DB::table($subQuery)
    ->groupBy('customer_id')
    ->selectRaw('customer_id, customer_name,
    max(created_at) as recentDate,
    datediff(now(), max(created_at)) as recency,
    count(customer_id) as frequency,
    sum(totalPerPurchase) as monetary');

    // 4. 会員毎のRFMランクを計算
    $subQuery = DB::table($subQuery)
    ->selectRaw('customer_id, customer_name,
    recentDate, recency, frequency, monetary,
    case
        when recency < ? then 5
        when recency < ? then 4
        when recency < ? then 3
        when recency < ? then 2
        else 1 end as r,
    case
        when ? <= frequency then 5
        when ? <= frequency then 4
        when ? <= frequency then 3
        when ? <= frequency then 2
        else 1 end as f,
    case
        when ? <= monetary then 5
        when ? <= monetary then 4
        when ? <= monetary then 3
        when ? <= monetary then 2
    else 1 end as m', $rfmPrms);

    return $subQuery;
  }

  public function getCountsSubQuery($subQuery)
  {
    // 5.ランク毎の数を計算する
    $totals = DB::table($subQuery)->count();

    $rCount = DB::table($subQuery)
    ->rightJoin('ranks', 'ranks.rank', '=', 'r')
    ->groupBy('ranks.rank')
    ->selectRaw('ranks.rank as r, count(r)')
    ->orderBy('r', 'desc')
    ->get();

    $fCount = DB::table($subQuery)
    ->rightJoin('ranks', 'ranks.rank', '=', 'f')
    ->groupBy('ranks.rank')
    ->selectRaw('ranks.rank as f, count(f)')
    ->orderBy('f', 'desc')
    ->get();

    $mCount = DB::table($subQuery)
    ->rightJoin('ranks', 'ranks.rank', '=', 'm')
    ->groupBy('ranks.rank')
    ->selectRaw('ranks.rank as m, count(m)')
    ->orderBy('m', 'desc')
    ->get();

    // $rCount = $rCount->pluck('count(r)');
    // $fCount = $fCount->pluck('count(f)');
    // $mCount = $mCount->pluck('count(m)');

    // $eachCount = []; // Vue側に渡すようの空の配列
    // $rank = 5; // 初期値5
    // for($i = 0; $i < 5; $i++)
    // {
    // array_push($eachCount, [
    //     'rank' => $rank,
    //     'r' => $rCount[$i],
    //     'f' => $fCount[$i],
    //     'm' => $mCount[$i],
    // ]);
    //     $rank--; // rankを1ずつ減らす
    // }
    return [
      'totals' => $totals,
      'rCount' => $rCount,
      'fCount' => $fCount,
      'mCount' => $mCount
    ];
  }

  public function getRFM($subQuery)
  {
    // concatで文字列結合
    // 6. RとFで2次元で表示してみる
    $data = DB::table($subQuery)
    ->rightJoin('ranks', 'ranks.rank', '=', 'r')
    ->groupBy('ranks.rank')
    ->selectRaw('concat("r_", ranks.rank) as rRank,
        count(case when f = 5 then 1 end ) as f_5,
        count(case when f = 4 then 1 end ) as f_4,
        count(case when f = 3 then 1 end ) as f_3,
        count(case when f = 2 then 1 end ) as f_2,
        count(case when f = 1 then 1 end ) as f_1')
    ->orderBy('rRank', 'desc')
    ->get();

    return $data;
  }
}