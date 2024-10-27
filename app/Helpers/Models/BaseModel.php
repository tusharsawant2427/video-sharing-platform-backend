<?php


namespace App\Helpers\Models;


use App\Helpers\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model {
    use Uuid;

    public $timestamps = true;
    protected $guarded = ['id'];

}
