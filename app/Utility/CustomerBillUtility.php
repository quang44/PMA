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

    const TYPE_LOG_RECHARGE = 0;
    const TYPE_LOG_WITHDRAW = 1;
    const TYPE_LOG_ADDITION=2;
    const TYPE_LOG_DEDUCTION = 3;

    public static $arrayTypeLog = [
        self::TYPE_LOG_RECHARGE => 'Nạp tiền',
        self::TYPE_LOG_WITHDRAW => 'Rút tiền',
        self::TYPE_LOG_ADDITION => 'Cộng tiền',
        self::TYPE_LOG_DEDUCTION => 'Trừ tiền',
    ];

}
