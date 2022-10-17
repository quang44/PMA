<?php

namespace App\Utility;

class CustomerBillUtility
{


    const STATUS_NEW = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_CANCEL = 2;

    public static $aryStatus = [
        self::STATUS_NEW => 'Chưa thanh toán',
        self::STATUS_SUCCESS => 'Đã thanh toán',
        self::STATUS_CANCEL => 'Hủy thanh toán',
    ];
}
