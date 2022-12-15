<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{User};

class ShortUrls extends Model
{
    use HasFactory;

    protected $table = "short_urls";
    protected $primaryKey = "id"; 
    protected $fillable = ['short_url', 'user_id', 'og_url'];
    protected $hidden = ['created_at', 'updated_at'];



    public function insertEmp(array $tobeInserted){
        return self::insertGetId($tobeInserted);
    }

    // public function shortUrls(){
        // return hasMany(Users::class, )
    // }

    public function shortUrls(){
        
        return $this->hasMany(User::class, 'id');

    }

}
