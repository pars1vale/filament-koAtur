<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'company_name', 
        'company_email', 
        'company_phone', 
        'site_logo', 
        'default_currency_id', 
        'default_currency_position', 
        'notification_email', 
        'footer_text', 
        'company_address'
    ];
}
