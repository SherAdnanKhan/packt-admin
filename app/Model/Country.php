<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
//use App\Http\Traits\LocaleableTrait;

class Country extends Model
{
    use HasTranslations;
    //, LocaleableTrait;

    public $translatable = ['name', 'alias', 'abbr', 'full_name', 'capital', 'currency_name'];
    protected $fillable = [
        'name',
        'alias',
        'abbr',
        'full_name',
        'code',
        'continent_id',
        'capital',
        'code_alpha3',
        'emoji',
        'has_state',
        'currency',
        'currency_name',
        'tld',
        'callingcode',
    ];
    public $timestamps = false;

    
    public function states()
    {
        return $this->hasMany(State::class, 'country_id');
    }
    
    public function cities()
    {
        return $this->hasMany(City::class, 'country_id');
    }

    public function continent()
    {
        return $this->belongsTo(Continent::class, 'continent_id');
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->translatable) && !is_array($value)) {
            return $this->setTranslation($key, app()->getLocale(), $value);
        }

        return parent::setAttribute($key, $value);
    }


}
