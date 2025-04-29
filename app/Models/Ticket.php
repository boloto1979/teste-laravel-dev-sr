<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    const STATUS_ABERTO = 'aberto';
    const STATUS_EM_PROGRESSO = 'em_progresso';
    const STATUS_RESOLVIDO = 'resolvido';

    protected $fillable = [
        'title',
        'description',
        'status',
        'category_id',
        'created_by'
    ];

    protected $attributes = [
        'status' => self::STATUS_ABERTO,
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
