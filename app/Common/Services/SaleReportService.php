<?php
namespace App\Common\Services;

class SaleReportService
{
    public function getByFilters(array $requestData): array
    {
        $data = [
            'module' => "Report",
            'sub_module' => "Sale Report",
            'title' => "Sale Report",
        ];
        // dd($requestData);
        $filterData = $this->getFilterData($requestData);
        
        return $data;        
    }
    public function getFilterData(array $requestData): array
    {
        if(!empty($requestData['date_range'])){
            
        }
        return $requestData;
    }
}
