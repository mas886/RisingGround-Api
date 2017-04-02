<?php

/**
 * Parser class
 *
 * @author mas886/redrednose/arnau
 */
class Parser {
    
    public function parseContent($stringToParse,$keyWord){
        $contentArray= explode("|", $stringToParse);
        foreach ($contentArray as $content){
            $secExplode= explode(":", $content);
            if($secExplode[0]==$keyWord){
                return explode(";",$secExplode[1]);
            }
        }
        return NULL;
    }
    
}
