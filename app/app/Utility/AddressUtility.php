<?php
namespace App\Utility;
use App\Models\Address;
class AddressUtility{

    const Region_one=1;
    const Region_two=2;
    const Region_three=3;

    public static $arrayRegion=[
        self::Region_one=>'Miền Bắc',
        self::Region_two=>'Miền Trung',
        self::Region_three=>'Miền Nam'
    ];

}
