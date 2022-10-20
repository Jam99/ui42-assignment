<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'districts';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
