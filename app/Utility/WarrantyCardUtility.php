<?php

    namespace App\Utility;

    class WarrantyCardUtility
    {
        const STATUS_NEW = 0;
        const STATUS_SUCCESS = 1;
        const STATUS_CANCEL = 2;

        public static $aryStatus = [
            self::STATUS_NEW => 'Chờ duyệt',
            self::STATUS_SUCCESS => 'Đã duyệt',
            self::STATUS_CANCEL => 'Đã hủy',
        ];
    }
