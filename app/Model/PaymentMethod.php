<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    const DEFAULT_PAYMENT_METHOD = 2; // (Knowcrunch Inc (USA))

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
        'footer',
        'payment_email',
        'prefix',
        'company_name',

    ];

    public function getProcessorOptionsAttribute($value): array
    {
        return $this->decryptAndDecodeValue($value);
    }

    public function getProcessorConfigAttribute($value): array
    {
        return $this->decryptAndDecodeValue($value);
    }

    public function getTestProcessorOptionsAttribute($value): array
    {
        return $this->decryptAndDecodeValue($value);
    }

    public function getTestProcessorConfigAttribute($value): array
    {
        return $this->decryptAndDecodeValue($value);
    }

    private function decryptAndDecodeValue(?string $value): array
    {
        return json_decode(decrypt($value), true);
    }
}
