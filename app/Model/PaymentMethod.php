<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'has_installments',
        'visibility',
        'priority',
        'processor_id',
        'processor_config',
        'processor_options',
        //'test_processor',
        'test_processor_config',
        'test_processor_options',
        'method_name',
        'method_slug',
        'method_description',
        'method_instructions',
        'surcharge_name',
        'surcharge_percentage',
        'surcharge_amount',
        'type',

    ];

    public function getProcessorOptionsAttribute($value)
    {
        
        if ($value) {
            return json_decode(decrypt($value), true);
            //return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function getProcessorConfigAttribute($value)
    {
        if ($value) {
            return json_decode(decrypt($value), true);
            //return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function getTestProcessorOptionsAttribute($value)
    {
        
        if ($value) {
            return json_decode(decrypt($value), true);
            //return json_decode($value, true);
        } else {
            return [];
        }
    }

    public function getTestProcessorConfigAttribute($value)
    {
        if ($value) {
            return json_decode(decrypt($value), true);
            //return json_decode($value, true);
        } else {
            return [];
        }
    }
}
