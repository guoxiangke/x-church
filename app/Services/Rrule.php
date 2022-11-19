<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rrule extends Model
{
    use HasFactory;


     public function getRule()
    {
        $timezone = config('app.timezone');
        $rruleString = $this->string;
        $startDate = $this->start_at;
        $rule = new \Recurr\Rule($rruleString, $startDate, null, $timezone);

        return $rule;
    }

    public function toText()
    {
        $transformer = new \Recurr\Transformer\TextTransformer();
        $rruleText = $transformer->transform($this->getRule());

        return $rruleText;
    }

    // get BYDAY as dayOfWeek
    public function getRrule($key = false)
    {//'BYDAY'
        // RRULE:FREQ=WEEKLY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=MO
        $rruleStrings = explode(';', $this->string);
        $rruleArray = [];
        foreach ($rruleStrings as $value) {
            $keyvalue = explode('=', $value);
            $rruleArray[$keyvalue[0]] = $keyvalue[1];
        }
        if ($key) {
            return $rruleArray[$key];
        }

        return $rruleArray;
    }

    // public function getRruleCollection(){
    //     $rule = $this->getRule();

    //     $transformer = new \Recurr\Transformer\ArrayTransformer();
    //     $transformerConfig = new \Recurr\Transformer\ArrayTransformerConfig();
    //     $transformerConfig->enableLastDayOfMonthFix();
    //     $transformer->setConfig($transformerConfig);
    //     $rruleCollection = $transformer->transform($rule);
    //     return  $rruleCollection;
    // }
    // public static function  getRruleCollection
    public static function transCollection(\Recurr\Rule $rule)
    {
        $transformer = new \Recurr\Transformer\ArrayTransformer();
        $transformerConfig = new \Recurr\Transformer\ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $transformer->setConfig($transformerConfig);

        return $transformer->transform($rule);
    }

    public static function transArray(\Recurr\Rule $rule)
    {
        return static::transCollection($rule)->toArray();
    }

    public static function transByStart(\Recurr\Rule $rule)
    {
        return static::transCollection($rule)->map(function ($recurrence) {
            return $recurrence->getStart()->format('Y-m-d H:i:s');
        });
    }

    //0:arrayToSave 1:collection
    public static function buildRrule($value, $returnRruleCollection = 0)
    {
        $timezone = config('app.timezone');
        // DTSTART;TZID=Asia/Hong_Kong:20190330T180000
        // DTSTART:20190330T180000Z
        // RRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=TU

        // https://www.kanzaki.com/docs/ical/exrule.html
        // EXRULE:FREQ=WEEKLY;COUNT=4;INTERVAL=2;BYDAY=TU,TH
        // https://www.kanzaki.com/docs/ical/exdate.html
        // EXDATE:19960402T010000Z,19960403T010000Z,19960404T010000Z

        // $value = "DTSTART:20190330T180000\nRRULE:FREQ=DAILY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=TU";

        // @see https://stackoverflow.com/questions/5053373/explode-a-string-by-r-n-n-r-at-once
        // dd($value);
        $rrules = preg_split('/\r\n?|\n/', $value); // $rrules = explode(PHP_EOL, $value);

        $startDateString = explode(':', $rrules[0])[1];

        $startDate = Carbon::createFromFormat('Ymd\THis\Z', $startDateString, $timezone);
        $rruleString = substr($rrules[1], 6); //remove  RRULE:
        // $timezone    = 'Asia/Hong_Kong';
        $rule = new \Recurr\Rule($rruleString, $startDate, null, $timezone);

        $transformer = new \Recurr\Transformer\ArrayTransformer();
        $transformerConfig = new \Recurr\Transformer\ArrayTransformerConfig();
        $transformerConfig->enableLastDayOfMonthFix();
        $transformer->setConfig($transformerConfig);
        $rruleCollection = $transformer->transform($rule);
        if ($returnRruleCollection) {
            return  $rruleCollection;
        }

        return [
            'start_at' => $startDate,
            // 'text' => $rruleText, //TransformerText
            'string' => $rruleString, //oriString
            // 'period' => $rruleCollection->count(),
        ];
    }
}
