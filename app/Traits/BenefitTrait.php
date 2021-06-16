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
        return $this->morphToMany(Benefit::class, 'benefitable')->orderBy('priority');
    }

    public function orderBenefits($benefitss = []){

    
        foreach($this->benefits as $key => $benefit){

            
            $benefit->priority = $benefitss[$benefit['id']];
            $benefit->save();

        }
    
       
        
    }

}