<?php

namespace App\Validations;

use \Datetime;

class DataValidations {

    function validarDatas($date1, $date2){
        //dd($date1, $date2);
        if(
            $this->validateDate($date1) && $this->validateDate($date2) &&
            (strtotime($date1) <= strtotime($date2))
        ){
            return true;
        }
        else{
            return false;
        }
    }

    function validarData($date1){
        if($this->validateDate($date1)){
            return true;
        }
        else{
            return false;
        }
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
