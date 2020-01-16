<?php 
	
function find_max_price_per_month($el_val){
	$step = 0;
	$max_price_per_month = 0;
	  foreach($el_val as $t => $opt){
		$price_per_month = $opt['price']/$opt['pay_period'];
		if ($max_price_per_month < $price_per_month) $max_price_per_month = $price_per_month;
    }
    return $max_price_per_month;
}

include("additional_functions.php");


function second_screen($el_key, $el_val){
	$res2 = "<div class='wrap'>
	  <div class='tarif_title_left_col' onclick='getFirstScreen()'><b><</b></div>
	  <div class='tarif_title_right_col'><h2>Тариф \"$el_key\"</h2></div>
	  </div>";
	$res2 .= '<div class = "line main-line"></div>';
	$max = find_max_price_per_month($el_val);
	
	usort
    ( 
      $el_val,
      create_function
      (   
        '$a,$b', 
        'return ($a["pay_period"] - $b["pay_period"]);' 
      )
    );
	foreach ($el_val as $tariff => $options){
       $res2 .= "<br><div class='shift'><div class='month'><b>".month($options['pay_period'])."</b></div><hr class='month-hr'>";
       $price_per_month = $options['price']/$options['pay_period'];
       $res2 .= "<div class='wrap'>
           <div class='left_col_second_screen'><div class='price_per_month'><b>".$price_per_month." <img class='rub' src='img/rub.jpg'>/мес</b></div><br>";
       $res2 .= 'разовый платёж &ndash; '.$options['price']." <img class='rub' src='img/rub.jpg'><br>";
       $discount = $max*$options['pay_period'] - $options['price'];
       if ($discount<>0) $res2 .= "скидка &ndash; ".$discount." <img class='rub' src='img/rub.jpg'><br>";
	   $res2 .= "</div>
	     <div class='right_col_second_screen' onclick='getThirdScreen(\"".$el_key."\",".$options['pay_period'].")'><b>></b></div>
	   </div></div>";
	   $res2 .= '<br><div class = "line secondary-line"></div>';
	}
	$res2 .= "<div class='element'></div>";
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
    $tariff_name = $_POST["element"];
    foreach ($jsonIterator as $key => $val) {
      if(is_array($val)) {		 
		$currentDepth = $jsonIterator->getDepth();
		if ($currentDepth == 3 && $element_title === $tariff_name) {
		  $elements[$element_title][] = $val;
		}
      } else {
		if (isset($currentDepth) && $currentDepth == 1){
		  $element_title = $val;
	    }
    }
}

  $html = second_screen($tariff_name, $elements[$tariff_name]);
echo $html;
}
