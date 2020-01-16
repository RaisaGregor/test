<?php 

include("additional_functions.php");

function third_screen($tariff, $el_val){
	$res2 = "<div class='wrap'>
	  <div class='tarif_title_left_col' onclick='getSecondScreen(\"".$tariff."\")'><b><</b></div>
	  <div class='tarif_title_right_col'><div class='tarif_title'><h1 class='font-enlarge'>Выбор тарифа</h1></div></div>
	  </div>";
	$res2 .= '<div class = "line main-line"></div>';
	$res2 .= "<div class='shift font-enlarge'><div class='tariff_third_screen'><b>Тариф \"".$tariff."\"</b></div><hr>";
	$options = $el_val[0];
       $res2 .= "<br><b>Период оплаты &ndash; ".month($options['pay_period']);
       $price_per_month = $options['price']/$options['pay_period'];
       $res2 .= "<div class='price_per_month'><b>".$price_per_month." <img class='rub' src='img/rub.jpg'>/мес</b></div><br>";
       $res2 .= 'разовый платёж &ndash; '.$options['price']." <img class='rub' src='img/rub.jpg'><br>";
       $res2 .= 'со счёта спишется &ndash; '.$options['price']." <img class='rub' src='img/rub.jpg'><br>";
	   $res2 .= '<br><div class = "activate">вступит в силу - сегодня</div>';
	   $date = date('Y-m-d');
       $dateAt = strtotime('+'.$options['pay_period'].' MONTH', strtotime($date));
	   $end_date = date('d.m.Y', $dateAt);
	   /* Из задания не совсем ясно, зачем нужен new_payday, логично, что если дата активации тарифа - сегодня, то дата окончания -
	    * сегодня плюс pay_period месяцев. Но, если это не так, привожу работу с new_payday в этом комментарии:
	   $date = date_create();
       //date_timestamp_set($date, substr($options['new_payday'], 0, -5));
       * или
       date_timestamp_set($date, explode("+", $options['new_payday'])[0]);
       $end_date = date_format($date, 'd.m.Y')*/;
	   $res2 .= '<div class = "active">активно до - '.$end_date.'</div>';
	$res2 .= "<br><hr></div><br>";
	$res2 .= "<div class='botton'><div class='choose font-enlarge'>Выбрать</div></div><br>";
	return $res2;
}

if($_POST) {
    $string = file_get_contents("https://www.sknt.ru/job/frontend/data.json");
    $json_to_array = json_decode($string, true);
    $jsonIterator = new RecursiveIteratorIterator(
        new RecursiveArrayIterator($json_to_array),
        RecursiveIteratorIterator::SELF_FIRST);

    $element_title = "";
    $elements = [];
    $tariff_name = $_POST["tariff"];
    $pay_period = $_POST["pay_period"];
    foreach ($jsonIterator as $key => $val) {
      if(is_array($val)) {		 
		$currentDepth = $jsonIterator->getDepth();
		if ($currentDepth == 3 && $element_title === $tariff_name && $val['pay_period'] === $pay_period) {
		  $elements[$tariff_name][] = $val;
		}
      } else {
		if (isset($currentDepth) && $currentDepth == 1){
		  $element_title = $val;
	    }
    }
}

  $html = third_screen($tariff_name, $elements[$tariff_name]);
echo $html;
}
