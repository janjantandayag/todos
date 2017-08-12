<?php

namespace app\helpers;

class CustomHelpers{
	public static function getProgressClass($value)
	{		
        if($value >= 0 && $value <= 25){
            $class = 'progress-bar-danger';
        }
        if($value > 25 && $value <= 50){
            $class = 'progress-bar-warning';                        
        }
        if($value > 50 && $value <= 75){
            $class = 'progress-bar-info';                        
        }
        if($value > 75 && $value <= 100){
            $class = 'progress-bar-success';                        
        }
        return $class;
	}

    public static function getBgColor($value) {
        if($value == 'LOW'){
            return ['class' => 'bg-success'];
        }elseif($value == 'NORMAL'){
            return ['class' => 'bg-warning'];
        }else{
            return ['class' => 'bg-danger'];
        }
    }
}