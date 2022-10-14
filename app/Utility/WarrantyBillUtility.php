<?php

    namespace App\Utility;

    class WarrantyBillUtility
    {
        const STATUS_NEW = 0;
        const STATUS_SUCCESS = 1;
        const STATUS_CANCEL = 2;

        public static $aryStatus = [
            self::STATUS_NEW => 'Chưa duyệt đơn',
            self::STATUS_SUCCESS => 'Đã duyệt đơn',
            self::STATUS_CANCEL => 'Hủy đơn',
        ];
    }
