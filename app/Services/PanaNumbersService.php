<?php

namespace App\Services;

class PanaNumbersService
{

    public function getPanaNumbersList()
    {
        $panaNumbers = array();
        for ($i = 100; $i <= 999; $i++) {
            $digits = str_split($i);
            if ($digits[1] >= $digits[0] && ($digits[2] >= $digits[1] || $digits[2] == 0)) {
                array_push($panaNumbers, strval($i));
            }
        }
        $panaNumbers = array_merge($panaNumbers, ["100", "200", "300", "400", "500", "600", "700", "800", "900", "000"]);
        return $panaNumbers;
    }

    public function getPanaNumber($digit)
    {
        $panaNumbers = $this->getPanaNumbersList();
        foreach ($panaNumbers as $number) {
            $digits = str_split($number);
            if (array_sum($digits) % 10 == $digit) {
                return  strval($number);
            }
        }
        return 0;
    }

    public function getDigitFromPana($pana)
    {
        $digits = str_split($pana);
        $digit =  (string) $digits[0] + (string) $digits[1] + (string) $digits[2];
        return (string)$digit % 10;
    }
}
