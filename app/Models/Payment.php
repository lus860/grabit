<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'method',
        'img',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

    public $timestamps = false;
    public function order(){
        return $this->hasOne(Order::class);
    }
}