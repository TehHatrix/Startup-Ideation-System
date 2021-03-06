<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSegment extends Model
{
    use HasFactory;
    // protected $table= 'CustomerProblem'; 
    // protected $primaryKey = 'cs_ID';
    // protected $fillable = [
    //     'cs_ID',
    //     'canvas_ID',
    //     'topic',
    //     'description',
    //     'publisher',
    // ];
    protected $fillable = ['topic', 'publisher_id'];

    // TODO change back to default lean content

    public function canvas() {
        return $this->belongsTo(LeanCanvas::class);
    }

    public function custproblem(){
        return $this->hasMany(CustomerProblem::class,'cs_ID');
    }

    public function problem() {
        return $this->hasMany(Problem::class);
    }

    public function solution() {
        return $this->hasMany(Solution::class);
    }
}
