<?php


include "Event.php";

//constants
$startHour = 8;
$endHour = 15;
$numberOfDays = 7;
//

$eventArray = [];
$emptySlots = [];

// echo "<img src=\"https://i.ytimg.com/vi/3qlRNWBWr5o/maxresdefault.jpg\">";

for($i=1;$i<=$numberOfDays;$i++){
    //add 2 random events
    for($j=1;$j<=2;$j++){
        //declare everything
        $randomDuration= Event::getRandomDuration();
        $randomStartTimeInMinutes = Event::getRandomStartMinute($startHour,$endHour,$randomDuration);
        //


        $eventArray[] = new Event("event ".($i+$j),  $i,$randomStartTimeInMinutes,$randomDuration,false);  

        //check if other event is in that timestamp and if yes try another start time
        forEach($eventArray as $event){
            if($event!==$eventArray[count($eventArray)-1]){
                while(areEventsOverlaping($event,$eventArray[count($eventArray)-1])){
                    $eventArray[count($eventArray)-1]->startMinute=
                        Event::getRandomStartMinute($startHour,$endHour,$randomDuration);
                }
            }
        }
        //
    }
    //


    //populate with empty slots

    //get this day events
    $thisDayEvents = array_values(array_filter($eventArray,function($event){
        global $i;
        return $event->day==$i;
    }));

    usort($thisDayEvents, function($a, $b) {
        return $a->startMinute <=> $b->startMinute;
    });

    //

    $eventDuration = Event::getRandomDuration();

    for($j=-1;$j<count($thisDayEvents);$j++){
        //if at the first index the start time is the start of the day, else is the end time of the
        //curent event
        //if at the last index the end time is the end of the day, else is the start time of the next
        //event
        $freeTimeStartMinute = $j==-1?($startHour*60):$thisDayEvents[$j]->getEndTime();
        $freeTimeEndMinute = ($j+1)==count($thisDayEvents)?($endHour*60):$thisDayEvents[$j+1]->startMinute;
        //

        // $emptySlots[]=new Event
        //     ("Empty slot",$i,$freeTimeStartMinute,$freeTimeEndMinute-$freeTimeStartMinute,true);
        for($k =0 ; $k <(($freeTimeEndMinute-$freeTimeStartMinute)/$eventDuration)-1 ;$k++){
            $emptySlots[]=new Event("Empty slot",$i,($freeTimeStartMinute+($k*$eventDuration)),$eventDuration,true);
        }

    }

    //
}

function areEventsOverlaping($event1,$event2){
    if($event1->day!==$event2->day)return false;
    //they are overlaping if the start or end time of $event1 is in between the start and end time of 
    //$event2

    //is start time in between
    if(($event1->startMinute>=$event2->startMinute) and ($event1->startMinute<=$event2->getEndTime())){
        return true;
    }

    //is end time in between
    if(($event1->getEndTime()>=$event2->startMinute) and ($event1->getEndTime()<=$event2->getEndTime())){
        return true;
    }

    return false;
}


$htmlString ='<div style=\"background-color:blue; width:100vw;\">';

for($i = $startHour ; $i<=$endHour ; $i++){
    $top = $i*60-($startHour*60);
    echo "<h5 style=\"position:absolute; top:$top;\">$i:00</h5>";
}

foreach((array_merge($emptySlots,$eventArray)) as $event){
    //echo (string)$event;

    $percent = 100/($numberOfDays);
    $right=(100-($percent)*($event->day-1)-$percent).'vw';
    $left = ($percent)*($event->day-1)+3 .'vw';
    $color = $event->isEmptySlot?'rgba(255, 255, 83, 0.3)':'blue';
    $top=$event->startMinute-($startHour*60);
    $htmlString.= "<div style=\"position:relative; overflow: hidden; background-color:$color; border-radius:5px; border-style:solid; top:$top; position:absolute; left:$left ; right:$right; height:$event->duration;\"> $event->name ➡ $event->startMinute ➡{$event->getEndTime()} </div>";
}


$htmlString .= '</div>';
echo $htmlString;
?>