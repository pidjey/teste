<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Produto extends Model
{
    use HasFactory;

    public function loja(){
        return $this->belongsTo(Loja::class);
    }

    protected function valor(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $value = strval($value);
                $len = strlen($value);
                if($len == 2){
                    return "R$ 00,$value";
                }
                if($len == 1){
                    return "R$ 00,0$value";
                }
                if($len == 3){
                    return "R$ 0" . substr($value, 0, -2) . "," . substr($value, -2);
                }
                if($len > 3){
                    return "R$ " . substr($value, 0, -2) . "," . substr($value, -2);
                }
                return "R$ 00,00";
            },
        );
    }
}
