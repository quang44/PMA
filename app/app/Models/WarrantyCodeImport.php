<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WarrantyCodeImport implements ToCollection, WithHeadingRow, WithValidation, ToModel
{
    private $rows = 0;
    public function collection(Collection $rows)
    {
        $warrantyCode=WarrantyCode::pluck('code');
        foreach ($rows as $row) {
//            if(!in_array($row['code'],[$warrantyCode],true)){
                WarrantyCode::query()->updateOrCreate([
                    'code' => $row['code'],
                    'status' =>  $row['status']??0,
                    'use_at' =>  $row['use_at']??null,
                ]);
//            }
        }
        flash(translate('Warranty Code imported successfully'))->success();
    }

    public function model(array $row)
    {
        ++$this->rows;
    }

    public function rules(): array
    {
        return [
//            // Can also use callback validation rules
//            'unit_price' => function ($attribute, $value, $onFailure) {
//                if (!is_numeric($value)) {
//                    $onFailure('Unit price is not numeric');
//                }
//            }
        ];
    }
}
