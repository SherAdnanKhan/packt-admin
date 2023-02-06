<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Http\Traits\LocaleableTrait;

class Continent extends Model
{
    use HasTranslations, LocaleableTrait;

    public $translatable = ['name', 'alias', 'abbr', 'full_name'];
    protected $fillable = [
        'name',
        'alias',
        'abbr',
        'full_name',
        'code',
    ];
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable('continents');
    }

    public function countries()
    {
        return $this->hasMany(Country::class, 'continent_id');
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->translatable) && !is_array($value)) {
            return $this->setTranslation($key, app()->getLocale(), $value);
        }

        return parent::setAttribute($key, $value);
    }
}
