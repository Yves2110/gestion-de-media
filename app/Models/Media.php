<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','source_id','thematique_id','title','description','auteur','code_media','statut','type','media'];
    

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

    public function role() {
        return $this->belongsTo(Role::class);
    }
    public function scopeIdDescending($query){
        return $query->orderBy('created_at','desc');
    }
    public function scopeIsAudio($query){
        return $query->where('type',0);
    }
    public function scopeIsvideo($query){
        return $query->where('type',1);
    }
}
