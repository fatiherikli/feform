FEForm Nedir ?
===============
 FEForm, PHP ile internet uygulamaları geliştirirken en sıkıcı ve uzun işlerden olan form işlemlerini daha basit bir şekilde yönetmenizi sağlayan bir php kütüphanesidir. FEForm ile formlarınızı düz html yerine PHP'de sınıflar sayesinde oluşturmaktasınız. Bu şekilde kodlarınıza daha sonra girdiğinizde kolayca yönetebilir ve değişikliğinizi yapabilirsiniz (maintenance kolaylığı). Validator foksiyonları sayesinde formunuzdaki her hangi bir öğenin zorunlu olmasını ya da sizin belirlediğiniz kriterlere uygun olmasını sağlayabilirsiniz.

Özellikler
==========
- Hazır widgetlar (EmailWidget, PasswordInput gibi)
- Custom Widget (Özel widget hazırlayabilme)
- Validators (email_validator, numeric_validator gibi)
- Özel validator hazırlayabilme
- XSS Koruması
- String Filters (girilen verileri filtrelerden geçirmek)

Ücretli midir ?
===============
 FEForm ücretsiz ve açık kaynak kodlu bir kütüphanedir. Dilediğiniz gibi indirip değiştirebilirsiniz.
 
Dökümantasyon
=============

Başlangıç
---------
 FEForm'u kullanabilmek için dosyaları projenizin dizinine veya her hangi bir dizine çıkarıp feform.php dosyasını include etmeniz yeterlidir. include('feform.php'); şeklinde include edebilirsiniz.

Form oluşturmak
----------------
 Formları oluştururken Form sınıfından yeni sınıflar türeterek çalışacağız. Form base class (temel sınıf), ürettiğiniz class'lar ise  delivered class'lar (türetilmiş sınıf) olacaktır. Örnek olarak;
 
	<?php
		 class AramaFormu extends Form {
			 
		 }
	?>
	
Form field'larını (alanları) tanımlamak
----------------------------------------
 Form field'larını türettiğimiz sınıfda fields adında array(diziler) yardımıyla belirtmekteyiz. Array'deki label alanın gözükecek ismi, widget alanın türünü, validators ise verinin geçerliliğini, zorunlu olmasını vb. belirmektedir.
 
	<?php
	class AramaFormu extends Form {
			 var $fields = Array(
				 "kelime" => Array(
					 "widget" => "TextInput",
					 "label" => "Aranacak Kelime",
					 "validators" => Array("required")
				 ),
				 "siralama" => Array(
					"widget" => "Radio",
					"label" => "Sıralama",
					"choices" => Array("baslik"=>"Başlık", "hit"=>"Hit", "tarih", "Tarih")
				),

			 );
		 }
	 
	?>


Belli başlı widget'lar
----------------------
- TextInput (düz metin girişi)
- PasswordInput (şifre girişi)
- MailInput (email adresi girişi)
- TextArea (geniş metin girişi)
- Select (açılabilir menü ile giriş)
- SelectMultiple (açılabilir menü ile birden fazla giriş)
- Radio (radyo butonları ile tek seçim)
- CheckBox (çentiklerle çoklu seçim)
 
 şimdilik bu kadar...
 
Formu render etmek (yazdırmak)
------------------------------
 Formu bir bir dizi ile doldurabilmektesiniz. Örneğin $_POST ve $_GET size array döndürür. Bunu direk formu oluştururken girebilirsiniz. Formun constructor (yapıcı) method'unda field'lara doldurulmaktadır. Biz aşağıdaki örnekte GET metodundan aldığımız dizi ile formumuzu doldurduk.
	
	<?php
	if ($_GET) {
		$form = new AramaFormu($_GET);
		if ($form->is_valid()) {
		  echo $form->cleaned_data["kelime"] . " araması yapılıyor.";
		}
		  
	}
	else {
	 $form = new AramaFormu();
	}
	?>

	<form action="" method="get">
	 <?php $form->render() ?>
	 <input type="submit" value="Ara" />
	</form>
 
 Örnekte gördüğümüz gibi formdan gelen verileri formun cleaned_data özelliği sayesinde alabilmekteyiz.
 
Özel validator yazmak
---------------------
Özel validator yazmak oldukça basittir. Her bir validatör sadece bir foksiyondan ibarettir. Aşağıdaki örnek ile rahatlıkla anlaşılabilir.
	
	<?php
	function required($data) {
			 if (empty($data))
				 throw new Exception("Bu alan zorunludur.");
			 return $data;
		}
	?>
	
Özel widget yazmak
------------------
 Özel widget yazmak için widgets.php üzerindeki Widget sınıfından başka bir sınıf türetmeniz gerekmektedir. Aşağıdaki örnekte widget sınıfından türetilmiş bir sınıf gözükmekte.
	
	<?php
	class EmailInput extends TextInput {
		 var $validators = Array("email_validator");
	}
	?>

 Aşağıdaki kod parçasında ise türettiğiniz sınıflarda kullanabileceğiniz özelikleri gösterilmekte. name, value, label özellikleri form oluştururken kullanılmakta. type özelliğini widget'a özel olarak belirtebilirsiniz. örneğin password olarak belirtesiniz şifre seçim girişi elde edersiniz. choices özelliği çoklu seçimlerde kullanılmaktadır. attrs ile widget'a ait parametreleri belirtebilirsiniz. Örneğin textarea için rows, cols gibi. validators özelliği widget'ın default denetleyicisini belirtmekte. Örneğin EmailInput için email_validator validator'ü default olarak gelmekte. string filters özelliği formdan gelen verinin geçeceği filtreleri belirmekte. strip_tags formdaki html kodlarını temizler. trim ise girilen metinin başındaki ve sonundaki gereksiz boşlukları temizler. Bu iki filter default olarak gelmekte. Dilerseniz kendi filter fonksiyonunuzu yazıp, burada string olarak belirtebilirsiniz.
 
	var $name;
	var $value;
	var $label;
	var $type;
	var $choices = Array();
	var $attrs = Array();
	var $validators = Array();
	var $string_filters = Array("strip_tags", "trim");
	var $error_text;
	

Demo
=====
 Demolar, dökümantasyon ve daha detaylı bilgi için;
 
 http://fatiherikli.com/fejax/