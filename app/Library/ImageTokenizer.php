<?php

namespace App\Library;

use TeamTNT\TNTSearch\Support\AbstractTokenizer;
use TeamTNT\TNTSearch\Support\TokenizerInterface;

class ImageTokenizer extends AbstractTokenizer implements TokenizerInterface
{
    protected static $pattern = '/[\s,\.-]+/';

    public function tokenize($text, $stopwords = [])
    {
        return preg_split($this->getPattern(), strtolower($text), -1, PREG_SPLIT_NO_EMPTY);
    }
}
