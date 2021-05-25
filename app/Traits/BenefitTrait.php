<?php 

namespace App\Traits;
use App\Model\Benefit;
use Eloquent;

trait BenefitTrait
{
    
    public function attachBenefits(){
        
        $benefit= new Benefit;
        $benefit->save();
       
        $this->benefits()->save($benefit);
        
    }
    
    /**
     * @return string
     */
    
    public function benefits()
    {
        return $this->morphToMany(Benefit::class, 'benefitable');
    }

}