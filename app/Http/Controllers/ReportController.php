<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$equipment, $filters] = $this->filtered($request);
        return view('reports.index', compact('equipment', 'filters'));
    }

    public function pdf(Request $request): Response
    {
        [$equipment, $filters] = $this->filtered($request);
        $html = view('reports.print', compact('equipment', 'filters'))->render();
        // This lightweight PDF keeps exports dependency-free and works in the local demo environment.
        $pdf = $this->simplePdf(strip_tags(str_replace(['</tr>', '</p>', '<br>'], ["\n", "\n", "\n"], $html)));
        return response($pdf, 200, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'attachment; filename="equipment-report.pdf"']);
    }

    public function excel(Request $request): Response
    {
        [$equipment] = $this->filtered($request);
        $rows = ['Equipment Name,Category,Unit Price,Availability Status,Description'];
        foreach ($equipment as $item) {
            $rows[] = collect([$item->equipment_name, $item->category, number_format($item->unit_price, 2, '.', ''), ucfirst($item->availability_status), $item->description])->map(fn ($value) => '"'.str_replace('"', '""', $value).'"')->implode(',');
        }
        return response(implode("\r\n", $rows), 200, ['Content-Type' => 'application/vnd.ms-excel', 'Content-Disposition' => 'attachment; filename="equipment-report.xls"']);
    }

    private function filtered(Request $request): array
    {
        $filters = $request->validate(['category' => ['nullable', 'in:'.implode(',', Equipment::categories())], 'availability_status' => ['nullable', 'in:'.implode(',', Equipment::statuses())]]);
        $query = $request->user()->equipment()->latest();
        if (! empty($filters['category'])) $query->where('category', $filters['category']);
        if (! empty($filters['availability_status'])) $query->where('availability_status', $filters['availability_status']);
        return [$query->get(), $filters];
    }

    private function simplePdf(string $text): string
    {
        $lines = array_slice(array_filter(array_map('trim', preg_split('/\R/', $text))), 0, 55);
        $content = "BT /F1 10 Tf 40 800 Td ";
        foreach ($lines as $index => $line) { $safe = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], substr($line, 0, 110)); $content .= "(".$safe.") Tj 0 -14 Td "; }
        $content .= 'ET';
        $objects = ["<< /Type /Catalog /Pages 2 0 R >>", "<< /Type /Pages /Kids [3 0 R] /Count 1 >>", "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>", "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>", "<< /Length ".strlen($content)." >>\nstream\n".$content."\nendstream"];
        $pdf = "%PDF-1.4\n"; $offsets = [0];
        foreach ($objects as $number => $object) { $offsets[] = strlen($pdf); $pdf .= ($number + 1)." 0 obj\n".$object."\nendobj\n"; }
        $xref = strlen($pdf); $pdf .= "xref\n0 ".(count($objects) + 1)."\n0000000000 65535 f \n"; foreach (array_slice($offsets, 1) as $offset) $pdf .= sprintf('%010d 00000 n ', $offset)."\n"; $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n".$xref."\n%%EOF";
        return $pdf;
    }
}
