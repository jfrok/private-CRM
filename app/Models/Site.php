<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
protected $fillable = [
    'customer_id',
    'title',
    'date',
    'type',
    'thumbnail'
];
public function customer()
{
    return $this->belongsTo(Customer::class);

}
    public function project()
    {
        return $this->belongsTo(Project::class);

    }
public function contents()
{
    return $this->hasMany(SiteContent::class);

}
//    public function contentd()
//    {
//        return $this->belongsTo(SiteContent::class);
//
//    }
//    static function create($data) {
//        $new = new Site();
//        $new->customer_id = $data['customer_id'];
//
//        $new->save();
//
//        return true;
//    }
}
