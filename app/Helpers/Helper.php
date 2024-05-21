<?php

namespace App\Helpers;

class Helper
{
    public const CATEGORY_PATIENT = 1;
    public const CATEGORY_FAMILY = 2;
    public const CATEGORY_CONCIERGE = 3;
    public const CATEGORY_BUSINESS = 4;

    public const STATUS_NEW = 1;
    public const STATUS_PENDING = 3;
    public const STATUS_ACTIVE = [4, 5];
    public const STATUS_CONCLUDE = 6;
    public const STATUS_TOCLOSE = [2, 7, 11];
    public const STATUS_UNPAID = 9;

    /**
     * Get category id from the name of category
     *
     * @param string $category different category names.
     *
     * @return int different types of request_type_id.
     */
    public static function getCategoryId($category)
    {
        // mapping of category names to request_type_id
        $categoryMapping = [
            'patient' => self::CATEGORY_PATIENT,
            'family' => self::CATEGORY_FAMILY,
            'concierge' => self::CATEGORY_CONCIERGE,
            'business' => self::CATEGORY_BUSINESS,
        ];
        return $categoryMapping[$category] ?? null;
    }
    /**
     * Get status id from the name of status
     *
     * @param string $status different status names.
     *
     * @return int status in Id.
     */
    public static function getStatusId($status)
    {
        $statusMapping = [
            'new' => self::STATUS_NEW,
            'pending' => self::STATUS_PENDING,
            'active' => self::STATUS_ACTIVE,
            'conclude' => self::STATUS_CONCLUDE,
            'toclose' => self::STATUS_TOCLOSE,
            'unpaid' => self::STATUS_UNPAID,
        ];
        return $statusMapping[$status];
    }
}
