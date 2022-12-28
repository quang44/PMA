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

    //    type history
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

//    type notification
    const TYPE_NOTIFICATION_GIFT = 0;
    const TYPE_NOTIFICATION_WARRANTY = 1;
    const TYPE_NOTIFICATION_MAINTAIN = 2;
    const TYPE_NOTIFICATION_EVENT = 3;
//    const TYPE_NOTIFICATION_UPDATE = 4;

    const TYPE_NOTIFICATION_USER = 0;
    const TYPE_NOTIFICATION_ADMIN = 1;

    public static $arrayTypeNotification = [
        self::TYPE_NOTIFICATION_GIFT =>  'Thông báo đổi quà',
        self::TYPE_NOTIFICATION_WARRANTY => 'Thông báo bảo hành',
        self::TYPE_NOTIFICATION_MAINTAIN => 'Cập nhật hệ thống',
        self::TYPE_NOTIFICATION_EVENT => 'Thông báo sự kiện',
//        self::TYPE_NOTIFICATION_UPDATE=>'Thông báo nâng cấp'
    ];

}
