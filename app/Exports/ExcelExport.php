<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomProperties;
use Maatwebsite\Excel\Concerns\WithProperties;

class ExcelExport implements FromCollection
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $row = array_fill(0, 5, '*'); // Fill the first 5 columns with '*'
        $row = array_merge($row, $this->data); // Add the data array to the row

        return new Collection([$row]);
    }


    /**
     * @return array
     */
    public function properties(): array
    {
        return $this->properties;
    }
}
