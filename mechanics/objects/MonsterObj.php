<?php

/**
 * Monster object class to help the class battle.
 *
 * @author mas886/redrednose/arnau
 */

include_once("./class/Monster.php");

class monsterObj {
    
    protected $accuracy;
    protected $speed;
    protected $strength;
    protected $vitality;
    protected $defence;
    protected $team;
    
    function __construct($monsterId,$team) {
        $monster=new Monster;
        $statsArray=$monster->getMonster($monsterId);
        $this->accuracy = (float)$statsArray['accuracy'];
        $this->speed =  (float)$statsArray['speed'];
        $this->strength = (float)$statsArray['strength'];
        $this->vitality = (float)$statsArray['vitality'];
        $this->defence = (float)$statsArray['defence'];
        $this->team=$team;
    }
    
    function getAccuracy() {
        return $this->accuracy;
    }

    function getSpeed() {
        return $this->speed;
    }

    function getStrength() {
        return $this->strength;
    }

    function getVitality() {
        return $this->vitality;
    }

    function getDefence() {
        return $this->defence;
    }
    
    function getTeam() {
        return $this->team;
    }

    function setAccuracy($accuracy) {
        $this->accuracy = $accuracy;
    }

    function setSpeed($speed) {
        $this->speed = $speed;
    }

    function setStrength($strength) {
        $this->strength = $strength;
    }

    function setVitality($vitality) {
        $this->vitality = $vitality;
    }

    function setDefence($defence) {
        $this->defence = $defence;
    }

    function setTeam($team) {
        $this->team = $team;
    }

    function damage($damage){
        $this->setVitality($this->vitality-$damage);
    }
    
}
