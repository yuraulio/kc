<?php 

namespace App\Traits;
use App\Model\Benefit;
use Eloquent;

trait BenefitTrait
{
     
    /**
     * @return string
     */
    
    public function benefits()
    {
        return $this->morphToMany(Benefit::class, 'benefitable');
    }

}