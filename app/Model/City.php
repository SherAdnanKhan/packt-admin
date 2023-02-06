<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Http\Traits\LocaleableTrait;

class City extends Model
{
    use HasTranslations, LocaleableTrait;

    public $translatable = ['name', 'alias', 'abbr', 'full_name'];
    protected $fillable = [
        'name',
        'country_id',
        'state_id',
        'alias',
        'abbr',
        'full_name',
        'code',
    ];

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable('cities');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->translatable) && !is_array($value)) {
            return $this->setTranslation($key, app()->getLocale(), $value);
        }

        return parent::setAttribute($key, $value);
    }



}
