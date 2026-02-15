<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentationPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category_id',
        'parent_id',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(DocumentationCategory::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(DocumentationPage::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(DocumentationPage::class, 'parent_id');
    }
}
