<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieFAQ extends Model
{
    protected $table ="categorie_faq";
    public $timestamps=false;

    /**
     * Get all of the sujets_faq for the CategorieFAQ
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sujets_faq(): HasMany
    {
        return $this->hasMany(FAQ::class, 'id_categorie');
    }
}
