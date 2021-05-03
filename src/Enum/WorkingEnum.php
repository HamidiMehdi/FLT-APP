<?php

namespace App\Enum;

class WorkingEnum
{
    const DAY = 'DAY';
    const MORNING = 'AM';
    const AFTERNOON = 'PM';

    const PENDING_STATUS = 'pending';
    const ACCEPTED_STATUS = 'accepted';
    const REFUSED_STATUS = 'refused';
    const REPORT_STATUS = 'report';

    public static function getChoiceDate()
    {
        return [
            'Matin' => self::MORNING,
            'Après-midi' => self::AFTERNOON
        ];
    }

    public static function getMonthsName($month)
    {
        $months = [
            1 => 'Janvier',
            2 => 'Février',
            3 => 'Mars',
            4 => 'Avril',
            5 => 'Mai',
            6 => 'Juin',
            7 => 'Juillet',
            8 => 'Août',
            9 => 'Septembre',
            10 => 'Octobre',
            11 => 'Novembre',
            12 => 'Décembre'
        ];

        return !isset($month) ? $months : $months[intval($month)];
    }
}