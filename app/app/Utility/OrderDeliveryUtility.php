<?php

namespace App\Utility;

class OrderDeliveryUtility
{
    const MIN_FEE = 3000000;
    const TYPE_GH = 1;
    const TYPE_DH = 3;
    const TYPE_TH = 2;

    public static $aryType = [
        self::TYPE_GH => 'Đơn giao hàng',
        self::TYPE_DH => 'Đơn đổi hàng',
        self::TYPE_TH => 'Đơn thu hồi',
    ];

    const PICKUP_TYPE_OFFICE = 1;
    const PICKUP_TYPE_SHOP = 2;

    public static $aryPickUpType = [
        self::PICKUP_TYPE_OFFICE => 'Khách mang đến bưu cục',
        self::PICKUP_TYPE_SHOP => 'Nhân viên đến lấy hàng'
    ];

    const SERVICE_FAST = 12490;
    const SERVICE_BASIC = 12491;

    const STATUS_NEW = 0;
    const STATUS_FAIL = -1;
    const STATUS_SUCCESS = 1;
    const STATUS_CANCEL = 2;
    const STATUS_COLLECTED = 3;
    const STATUS_ON_WAY = 4;
    const STATUS_DELIVERY = 5;
    const STATUS_RETURN = 6;
    const STATUS_DELIVERED = 7;
    const STATUS_RETURNED = 8;
    const STATUS_LOST = 9;
    const STATUS_COLLECT_FAIL = 10;

    const REASON_CUSTOMER_COD = 1;
    const REASON_CUSTOMER_INSURANCE = 2;
    const REASON_CUSTOMER_DELIVERY = 3;
    const REASON_BEST_COD = 4;
    const REASON_BEST_INSURANCE = 5;
    const REASON_BEST_DELIVERY = 6;


    public static $aryStatusDelivery = [
        self::STATUS_NEW => 'Đơn lỗi',
        //self::STATUS_FAIL => 'Đơn lỗi',
        self::STATUS_SUCCESS => 'Đơn chờ lấy hàng',
        self::STATUS_COLLECT_FAIL => 'Đơn lấy hàng thất bại',
        self::STATUS_CANCEL => 'Đơn hủy',
        self::STATUS_COLLECTED => 'Đã lấy hàng',
        self::STATUS_ON_WAY => 'Đang vận chuyển',
        self::STATUS_DELIVERY => 'Đang giao hàng',
        self::STATUS_RETURN => 'Đang chuyển hoàn',
        self::STATUS_DELIVERED => 'Giao thành công',
        self::STATUS_RETURNED => 'Đã hoàn hàng',
        self::STATUS_LOST => 'Đơn mất, hỏng',
    ];


    const STATUS_PAYMENT_NEW = 1;
    const STATUS_PAYMENT_PENDING = 2;
    const STATUS_PAYMENT_CONFIRM = 3;
    const STATUS_PAYMENT_SUCCESS = 4;
    const STATUS_NOT_PAYMENT = 5;


    const PARTNER_STATUS_PAYMENT_NEW = 0;
    const PARTNER_STATUS_PAYMENT_SUCCESS = 1;

    public static $aryPartnerStatusPayment = [
        self::PARTNER_STATUS_PAYMENT_NEW => 'Chưa thanh toán',
        self::PARTNER_STATUS_PAYMENT_SUCCESS => 'Đã thanh toán'
    ];

    public static $aryStatusPayment = [
        //self::STATUS_PAYMENT_NEW => '--',
        self::STATUS_PAYMENT_PENDING => 'Chờ đối soát',
        self::STATUS_PAYMENT_CONFIRM => 'Đã đối soát',
        self::STATUS_PAYMENT_SUCCESS => 'Đã thanh toán',
        //self::STATUS_NOT_PAYMENT => 'Không cần thanh toán',
    ];



    const PARTNER_BEST_EXPRESS = 1;

    const ADDRESS_TYPE_SOURCE = 1;
    const ADDRESS_TYPE_DEST = 2;
    const ADDRESS_TYPE_RETURN = 3;

    public static $aryStatus = [
        "211" => "Đang đi lấy hàng",
        "203" => "Lấy hàng không thành công",
        "202" => "Lấy hàng thành công",
        "301" => "Nhận hàng vào bưu cục Nguồn",
        "302" => "Xuất hàng đến trung tâm khai thác",
        "303" => "Nhận hàng vào trung tâm khai thác",
        "304" => "Xuất hàng khỏi trung tâm khai thác",
        "309" => "Nhận hàng vào bưu cục phát hàng",
        "601" => "Xuất hàng để đi giao",
        "604" => "Giao hàng không thành công",
        "666" => "Giao hàng thành công",
        "605" => "Xác nhận chuyển hoàn",
        "701" => "Xuất hàng khỏi bưu cục phát để trả về",
        "702" => "Nhận hàng vào trung tâm khai thác để trả về",
        "703" => "xuất hàng khỏi trung tâm khai thác để trả về",
        "704" => "Nhận hàng vào bưu cục trả hàng",
        "705" => "Xuất hàng để trả về",
        "707" => "Trả hàng không thành công",
        "708" => "Trả hàng thành công",
        "777" => "Thất lạc",
        "1000" => "Hư hỏng",
        "2" => "Hủy đơn",
    ];
}
