<?php

$durationValuePosibility= array(15,20,50,30,40);

class Event {
    public $name;
    public $day;
    public $startMinute;
    public $duration;
    public $isEmptySlot;
  
    function __construct($name,$day,$startMinute,$duration,$isEmptySlot) {
        $this->name = $name;
        $this->day = $day;
        $this->startMinute = $startMinute;
        $this->duration = $duration;
        $this->isEmptySlot= $isEmptySlot;
    }

    function __toString(){
        return $this->name .' • '. $this->day. 'is the day •' . $this->startMinute/60 . 'is the start time •' . $this->duration . 'minutes \n';
    }

    public function getEndTime(){
        return $this->startMinute+$this->duration;
    }

    public static function getRandomDuration(){
        global $durationValuePosibility;
        return $durationValuePosibility[rand(0,4)];
    }

    public static function getRandomStartMinute($startHour,$endHour,$randomDuration){
        return rand($startHour*60,($endHour*60)-$randomDuration);
    }
  }
?>