<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sizes',
        'colors',
        'fitting',
        'fabric',
        'fabric_weight',
        'wash_type',
        'moq',
        'price',
        'article_no',
        'category',
        'type',
        'length',
        'slug',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function productImages(){
        return $this->hasMany(ProductImages::class, 'article_no', 'article_no');
    }
}
