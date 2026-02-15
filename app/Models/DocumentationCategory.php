<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentationCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'order'];

    public function pages()
    {
        return $this->hasMany(DocumentationPage::class, 'category_id')->orderBy('title');
    }
}
