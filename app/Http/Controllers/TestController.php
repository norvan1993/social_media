<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ListNode;

class TestController extends Controller
{
    public $positiveNums = [];
    public $negativeNums = [];
    public function test()
    {

        $nums = [];
        $target = 900000;
        //filling array with 100k elements
        for ($j = 0; $j < 100000; $j++) {
            array_push($nums, $j);
        }
        //setting the wanted two numbers near to each other
        //array_push($nums, 200000);
        //array_push($nums, 1000000);

        ////setting the wanted two numbers far from each other
        array_push($nums, 1000000);
        array_push($nums, -100000);


        $numsLength = sizeof($nums);
        $firstValue = $nums[0];
        unset($nums[0]);
        $this->insert_value_into_positive_or_negative_as_index($firstValue, 0);
        for ($x = 1; $x < $numsLength; $x++) {
            if ($firstValue + $nums[$x] === $target) {
                return [0, $x];
            }
            if ($nums[$x] >= 0) {
                if (isset($this->positiveNums[$nums[$x]])) {
                    if ($nums[$x] + $nums[$x] === $target) {
                        return [$this->positiveNums[$nums[$x]], $x];
                    }
                    continue;
                }
            }
            if ($nums[$x] < 0) {
                if (isset($this->negativeNums[$nums[$x]])) {
                    if ($nums[$x] + $nums[$x] === $target) {
                        return [$this->negativeNums[abs($nums[$x])], $x];
                    }
                    continue;
                }
            }
            $this->insert_value_into_positive_or_negative_as_index($nums[$x], $x);
            unset($nums[$x]);
        }

        return $this->getKeysOfSum($target);
    }
    public function insert_value_into_positive_or_negative_as_index($value, $key)
    {
        if ($value >= 0) {
            $this->positiveNums[$value] = $key;
        }
        if ($value < 0) {
            $this->negativeNums[abs($value)] = $key;
        }
    }
    public function getKeysOfSum($target)
    {
        while ($this->positiveNums) {
            $lastPositiveValue = end($this->positiveNums);
            $lastPositiveKey = key($this->positiveNums);
            unset($this->positiveNums[$lastPositiveKey]);
            $keys = $this->getPositivePartnerIfExist($lastPositiveKey, $lastPositiveValue, $target);
            if ($keys !== false) {
                return $keys;
            }
        }

        while ($this->negativeNums) {
            $lastNegativeValue = end($this->negativeNums);
            $lastNegativeKey = key($this->negativeNums);
            unset($this->negativeNums[$lastNegativeKey]);
            $keys = $this->getNegativePartnerIfExist($lastNegativeKey, $lastNegativeValue, $target);
            if ($keys !== false) {
                return $keys;
            }
        }
        return false;
    }
    public function getPositivePartnerIfExist($lastPositiveKey, $lastPositiveValue, $target)
    {
        if ($lastPositiveKey > $target) {
            $expectedNegative = $lastPositiveKey - $target;
            $expectedNegative = -1 * $expectedNegative;
            if (isset($this->negativeNums[$expectedNegative])) {
                return ($lastPositiveValue < $this->negativeNums[$expectedNegative] ? [$lastPositiveValue, $this->negativeNums[$expectedNegative]] : [$this->negativeNums[$expectedNegative], $lastPositiveValue]);
            }
        }

        if ($lastPositiveKey < $target) {
            $expectedPositive = $target - $lastPositiveKey;
            if (isset($this->positiveNums[$expectedPositive])) {
                return ($lastPositiveValue < $this->positiveNums[$expectedPositive] ? [$lastPositiveValue, $this->positiveNums[$expectedPositive]] : [$this->positiveNums[$expectedPositive], $lastPositiveValue]);
            }
        }
        if ($lastPositiveKey === $target) {

            if (isset($this->positiveNums[0])) {
                return ($lastPositiveValue < $this->positiveNums[0] ? [$lastPositiveValue, $this->positiveNums[0]] : [$this->positiveNums[0], $lastPositiveValue]);
            }
        }
        return false;
    }
    public function getNegativePartnerIfExist($lastNegativeKey, $lastNegativeValue, $target)
    {
        if (-1 * $lastNegativeKey > $target) {
            $expectedNegative = $target - (-1 * $lastNegativeKey);
            $expectedNegative = -1 * $expectedNegative;
            if (isset($this->negativeNums[$expectedNegative])) {
                return ($lastNegativeValue < $this->negativeNums[$expectedNegative] ? [$lastNegativeValue, $this->negativeNums[$expectedNegative]] : [$this->negativeNums[$expectedNegative], $lastNegativeValue]);
            }
        }
        return false;
    }
}
