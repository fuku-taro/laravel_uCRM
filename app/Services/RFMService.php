<?php

namespace App\Services;

use App\Repositories\RFMRepository;

class RFMService
{
  private $rfmRepository;
  //コンストラクタ
  public function __construct(RFMRepository $rfmRepository)
  {
      $this->rfmRepository = $rfmRepository;
  }

  public function rfm($subQuery, $rfmPrms)
  {
    $subQuery = $this->rfmRepository->getRFMSubQuery($subQuery, $rfmPrms);

    $resultArr = $this->rfmRepository->getCountsSubQuery($subQuery);

    $totals = $resultArr['totals'];
    $rCount = $resultArr['rCount']->pluck('count(r)');
    $fCount = $resultArr['fCount']->pluck('count(f)');
    $mCount = $resultArr['mCount']->pluck('count(m)');

    $eachCount = []; // Vue側に渡すようの空の配列
    $rank = 5; // 初期値5
    for($i = 0; $i < 5; $i++)
    {
    array_push($eachCount, [
        'rank' => $rank,
        'r' => $rCount[$i],
        'f' => $fCount[$i],
        'm' => $mCount[$i],
    ]);
        $rank--; // rankを1ずつ減らす
    }

    $data = $this->rfmRepository->getRFM($subQuery);

    return [
      $data,
      $totals,
      $eachCount
    ];
  }

}