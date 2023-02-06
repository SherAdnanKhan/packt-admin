<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Http\Traits\LocaleableTrait;

class State extends Model
{
    use HasTranslations, LocaleableTrait;

    public $translatable = ['name', 'alias', 'abbr', 'full_name'];
    protected $fillable = ['name', 'alias', 'abbr', 'full_name', 'code', 'country_id', 'has_city'];
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable('states');
    }

    public function cities()
    {
        return $this->hasMany(City::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->translatable) && !is_array($value)) {
            return $this->setTranslation($key, app()->getLocale(), $value);
        }

        return parent::setAttribute($key, $value);
    }
}
