<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WarrantyCardImport implements ToCollection, WithHeadingRow, WithValidation, ToModel
{
    private $rows = 0;
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            WarrantyCode::create([
                'username' => $row['username'],
                'phone'=>$row['phone'],
                'address'=>$row['address'],
                'product'=>$row['product'],
                'color'=>$row['color'],
                'quantity'=>$row['quantity'],
                'image'=>$row['image'],
                'video'=>$row['video'],
                'warrantyCode'=>$row['warrantyCode'],
            ]);
        }
        flash(translate('Warranty card imported successfully'))->success();
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
