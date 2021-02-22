<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAddress extends Model {

    protected $fillable = ['email_address'];

    public function scopeSearch($query, $search) {

        $email_addresses = $this->where('email_address', 'LIKE', "%$search%")
            ->orderBy('email_address');

        return $email_addresses;

    }		

}

?>
