<?php

use Brick\Math\BigNumber;
use Brick\Math\RoundingMode;
use Brick\Money\Context;
use Brick\Money\Currency;
use Brick\Money\Money;

if (!function_exists('money')) {
    /**
     * Returns a Money of the given amount.
     *
     * @see Brick\Money\Money::of
     */
    function money(
        BigNumber|int|float|string $amount,
        Currency|string|int|null $currency = null,
        ?Context $context = null,
        int $roundingMode = RoundingMode::UNNECESSARY
    ): Money {
        return Money::of($amount, $currency ?? config('app.currency'), $context, $roundingMode);
    }
}

if (!function_exists('max_amount_float_value')) {
    /**
     * Max supported float value for money amount by currency default fraction digits.
     */
    function max_amount_float_value(Currency|string|int|null $currency = null): float
    {
        $currency ??= config('app.currency');
        if (!$currency instanceof Currency) {
            $currency = Currency::of($currency);
        }
        return PHP_INT_MAX / (10 ** $currency->getDefaultFractionDigits());
    }
}
