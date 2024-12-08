<?php

namespace App\Helper;

use DateTime;

class ToDoListHelper
{
    private $englishDays = [
        'lundi'    => 'Monday',
        'mardi'    => 'Tuesday',
        'mercredi' => 'Wednesday',
        'jeudi'    => 'Thursday',
        'vendredi' => 'Friday',
        'samedi'   => 'Saturday',
        'dimanche' => 'Sunday'
    ];

    public function buildFilteredQuery(array $filters)
    {
        $filteredQuery = "";

        foreach ($filters as $key => $value) {
            if ($key === "date") {
                $monthAndYearDates = $this->getDateByDay($value);

                $filteredQuery .= " AND (
                                        task.type = 'journalier'
                                        OR (task.type = 'hebdomadaire' AND task.value LIKE '%{$value}%')
                                        OR (task.type = 'mensuel' AND task.value = '{$monthAndYearDates['month']}')
                                        OR (task.type = 'annuel' AND task.value = '{$monthAndYearDates['year']}')
                                    )";
                break;
            }
            
            $filteredQuery .= " AND {$key} = '{$value}'";
        }

        return $filteredQuery;
    }

    public function getDateByDay(string $day)
    {
        $date = (new DateTime("this week"))->modify($this->englishDays[$day]);

        return [
            "month" => $date->format("d"),
            "year" => $date->format("d-m")
        ];
    }
}