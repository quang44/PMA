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
use Maatwebsite\Excel\Concerns\WithValidation;

class WarrantyCodeImport implements ToModel, WithHeadingRow,WithUpsertColumns,WithBatchInserts
{
//    private $rows = 0;
    public function model(array $rows)
    {
        unset($rows['']);
//        dd($rows);
          $warrantyCode=WarrantyCode::query()->where('code',$rows['code'])->first();
              if(!$warrantyCode && isset($rows['code'])){
                  WarrantyCode::query()->create([
                      'code' => $rows['code'],
//                      'status' =>  $rows['status']??0,
//                      'use_at' =>  $rows['use_at']??null,
                  ]);
              }
//              return back();
    }


//    public function rules(): array
//    {
//        return [
//            'code' => 'unique:warranty_codes',
//        ];
//    }
//
//    public function customValidationMessages()
//    {
//        return [
//            'code.unique' => 'Số điện thoại đã tồn tại ',
//        ];
//    }


    public function batchSize(): int
    {
        return 1000;
    }


    public function upsertColumns()
    {
        return ['code'];
    }
}
