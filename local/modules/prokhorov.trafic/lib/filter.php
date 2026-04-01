<?php
namespace Prokhorov\Trafic;

use Bitrix\Main\Loader,
Bitrix\Main\Context,
\Bitrix\Main\Type\DateTime;



class Filter {

    public static function getFilterParams(){

        try
        {
            $filterParams = self::getParams();

            $period = $filterParams['PERIOD'];

            $name = $filterParams['NAME'];

            $calendar = $filterParams['CALENDAR'];


            if($calendar)
            {
                 $filterQuery['SEARCH'] = self::getParamsByDatePeriod($calendar);
                 $filterQuery['PERIOD'] = 'period';
                 $filterQuery['PERIOD_LIST'] = $calendar;
                 
            }

            if($period)
            {
                $filterQuery['PERIOD'] = $period;
                $filterQuery['SEARCH'] = self::getParamsByDate($period);
            }

            if($name)
            {
                $filterQuery['IP'] = $name;
                $filterQuery['SEARCH'][] = self::getParamsName($name);

            }

            return $filterQuery;

            

        } 
        catch (\Exception $e)
        {
            return false;
        }

        
    }

     /*Параметры фильтра за период*/
    public static function getParamsByDatePeriod($dates){
        return 
        [
            '>=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime(date($dates['DATE_START']).' 00:00:00'),
            '<=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime(date($dates['DATE_END']).'23:59:59')
        ];
    }

    /*Параметры фильтра за сегодня*/
    public static function getParamsToday(){
        return 
        [
            '>=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime(date("d.m.Y").' 00:00:00'),
            '<=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime(date("d.m.Y").'23:59:59')
        ];
    }

    /*Фильтр по имени*/
    public static function getParamsName($name){
        return
        [
            "UF_IP_ID" => $name
        ];
    }

    /*Параметры фильтра за 7 дней*/
    public static function getParamsWeek(){
        return 
        [
            '>=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime(date("d.m.Y", strtotime("-7 days")).' 00:00:00'),
            '<=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime(date("d.m.Y").' 23:59:59')
        ];
    }

    /*Параметры фильтра за вчера*/
    public static function getParamsYesterday(){
        return 
        [
            '>=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime(date("d.m.Y", strtotime("yesterday")).' 00:00:00'),
            '<=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime(date("d.m.Y", strtotime("yesterday")).'23:59:59')
        ];    
    }

    /*Параметры фильтра позавчера*/
    public static function getParamsDayBeforeYesterday(){
        return 
        [
            '>=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime(date("d.m.Y", strtotime("-2 day")).' 00:00:00'),
            '<=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime(date("d.m.Y", strtotime("-2 day")).'23:59:59')      
        ];
    }

    /*Параметры фильтра позавчера*/
    public static function getParamsMonth(){

        // Получить первый день текущего месяца
        $firstDayOfMonth = date('01.m.Y 00:00:00');
        // Получить последний день текущего месяца
        $lastDayOfMonth = date('t.m.Y 23:59:59');

        return  
        [
            '>=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime($firstDayOfMonth),
            '<=UF_IP_DATA_VIZITA' => DateTime::createFromUserTime($lastDayOfMonth)
        ];    
    }

    protected static function getParamsByDate($params){

        switch ($params) {
            
            case 'today':
                $filter = self::getParamsToday();
                break;

            case 'week':
                $filter = self::getParamsWeek();
                break;

            case 'yesterday':
                $filter = self::getParamsYesterday();
                break;

            case 'day_before_yesterday':
                $filter = self::getParamsDayBeforeYesterday();
                break;   
                    
            case 'month':
                $filter = self::getParamsMonth();
                break;     
                
        }

        return $filter;
    }


    

    protected static function getParams(){

        $context = Context::getCurrent();
        $request = Context::getCurrent()->getRequest();

        $filterDate = [];

        if($request->get("date"))
        {
            $filterDate['PERIOD'] = $request->get("date");
        }

        if($request->get("dateStart")){

            $filterDate['CALENDAR'] = [
                "DATE_START" => $request->get("dateStart"),
                "DATE_END" => $request->get("dateEnd")
            ];
        }

        if($request->get("ip"))
        {
            $filterDate['NAME'] = $request->get("ip");
        }


        if(empty($filterDate))
        {
            throw new \Exception('Не переданы параметры для фильтра');
        }

        return $filterDate;
    }
}