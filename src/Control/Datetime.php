<?php

namespace UI\Control;

use FFI\CData;
use UI\Control;

class Datetime extends Control
{
    const CTL_NAME = 'datetime';
    public function newControl(): CData
    {
        switch ($this->attr['type']) {
            case 'time':
                $this->instance = self::$ui->newTimePicker();
                break;
            case 'date':
                $this->instance = self::$ui->newDatePicker();
                break;
            case 'datetime':
                $this->instance = self::$ui->newDateTimePicker();
        }
        if ($this->attr['change']) {
            $this->onChange($this->attr['change']);
        }
        return $this->instance;
    }

    public function getValue()
    {
        $time = self::$ui->newTm(true);
        $this->dateTimePickerTime($time);
        return [
            'tm_sec' => $time[0]->tm_sec,
            'tm_min' => $time[0]->tm_min,
            'tm_hour' => $time[0]->tm_hour,
            'tm_mday' => $time[0]->tm_mday,
            'tm_mon' => $time[0]->tm_mon,
            'tm_year' => $time[0]->tm_year + 1900,
            'tm_wday' => $time[0]->tm_wday,
            'tm_yday' => $time[0]->tm_yday,
            'tm_isdst' => $time[0]->tm_isdst,
            'tm_gmtoff' => $time[0]->tm_gmtoff,
            'tm_zone' => self::$ui->string($time[0]->tm_zone),
        ];
    }

    public function setValue(array $v)
    {
        $time = self::$ui->newTm();
        $localtime = localtime();
        $timezone = date_default_timezone_get();
        $time->tm_sec = $v['tm_sec'] ?? $localtime['tm_sec'];
        $time->tm_min = $v['tm_min'] ?? $localtime['tm_min'];
        $time->tm_hour = $v['tm_hour'] ?? $localtime['tm_hour'];
        $time->tm_mday = $v['tm_mday'] ?? $localtime['tm_mday'];
        $time->tm_mon = $v['tm_mon'] ?? $localtime['tm_mon'];
        $time->tm_year = $v['tm_year'] ?? $localtime['tm_year'];
        $time->tm_wday = $v['tm_wday'] ?? $localtime['tm_wday'];
        $time->tm_yday = $v['tm_yday'] ?? $localtime['tm_yday'];
        $time->tm_isdst = $v['tm_isdst'] ?? $localtime['tm_isdst'];
        $time->tm_gmtoff = $v['tm_gmtoff'] ?? (new DateTime("now", new \DateTimeZone($timezone)))->getOffset();
        $time->tm_zone = $v['tm_zone'] ?? $timezone;
        $this->dateTimePickerSetTime(self::$ui->addr($time));
    }

    public function onChange($callable)
    {
        $this->bindEvent('dateTimePickerOnChanged', $callable);
    }
}
