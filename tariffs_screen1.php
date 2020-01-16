<?php
function element_color($element){
	switch ($element) {
    case 'Земля':
        return "ground";
        break;
    case 'Вода':
        return "water";
        break;
    case 'Огонь':
        return "fire";
        break;
    case 'Вода HD':
        return "water_hd";
        break;
    case 'Огонь HD':
        return "fire_hd";
        break;
   }
}

function first_screen($el_key, $el_val, $free_options, $links){
	$res = "<div class ='shift'><h1 class='tariff'>Тариф \"$el_key\"</h1><hr></div>";
	$step = 0;
	foreach ($el_val as $tariff => $options){
		$price_per_month = $options['price']/$options['pay_period'];
		$step += 1;
		if ($step == 1) {
		    $min_price_per_month = $price_per_month;
            $max_price_per_month = $price_per_month;
            $min_speed = $options['speed'];
            $max_speed = $options['speed'];
        } else {
          if ($price_per_month > $max_price_per_month) 
            $max_price_per_month = $price_per_month;
          if ($price_per_month < $min_price_per_month) 
            $min_price_per_month = $price_per_month;
          if ($options['speed'] > $max_speed) $max_speed = $options['speed'];
          if ($options['speed'] < $min_speed) $min_speed = $options['speed'];
        }
	}
	$element_code = element_color($el_key);
    $res .= "<div class='wrap'>
        <div class='left_col'>
          <div class='speed shift ".$element_code."'><b>";	
    if ($min_speed == $max_speed)
      $res .= "$min_speed Мбит/с";
      else $res .= "$min_speed - $max_speed Мбит/с";
    $res .= "</b></div>
          <div class ='price shift'><b>$min_price_per_month - $max_price_per_month <img class='rub' src='img/rub.jpg'>/мес</b></div>";
    if (isset($free_options[$el_key])) {
	$res .= "<div class='free_opt shift'>";	
    foreach ($free_options[$el_key] as $options_key => $options_val)
      foreach ($options_val as $opt_val_key => $opt_val)
        $res .= "$opt_val<br>";
    $res .= "</div>";
    }
    $res .= "
    </div>
    <div class='right_col' onclick='getSecondScreen(\"".$el_key."\")'><b>></b></div>
    </div>";
    $res .= "<div class='element_link_margin shift'><hr><a class='element_link' href=$links[$el_key]>Узнать подробнее на www.sknt.ru</a></div>";
    $res .= '<div class = "line secondary-line"></div>';  
	return $res;
}

$html = "";
$string = file_get_contents("https://www.sknt.ru/job/frontend/data.json");
$json_to_array = json_decode($string, true);
$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator($json_to_array),
    RecursiveIteratorIterator::SELF_FIRST);

$element_title = "";
$elements = [];
$free_options = [];
$links = [];
foreach ($jsonIterator as $key => $val) {
    if(is_array($val)) {		 
		$currentDepth = $jsonIterator->getDepth();
		if ($currentDepth == 3) {
		  $elements[$element_title][] = $val;
		}
		if ($key === 'free_options') {
		  $free_options[$element_title][] = $val;
		}
    } else {
		if (isset($currentDepth) && $currentDepth == 1){
		  $element_title = $val;
	  }
	    if ($key === 'link')
	      $links[$element_title] = $val;
    }
}

$html = '<div class="columns">';

foreach ($elements as $el_key => $el_val) {
  $html .= '<div>';
  $html .= first_screen($el_key, $el_val, $free_options, $links);
  $html .= '</div>';
  }
  
$html .= '</div>';
echo $html;
