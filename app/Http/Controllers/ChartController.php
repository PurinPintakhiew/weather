<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Weather;
use App\Models\Machine;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function chartData(){
        date_default_timezone_set('Asia/Bangkok');
        $sql = DB::select("SELECT datetime,pm2_5 FROM datapm WHERE datetime > NOW() - INTERVAL 24 HOUR  ");
        $data[] = ['Time','Average'];
        foreach($sql as $key => $value){
            $date = $value->datetime;
            $time = date("H:i", strtotime($date));
            $data[++$key] = [$time,$value->pm2_5];
        }
        $data = json_encode($data);
        $datenow = date('Y-m-d');
        $avgDay = Weather::avg('pm2_5');

        $datenow = $this->DateThai($datenow);
        $dataWeek = $this->chartWeek();
        $minTimeDay = $this->minTimeOfDay();

        return view('chartPM',compact('data','avgDay','datenow','dataWeek','minTimeDay'));
    }

    public function DateThai($date){
        date_default_timezone_set('Asia/Bangkok');
        $year = date("Y",strtotime($date))+543;
        $month = date("m",strtotime($date));
        $day = date("d",strtotime($date));
        $monthArray =  array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กฎกราคม",
                            "สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
        $monthThai = $monthArray[$month];
        return "$day $monthThai $year";
      }

      public function chartWeek(){
        
        $sql = DB::select("SELECT DATE(datetime) as DateOnly,AVG(pm2_5) AS avg FROM `datapm`
             WHERE DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() GROUP BY DateOnly ORDER BY DateOnly;");

        $dataWeek[] = ['Time','Average'];
        foreach($sql as $key => $value){
            $date = $value->DateOnly;
            $time = $this->DateThai($date);
            $dataWeek[++$key] = [$time,$value->avg];
        }
        $dataWeek = json_encode($dataWeek);
        return $dataWeek;
      }

      public function minTimeOfDay(){

        $sql = db::select("SELECT MIN(datetime) as mindate FROM datapm WHERE datetime > NOW() - INTERVAL 24 HOUR;");
        
        foreach($sql as $key => $value){
            $date = $value->mindate;
            // $minTimeDay = $this->DateThai($date);
        }
        
        $date = json_encode($date);
        return $date;

      }
// ---------------------- new ----------------------------------
      public function machine(){
        $sql = DB::select("SELECT machine_id,address FROM machine_location");
        return $sql;
      }

      public function dataSelect(Request $request){
        $macid = $request->macid;
        $type = $request->typedata;
        $data1 = $request->date1;
        $data2 = $request->date2;

        $sql = DB::select("SELECT machine_id,datetime,$type FROM datapm WHERE machine_id = $macid and  datetime BETWEEN '$data1' and '$data2'  ");
        $dataTime[] = ['Time','Average '.$type];
        foreach($sql as $key => $value){
          $date = date("d/m/Y H:i", strtotime($value->datetime));
          $dataTime[++$key] = [$date,$value->$type];
        }
        $dataTime = json_encode($dataTime);

        return $dataTime;
      }

      public function chartSelect(){
        $machines = $this->machine();
        return view('chartSelect',compact('machines'));
      }



}

