<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'observations',
        'status',
        'user_id',
    ];

    /**
     * Get the user that created the contact.
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }
}
