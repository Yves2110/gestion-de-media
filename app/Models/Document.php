<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable= ['user_id','source_id','thematique_id','title','code_document','auteur','page','edition','publication_date','categorie','picture','file_doc','resume','statut_publication','ask_form','localisation'];
    

    public function getCustomAttribute() {
        $thematiques = [];
        if ($thematique_ids = json_decode($this->thematique_id, true)) {
            foreach ($thematique_ids as $id) {
                $thematique = Thematique::find($id);
                if ($thematique) {
                    array_push($thematiques, $thematique);
                }
            }
        }
    
        return $thematiques;
    }

    public function source() {
        return $this->belongsTo(Source::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function scopeIdDescending($query){
        return $query->orderBy('created_at','desc');
    }
}
