<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SaleReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Simple filtering
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereDate('created_at', '>=', $dates[0])
                      ->whereDate('created_at', '<=', $dates[1]);
            } else {
                $query->whereDate('created_at', $dates[0]);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $summary = [
            'total_revenue' => (clone $query)->sum('total_price'),
            'total_orders' => (clone $query)->count(),
        ];

        $sales = $query->paginate(20)->withQueryString();
        $statuses = OrderStatus::cases();

        return view('admin.sale-report.index', compact('sales', 'statuses', 'summary'));
    }

    /**
     * Export sale report as CSV or XLSX (XML Spreadsheet).
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'csv');

        $query = Order::with('user')->latest();

        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereDate('created_at', '>=', $dates[0])
                      ->whereDate('created_at', '<=', $dates[1]);
            } else {
                $query->whereDate('created_at', $dates[0]);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();
        $filename = 'sale_report_' . now()->format('Y_m_d_His');

        if ($format === 'xlsx') {
            return $this->exportXlsx($orders, $filename);
        }

        return $this->exportCsv($orders, $filename);
    }

    /**
     * CSV export using native PHP fputcsv.
     */
    private function exportCsv($orders, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, ['Order ID', 'Customer', 'Email', 'Amount', 'Status', 'Date']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->user->name,
                    $order->user->email,
                    number_format($order->total_price, 2),
                    $order->status->label(),
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * XLSX export using XML Spreadsheet 2003 format (no packages required).
     * Excel, Google Sheets, and LibreOffice all open this natively.
     */
    private function exportXlsx($orders, $filename)
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
        $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";

        // Styles
        $xml .= '<Styles>';
        $xml .= '<Style ss:ID="header"><Font ss:Bold="1" ss:Size="11"/><Interior ss:Color="#4F46E5" ss:Pattern="Solid"/><Font ss:Color="#FFFFFF" ss:Bold="1"/></Style>';
        $xml .= '<Style ss:ID="currency"><NumberFormat ss:Format="#,##0.00"/></Style>';
        $xml .= '</Styles>';

        $xml .= '<Worksheet ss:Name="Sale Report">' . "\n";
        $xml .= '<Table>' . "\n";

        // Column widths
        $xml .= '<Column ss:Width="70"/>';
        $xml .= '<Column ss:Width="150"/>';
        $xml .= '<Column ss:Width="200"/>';
        $xml .= '<Column ss:Width="100"/>';
        $xml .= '<Column ss:Width="100"/>';
        $xml .= '<Column ss:Width="150"/>';

        // Header row
        $headers = ['Order ID', 'Customer', 'Email', 'Amount', 'Status', 'Date'];
        $xml .= '<Row>';
        foreach ($headers as $h) {
            $xml .= '<Cell ss:StyleID="header"><Data ss:Type="String">' . htmlspecialchars($h) . '</Data></Cell>';
        }
        $xml .= '</Row>' . "\n";

        // Data rows
        foreach ($orders as $order) {
            $xml .= '<Row>';
            $xml .= '<Cell><Data ss:Type="Number">' . $order->id . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->user->name) . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->user->email) . '</Data></Cell>';
            $xml .= '<Cell ss:StyleID="currency"><Data ss:Type="Number">' . $order->total_price . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($order->status->label()) . '</Data></Cell>';
            $xml .= '<Cell><Data ss:Type="String">' . $order->created_at->format('Y-m-d H:i:s') . '</Data></Cell>';
            $xml .= '</Row>' . "\n";
        }

        $xml .= '</Table>' . "\n";
        $xml .= '</Worksheet>' . "\n";
        $xml .= '</Workbook>';

        return Response::make($xml, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"{$filename}.xls\"",
        ]);
    }
}
