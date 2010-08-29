<?php
/*
Plugin Name: Vücut Kitle Endeksi
Plugin URI: http://wordpress.org/extend/plugins/vucut-kitle-endeksi/
Description: Cinsiyet, boy ve kilo girilerek; Vücut Yüzey Alanı, Yağsız Vücut Ağırlığı, İdeal Vücut Ağırlığı, Vücut Kitle Endeksi değerlerini hesaplayan bir eklenti.
Version: 1.0
Author: Süleyman ÜSTÜN
Author URI: http://suleymanustun.com
*/

add_action("plugins_loaded", "vke_widget_create");

function vke_widget_create() {
	$options = array('classname' => 'vke_widget', 'description' => "Cinsiyet, boy ve kilo girilerek; Vücut Yüzey Alanı, Yağsız Vücut Ağırlığı, İdeal Vücut Ağırlığı, Vücut Kitle Endeksi değerlerini hesaplayan bir bileşen." );
	wp_register_sidebar_widget('vke_widget', 'Vücut Kitle Endeksi', 'vke_widget_init', $options);
}

function vke_widget_init($args) {
	extract($args);
	echo $before_widget;
	echo $before_title.'Vücut Kitle Endeksi'.$after_title;
	vke_widget_show();
	echo $after_widget;
}

function vke_widget_show() {
	if ($_GET['kilo'] && $_GET['boy']) {
		$cins = $_GET['cins'];
		$kilo = $_GET['kilo'];
		$boy = $_GET['boy']/100;
		$vya  = round(0.20247 * pow($boy, '0.725') * pow($kilo, '0.425'),2);
		if ($cins == 'kadin') {
			$yva = round(1.07 * $kilo - 148 * pow($kilo, 2) / pow(100 * $boy, 2),2);
			$iva = round(45.5 + 2.3 * (($boy*100/2.54) - 60),2);
		} elseif ($cins == 'erkek') {
			$yva = round(1.10 * $kilo - 128 * pow($kilo, 2) / pow(100 * $boy, 2),2);
			$iva = round(50 + 2.3 * (($boy*100/2.54) - 60),2);
		}
		$vke  = round($kilo / ($boy * $boy),2);
		if ($vke < 18.50) {
			if ($vke < 16) {
				$sonuc = 'Durum: Şiddetli Zayıf';
			} elseif ($vke >= 16 and $vke < 16.99) {
				$sonuc = 'Durum: Zayıf';
			} elseif ($vke >= 17 and $vke < 18.49) {
				$sonuc = 'Durum: Biraz Zayıf';
			}
		} elseif ($vke >= 18.50 and $vke < 25) {
			$sonuc = 'Durum: Normal';
		} elseif ($vke >= 25 and $vke < 30) {
			$sonuc = 'Durum: Şişman';
		} elseif ($vke >= 30) {
			if ($vke < 34.99) {
				$sonuc = 'Durum: 1. Seviye Obez';
			} elseif ($vke >= 35 and $vke < 39.99) {
				$sonuc = 'Durum: 2. Seviye Obez';
			} elseif ($vke >= 40) {
				$sonuc = 'Durum: 3. Seviye Obez';
			}
		}
		echo '<ul>';
		echo '<li>Cinsiyet<span style="float:right;width:95px;">: '.$cins.'</span></li>';
		echo '<li style="clear:right">Kilo<span style="float:right;width:95px;">: '.$kilo.' kg</span></li>';;
		echo '<li style="clear:right">Boy<span style="float:right;width:95px;">: '.$boy.' cm</span></li>';
		
		echo '<li style="clear:right;">Vücut Yüzey Alanı</li>';
		echo '<span style="float:right;width:95px;">: '.$vya.' m&sup2;</span>';
		
		echo '<li style="clear:right;">Yağsız Vücut Ağırlığı</li>';
		echo '<span style="float:right;width:95px;">: '.$yva.' kg</span>';
		
		echo '<li style="clear:right;">İdeal Vücut Ağırlığı</li>';
		echo '<span style="float:right;width:95px;">: '.$iva.' kg</span>';
		
		echo '<li style="clear:right;">Vücut Kitle Endeksi</li>';
		echo '<span style="float:right;width:95px;">: '.$vke.' kg/m&sup2;</span>';
		
		echo '<li style="clear:right;">Hesaplama Sonucu</li>';
		echo '<span style="float:right;">'.$sonuc.'</span>';
		
		echo '</ul>';
	} else {
		echo '<form>';
			echo '<ul>';
				echo '<li>Cinsiyet<span style="float:right"><select name="cins" style="width:65px"><option value="erkek">Erkek</opiton><option value="kadin">Kadın</opiton></select></span></li>';
				echo '<li style="clear:right">Kilo (kg)<span style="float:right"><input type="text" name="kilo" style="width:50px"></span></li>';
				echo '<li style="clear:right">Boy (cm)<span style="float:right"><input type="text" name="boy" style="width:50px"></span></li>';
			echo '</ul>';
			echo '<input type="submit" value="Hesapla" style="width:100%">';
		echo '</form>';
	}
}
?>