<?php

namespace App\Common\Services\Utility;

use Carbon\Carbon;

class DateTime
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function gateFormatedDateFromDateRange(?string $dataRange = ''): array
    {
        $from = null;
        $to = null;
        if (! empty($dataRange)) {
            $dateRange = explode('-', trim($dataRange));
            $from = Carbon::parse(trim($dateRange[0]))->startOfDay()->format('Y-m-d H:i:s');
            $to = Carbon::parse(trim($dateRange[1]))->endOfDay()->format('Y-m-d H:i:s');
        }

        return [
             $from,
             $to,
        ];
    }
}
