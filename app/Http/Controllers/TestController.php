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

        //value of $a is 0 by default ...changing to 1 to break from the outer loop
        $a = 0;

        //timer start to check the speed of loop
        $start_time = microtime(true);

        $numsLength = sizeof($nums);


        $this->insert_value_into_positive_or_negative_as_index($nums[0], 0);
        for ($x = 1; $x < $numsLength; $x++) {
            if ($nums[0] + $nums[$x] === $target) {
                print_r([0, $x]);
                $a = 1;
                break;
            }
            if ($nums[$x] >= 0) {
                if (isset($this->positiveNums[$nums[$x]])) {
                    print_r([$this->positiveNums[$nums[$x]], $x]);
                    $a = 1;
                    break;
                }
            }
            if ($nums[$x] < 0) {
                if (isset($this->negativeNums[$nums[$x]])) {
                    print_r([$this->negativeNums[abs($nums[$x])], $x]);
                    $a = 1;
                    break;
                }
            }
            $this->insert_value_into_positive_or_negative_as_index($nums[$x], $x);
        }

        if ($a !== 1) {
            echo "hello";
            print_r($this->getKeysOfSum($target));
        }


        // End clock time in seconds
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time);

        echo " Execution time of script = " . $execution_time . " sec";
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
