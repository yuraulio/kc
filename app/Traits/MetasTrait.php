<?php 

namespace App\Traits;
use App\Model\Metas;
use Eloquent;

trait MetasTrait
{
    
    public function createMetas(){
        
        $metas = new Metas;
        $metas->save();
        
        $this->metable()->save($metas);
        
    }
    
    /**
     * @return string
     */
    
     public function metable()
    {
        return $this->morphOne(Metas::class,'metable');
    }

}