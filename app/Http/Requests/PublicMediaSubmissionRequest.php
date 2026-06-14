<?php

namespace App\Http\Requests;

use App\Rules\SafeMediaEmbed;
use App\Support\MediaInput;
use Illuminate\Foundation\Http\FormRequest;

class PublicMediaSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $type = (int) $this->input('type', 1);
        $requiresSubmitter = ! auth()->check() || ! auth()->user()->isAdmin();

        $rules = [
            'website' => 'nullable|max:0',
            'title' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'source_id' => 'required|integer|exists:sources,id',
            'thematique_id' => 'required|array|min:1',
            'thematique_id.*' => 'integer|exists:thematiques,id',
            'description' => 'nullable|string|max:5000',
            'media' => ['required', 'string', new SafeMediaEmbed($type)],
            'type' => 'required|in:0,1',
            'picture' => 'nullable|image|max:5120',
        ];

        if ($requiresSubmitter) {
            $rules['submitter_name'] = 'required|string|min:2|max:120';
            $rules['submitter_email'] = 'required|email|max:190';
        }

        return $rules;
    }

    public function attributes(): array
    {
        $isVideo = (int) $this->input('type', 1) === 1;

        return [
            'title' => 'titre',
            'auteur' => 'auteur',
            'source_id' => 'source',
            'thematique_id' => 'thématique',
            'media' => $isVideo ? 'lien vidéo YouTube' : 'lien audio',
            'submitter_name' => 'nom',
            'submitter_email' => 'email',
        ];
    }

    protected function prepareForValidation(): void
    {
        $type = (int) $this->input('type', 1);
        $media = $this->input('media');

        $this->merge([
            'media' => is_string($media) ? MediaInput::normalize($type, $media) : $media,
        ]);
    }
}
