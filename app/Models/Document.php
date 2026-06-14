<?php

namespace App\Models;

use App\Models\Concerns\HasPublicUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory, HasPublicUuid;

    protected $fillable = [
        'user_id', 'source_id', 'thematique_id', 'title', 'code_document', 'auteur',
        'page', 'edition', 'publication_date', 'category_id', 'categorie', 'picture', 'file_doc',
        'resume', 'statut_publication', 'ask_form', 'localisation',
        'is_guest_submission', 'submitter_name', 'submitter_email',
    ];

    protected $casts = [
        'statut_publication' => 'boolean',
        'ask_form' => 'boolean',
        'is_guest_submission' => 'boolean',
        'publication_date' => 'date',
    ];

    public function getCustomAttribute()
    {
        $thematiques = [];
        if ($thematique_ids = json_decode($this->thematique_id, true)) {
            foreach ($thematique_ids as $id) {
                $thematique = Thematique::find($id);
                if ($thematique) {
                    $thematiques[] = $thematique;
                }
            }
        }

        return $thematiques;
    }

    public function getPictureUrlAttribute(): ?string
    {
        return $this->picture ? asset('storage/picture/' . $this->picture) : null;
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thematiques()
    {
        return $this->belongsToMany(Thematique::class, 'document_thematique');
    }

    public function scopeIdDescending($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
