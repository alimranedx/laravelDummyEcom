<?php

namespace App\Common\Services\Utility;

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
            $dateRange = explode(' - ', $dataRange);
            $from = Carbon::parse($dateRange[0])->format('Y-m-d');
            $to = Carbon::parse($dateRange[1])->format('Y-m-d');
        }

        return [
            'from' => $from,
            'to' => $to,
        ];
    }
}
