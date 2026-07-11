<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'email',
        'password',
        'status',
        'owner_id',           // ✅ Добавляем owner_id
        'owner_telegram_id',
        'address',
    ];

    protected $hidden = [
        'password',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);

                // Проверяем уникальность slug
                $originalSlug = $company->slug;
                $count = static::where('slug', 'LIKE', "{$company->slug}%")->count();

                if ($count > 0) {
                    $company->slug = "{$originalSlug}-" . ($count + 1);
                }
            }
        });
    }
}