<?php

namespace App\Rules;

use App\Support\MediaInput;
use Illuminate\Contracts\Validation\Rule;

class SafeMediaEmbed implements Rule
{
    public function __construct(private int $type = 1)
    {
    }

    public function passes($attribute, $value): bool
    {
        if (! is_string($value) || trim($value) === '') {
            return false;
        }

        return $this->type === 1
            ? MediaInput::isValidVideo($value)
            : MediaInput::isValidAudio($value);
    }

    public function message(): string
    {
        if ($this->type === 1) {
            return 'Lien YouTube non reconnu. Utilisez une URL du type https://www.youtube.com/watch?v=…, https://youtu.be/… ou le code iframe copié depuis YouTube (Partager › Intégrer).';
        }

        return 'Lien audio non valide. Utilisez une URL HTTPS directe vers un fichier (.mp3, .wav, .ogg) ou une balise <audio controls src="https://…">.';
    }
}
