<?php

namespace App\Services;

use App\Repositories\DecileRepository;

class DecileService
{
  private $decileRepository;
  //コンストラクタ
  public function __construct(DecileRepository $decileRepository)
  {
      $this->decileRepository = $decileRepository;
  }

  public function decile($subQuery)
  {
    $data = $this->decileRepository->getDecile($subQuery);

    $labels = $data->pluck('decile');
    $totals = $data->pluck('totalPerGroup');

    return [
      $data,
      $labels,
      $totals
    ];
  }
}