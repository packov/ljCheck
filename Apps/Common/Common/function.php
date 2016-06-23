<?php
/**
 * 调试工具debug
 * @param $val
 * @param bool $dump
 * @param bool $exit
 */
function debug($val, $dump = false, $exit = true) {
    //自动获取调试函数名称$func
    if ($dump) {
        $func = 'var_dump';
    } else {
        $func = (is_array($val) || is_object($val)) ? 'print_r' : 'printf';
    }
    //输出到html
    header("Content-type:text/html; charset=utf-8");
    echo '<pre>debug output:<hr/>';
    $func($val);
    echo '</pre>';
    if ($exit) exit;
}

/**
 * 计算某个月有多少天
 * @param $month
 * @param $year
 * @return int
 */
function days_in_month($month, $year) {
    return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}

/**
 * 获取这个月每天的日期
 * @param $y_m 2016-03
 * @return array
 */
function getYMDofMonth($y_m){
     $tmpArr = explode('-',$y_m);
     $year = intval($tmpArr[0]);
     $month = intval($tmpArr[1]);
     $dayCount = days_in_month($month, $year);
     $dayArr = array();
     $tmpDays = range(1, $dayCount);
     foreach ($tmpDays as $d){
         if ($d < 10)  $d = '0'.$d;
         $dayArr[] = $y_m . '-'.$d;
     }
    return $dayArr;
}

//判断是否是周末
/**
 * @param $date string '2016-04-03'
 * @return bool
 */
function is_weekend($date) {
    $d = date("w", strtotime($date));
    if ($d == "0" || $d == "6") {
        return true;
    }
    return false;
}

//判断是否是周一
/**
 * @param $date  string '2016-04-04'
 * @return bool
 */
function is_monday($date){
    $d = date("w", strtotime($date));
    if ($d == "1"){
        return true;
    }
    return false;
}

/**
 * 获取时间差
 * @param $time1 '09:12'
 * @param $time2 '09:00'
 * @return int 时间差(分钟)
 */
 function periodTimeMinutes($time1, $time2) {
    $time1arr = explode(':', $time1);
    $time2arr = explode(':', $time2);
    $periodTime = ($time1arr[0] * 60 + $time1arr[1]) - ($time2arr[0] * 60 + $time2arr[1]);
    $periodTime = $periodTime < 0 ? 0 : $periodTime;

    return $periodTime;
}

/**
 * @param $time1 int 时间戳
 * @param $time2 int 时间戳
 * @return float 时间差(分钟)
 */
 function periodTimeMinutes2($time1, $time2){
     $time1 = intval($time1);
     $time2 = intval($time2);
    $periodTime = abs($time1-$time2)/60;
    return intval($periodTime);
}

/**
 * 十进制转八进制包含小数
 * @param $number
 * @return string
 */
function myDecoct($number){
    $number = (string)$number;
    $position = stripos($number,".");
    if ($position === false) { //不存在小数
        $num = decoct($number);
    } else{     //存在小数
        $num1 = intval($number);     //整数部分
        $num2 = substr($number, $position+1 ,strlen($number)); //小数部分
        $num2 = intval($num2);
        $num1  = decoct($num1);
        if ($num2 >= 8) {
            $num2 = $num2 % 8;
            $num1 += 1;
        }
        $num = $num1.'.'.$num2;
    }
    return $num;
}

/**
 * 根据开始日期结束日期 获取之间具体的日子
 * @param $date  '2016-04-01'
 * @param $end   '2016-04-04'
 * @return array
 */
function getDaysForStartAndEnd($date, $end){
    $array = array();
    for($i=0;strtotime($date.'+'.$i.' days') <= strtotime($end) && $i<365; $i++){
        $time = strtotime($date.'+'.$i.' days');
        $array[] = date('Y-m-d',$time);
    }
    return $array;
}

/**根据开始月份结束月份 获取之间具体的月份
 * @param $year_month_start '2015-11'
 * @param $year_month_end   '2016-03'
 * @return array
 */
function getMonthsForStartAndEnd($year_month_start, $year_month_end){
    $xAxisData = array();
    $start = explode('-', $year_month_start);
    $start_year = $start[0];
    $start_month = $start[1];
    $end = explode('-', $year_month_end);
    $end_year = $end[0];
    $end_month = $end[1];
    if ($start_year == $end_year) {
        $months = range($start_month, $end_month);
        foreach ($months as $m) {
            if ($m < 10) {
                $m = '0' . $m;
            }
            $xAxisData[] = $start_year . '-' . $m;
        }
    } else {
        $years = range($start_year, $end_year);
        foreach ($years as $y) {
            if ($y == $start_year) {
                $smonths = range($start_month, 12);
                foreach ($smonths as $m) {
                    if ($m < 10) {
                        $m = '0' . $m;
                    }
                    $xAxisData[] = $start_year . '-' . $m;
                }
            } elseif ($y == $end_year) {
                $emonths = range(1, $end_month);
                foreach ($emonths as $m) {
                    if ($m < 10) {
                        $m = '0' . $m;
                    }
                    $xAxisData[] = $end_year . '-' . $m;
                }
            } else {
                $months = range(1, 12);
                foreach ($months as $m) {
                    if ($m < 10) {
                        $m = '0' . $m;
                    }
                    $xAxisData[] = $y . '-' . $m;
                }
            }
        }
    }
    return $xAxisData;
}

//导出Excel表
/**
 * @param $fileName string 导出文件名
 * @param $headArr  array 字段名 (Excel列名)
 * @param $data     array
 * @throws PHPExcel_Exception
 * @throws PHPExcel_Reader_Exception
 */
function exportExcel($fileName, $headArr, $data){
    //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能import导入
    import("Org.Util.PHPExcel");
    import("Org.Util.PHPExcel.Writer.Excel5");
    import("Org.Util.PHPExcel.IOFactory.php");

    $date = date("Y_m_d",time());
    $fileName .= "_{$date}.xls";

    //创建PHPExcel对象，注意，不能少了\
    $objPHPExcel = new \PHPExcel();
    $objProps = $objPHPExcel->getProperties();

    //设置表头
    $key = ord("A");
    foreach($headArr as $v){
        $colum = chr($key);
        $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
        $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
        $key += 1;
    }

    $column = 2;
    $objActSheet = $objPHPExcel->getActiveSheet();

    foreach($data as $key => $rows){ //行写入
        $span = ord("A");
        foreach($rows as $keyName=>$value){// 列写入
            $j = chr($span);
            $objActSheet->setCellValue($j.$column, $value);
            $span++;
        }
        $column++;
    }

    $fileName = iconv("utf-8", "gb2312", $fileName);

    //重命名表
    //$objPHPExcel->getActiveSheet()->setTitle('test');
    //设置活动单指数到第一个表,所以Excel打开这是第一个表
    $objPHPExcel->setActiveSheetIndex(0);
    ob_end_clean();//清除缓冲区,避免乱码
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=\"$fileName\"");
    header('Cache-Control: max-age=0');

    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); //文件通过浏览器下载
    exit;
}