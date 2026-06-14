<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MinWords implements Rule
{
    public function __construct(private int $min = 250) {}

    public function passes($attribute, $value): bool
    {
        return $this->countWords((string) $value) >= $this->min;
    }

    public function message(): string
    {
        return "La description doit contenir au moins {$this->min} mots.";
    }

    public static function countWords(string $text): int
    {
        $text = strip_tags(trim($text));
        if ($text === '') {
            return 0;
        }

        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);

        return $words ? count($words) : 0;
    }
}
