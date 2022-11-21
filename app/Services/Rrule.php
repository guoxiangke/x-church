<?php

namespace App\Services;
use App\Models\User;
use App\Models\Service;
use Carbon\Carbon;

final class Rrule
{
    public String $rruleString;
    public $rule;

    //RRULE:FREQ=WEEKLY;COUNT=30;INTERVAL=1;WKST=SA
    public function __construct($startDate, $rruleString, $timezone=null)
    {
        $this->rruleString = $rruleString;
        $timezone = $timezone??config('app.timezone');
        $rruleString = substr($rruleString, 6); //remove  RRULE:

        return $this->rule = new \Recurr\Rule($rruleString, $startDate, null, $timezone);
    }

    public function toText()
    {
        $transformer = new \Recurr\Transformer\TextTransformer();
        $rruleText = $transformer->transform($this->rule);
        return $rruleText;
    }

    
    public function getNextEventDate()
    {
        $transformer = new \Recurr\Transformer\ArrayTransformer();
        $transformerConfig = new \Recurr\Transformer\ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $transformer->setConfig($transformerConfig);
        $rruleCollection = $transformer->transform($this->rule);
        $filteredCollection = $rruleCollection->filter(function ($recurrence) {
            if ($recurrence->getStart() > Carbon::now()) {
                return true;
            }
            return false;
        });
        return  $filteredCollection->first()->getStart();
    }
}
