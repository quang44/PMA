<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class WarrantyCodeImport implements ToModel, WithHeadingRow,WithBatchInserts,WithUpsertColumns,WithChunkReading
{
//    private $rows = 0;
    public function model(array $rows)
    {
             unset($rows['']);
            if(!WarrantyCode::query()->where('code',$rows['code'])->exists()){
                return new  WarrantyCode([
                    'code'=>$rows['code']
                ]);
            }
    }


    public function batchSize(): int
    {
        return 1000;
    }

    public function upsertColumns()
    {
        return 'code';
    }

    public function chunkSize(): int
    {
        return 1000;
    }


}
