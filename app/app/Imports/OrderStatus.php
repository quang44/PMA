<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class OrderStatus implements ToCollection
{

    /**
     * @inheritDoc
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            \App\Models\Imports\OrderStatus::create([
                'status' => $row[0]
            ]);
        }
    }
}
