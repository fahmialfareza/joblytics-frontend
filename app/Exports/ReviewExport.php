<?php

namespace App\Exports;

use App\Models\FeTxReservationRating;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ReviewExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    protected $reviews;

    public function __construct($reviews)
    {
        $this->reviews = $reviews;
    }
    
    public function headings() : array
    {
        return [
            'No',
            'Order No',
            'Room',
            'Unit',
            'Email',
            'User Name',
            'Rate',
            'Review',
            'Date',
        ];
    }

    public function collection()
    {
        $reviews = $this->reviews;
        return $reviews;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER, //No.
            'B' => NumberFormat::FORMAT_TEXT, // Order No
            'C' => NumberFormat::FORMAT_TEXT, // Room
            'D' => NumberFormat::FORMAT_TEXT, // Unit
            'E' => NumberFormat::FORMAT_TEXT, // Email
            'F' => NumberFormat::FORMAT_TEXT, // User Name
            'G' => NumberFormat::FORMAT_NUMBER, // Rate
            'H' => NumberFormat::FORMAT_TEXT, // Review
            'I' => NumberFormat::FORMAT_DATE_DATETIME, // Date Created
        ];
    }
}
