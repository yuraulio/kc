<?php

namespace App\Library;

use TeamTNT\TNTSearch\Support\AbstractTokenizer;
use TeamTNT\TNTSearch\Support\TokenizerInterface;

class ImageTokenizer extends AbstractTokenizer implements TokenizerInterface
{
    protected static $pattern = '/[\_\s,\.-]+/';

    public function tokenize($text, $stopwords = [])
    {
        if(is_object($text)){
            $text = json_encode($text);
        }elseif(is_array($text)){
            $text = json_encode($text);
        }else{
            $text = strval($text);
        }
        return preg_split($this->getPattern(), strtolower((string)$text), -1, PREG_SPLIT_NO_EMPTY);
    }
}
