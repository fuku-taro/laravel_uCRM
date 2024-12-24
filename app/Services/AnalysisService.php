<?php

namespace App\Services;

use App\Repositories\AnalysisRepository;

class AnalysisService
{
  private $analysisRepository;
  //コンストラクタ
  public function __construct(AnalysisRepository $analysisRepository)
  {
      $this->analysisRepository = $analysisRepository;
  }


  public function perDay($subQuery)
  {
    $data = $this->analysisRepository->getPerDay($subQuery);

    $labels = $data->pluck('date');
    $totals = $data->pluck('total');

    return [
      $data,
      $labels,
      $totals
    ];
  }

  public function perMonth($subQuery)
  {
    $data = $this->analysisRepository->getPerMonth($subQuery);

    $labels = $data->pluck('date');
    $totals = $data->pluck('total');

    return [
      $data,
      $labels,
      $totals
    ];
  }

  public function perYear($subQuery)
  {
    $data = $this->analysisRepository->getPerYear($subQuery);

    $labels = $data->pluck('date');
    $totals = $data->pluck('total');

    return [
      $data,
      $labels,
      $totals
    ];
  }
}