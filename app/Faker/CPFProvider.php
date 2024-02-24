<?php

namespace App\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Collection;

class CPFProvider extends Base
{
    public function cpf(): string
    {
        return $this
            ->addVerificatorDigits(
                collect(range(1, 9))->map(fn () => rand(0, 9))
            )
            ->join('');
    }

    private function addVerificatorDigits(Collection $cpf): Collection
    {
        if ($cpf->count() > 10) {
            return $cpf;
        }

        $digit = 11 - (
            collect(
                array_reverse($cpf->slice($cpf->count() - 9, 9)->all())
            )->reduce(
                fn ($return, $value, $index) => $return + $value * ($index + 2)
            ) % 11
        );

        return $this->addVerificatorDigits(
            $cpf->add($digit > 9 ? 0 : $digit)
        );
    }
}
