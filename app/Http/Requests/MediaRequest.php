<?php

namespace App\Http\Requests;

use App\Rules\SafeMediaEmbed;
use App\Support\MediaInput;
use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules()
    {
        $type = (int) $this->input('type', 1);

        return [
            'title' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'source_id' => 'required|integer|exists:sources,id',
            'thematique_id' => 'required|array|min:1',
            'thematique_id.*' => 'integer|exists:thematiques,id',
            'description' => 'nullable|string|max:5000',
            'media' => ['required', 'string', new SafeMediaEmbed($type)],
            'type' => 'required|in:0,1',
        ];
    }

    public function attributes(): array
    {
        $isVideo = (int) $this->input('type', 1) === 1;

        return [
            'title' => 'titre',
            'auteur' => 'auteur',
            'source_id' => 'source',
            'thematique_id' => 'thématique',
            'thematique_id.*' => 'thématique',
            'media' => $isVideo ? 'lien vidéo YouTube' : 'lien audio',
            'description' => 'description',
        ];
    }

    public function messages(): array
    {
        $isVideo = (int) $this->input('type', 1) === 1;

        return [
            'title.required' => 'Indiquez un titre pour ce contenu.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'auteur.required' => 'Indiquez l\'auteur ou l\'organisme à l\'origine du contenu.',
            'auteur.max' => 'Le nom de l\'auteur ne peut pas dépasser 255 caractères.',
            'source_id.required' => 'Sélectionnez une source dans la liste déroulante.',
            'source_id.integer' => 'La source choisie est invalide.',
            'source_id.exists' => 'La source sélectionnée n\'existe plus. Choisissez une autre source.',
            'thematique_id.required' => 'Cochez au moins une thématique.',
            'thematique_id.min' => 'Cochez au moins une thématique.',
            'thematique_id.array' => 'Sélectionnez au moins une thématique valide.',
            'thematique_id.*.exists' => 'Une des thématiques sélectionnées n\'existe plus.',
            'media.required' => $isVideo
                ? 'Collez le lien YouTube ou le code d\'intégration de la vidéo.'
                : 'Collez l\'URL HTTPS du fichier audio ou une balise <audio>.',
            'description.max' => 'La description ne peut pas dépasser 5000 caractères.',
        ];
    }

    protected function prepareForValidation()
    {
        $type = (int) $this->input('type', 1);
        $media = $this->input('media');

        $this->merge([
            'user_id' => auth()->id(),
            'media' => is_string($media) ? MediaInput::normalize($type, $media) : $media,
        ]);
    }
}
