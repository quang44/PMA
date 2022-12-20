<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class AddressSearch extends Model
{
    use Searchable;

    protected $table = 'address_search';

    public function toSearchableArray()
    {
        $array = $this->only('name');
        return $array;
    }

}
