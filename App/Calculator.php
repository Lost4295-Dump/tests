<?php
declare(strict_types=1);

namespace App;
class Calculator
{
    public function add(int $a, int $b): int
    {
        return $a + $b;

    }

    public function sub(int $a, int $b): int
    {
        return $a - $b;

    }

    public function mul(int $a, int $b): int
    {
        return $a * $b;

    }

    public function div(int $a, int $b): int
    {
        if ($b==0) {
            throw new \ArithmeticError();
        }
        return $a / $b;
    }

    public function avg(array $a)
    {
        $count = count($a);
        $sum = 0;
        foreach ($a as $val) {
            $sum += $val;
        }
        if ($count > 0) {
            return $sum / $count;
        }
        else {
            throw new \ArithmeticError();
        }
    }
}
