FEForm Nedir ?
===============
 FEForm, PHP ile internet uygulamalarý geliþtirirken en sýkýcý ve uzun iþlerden olan form iþlemlerini daha basit bir þekilde yönetmenizi saðlayan bir php kütüphanesidir. FEForm ile formlarýnýzý düz html yerine PHP'de sýnýflar sayesinde oluþturmaktasýnýz. Bu þekilde kodlarýnýza daha sonra girdiðinizde kolayca yönetebilir ve deðiþikliðinizi yapabilirsiniz (maintenance kolaylýðý). Validator foksiyonlarý sayesinde formunuzdaki her hangi bir öðenin zorunlu olmasýný ya da sizin belirlediðiniz kriterlere uygun olmasýný saðlayabilirsiniz.

Özellikler
==========
- Hazýr widgetlar (EmailWidget, PasswordInput gibi)
- Custom Widget (Özel widget hazýrlayabilme)
- Validators (email_validator, numeric_validator gibi)
- Özel validator hazýrlayabilme
- XSS Korumasý
- String Filters (girilen verileri filtrelerden geçirmek)

Ücretli midir ?
===============
 FEForm ücretsiz ve açýk kaynak kodlu bir kütüphanedir. Dilediðiniz gibi indirip deðiþtirebilirsiniz.
 
Dökümantasyon
=============

Baþlangýç
---------
 FEForm'u kullanabilmek için dosyalarý projenizin dizinine veya her hangi bir dizine çýkarýp feform.php dosyasýný include etmeniz yeterlidir. include('feform.php'); þeklinde include edebilirsiniz.

Form oluþturmak
----------------
 Formlarý oluþtururken Form sýnýfýndan yeni sýnýflar türeterek çalýþacaðýz. Form base class (temel sýnýf), ürettiðiniz class'lar ise  delivered class'lar (türetilmiþ sýnýf) olacaktýr. Örnek olarak;
	<?php
		 class AramaFormu extends Form {
			 
		 }
	?>
	
Form field'larýný (alanlarý) tanýmlamak
----------------------------------------
 Form field'larýný türettiðimiz sýnýfda fields adýnda array(diziler) yardýmýyla belirtmekteyiz. Array'deki label alanýn gözükecek ismi, widget alanýn türünü, validators ise verinin geçerliliðini, zorunlu olmasýný vb. belirmektedir.
 
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
					"label" => "Sýralama",
					"choices" => Array("baslik"=>"Baþlýk", "hit"=>"Hit", "tarih", "Tarih")
				),

			 );
		 }
	 
	?>


Belli baþlý widget'lar
----------------------
- TextInput (düz metin giriþi)
- PasswordInput (þifre giriþi)
- MailInput (email adresi giriþi)
- TextArea (geniþ metin giriþi)
- Select (açýlabilir menü ile giriþ)
- SelectMultiple (açýlabilir menü ile birden fazla giriþ)
- Radio (radyo butonlarý ile tek seçim)
- CheckBox (çentiklerle çoklu seçim)
 
 þimdilik bu kadar...
 
Formu render etmek (yazdýrmak)
------------------------------
 Formu bir bir dizi ile doldurabilmektesiniz. Örneðin $_POST ve $_GET size array döndürür. Bunu direk formu oluþtururken girebilirsiniz. Formun constructor (yapýcý) method'unda field'lara doldurulmaktadýr. Biz aþaðýdaki örnekte GET metodundan aldýðýmýz dizi ile formumuzu doldurduk.
	<?php
	if ($_GET) {
		$form = new AramaFormu($_GET);
		if ($form->is_valid()) {
		  echo $form->cleaned_data["kelime"] . " aramasý yapýlýyor.";
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
 
 Örnekte gördüðümüz gibi formdan gelen verileri formun cleaned_data özelliði sayesinde alabilmekteyiz.
 
Özel validator yazmak
---------------------
Özel validator yazmak oldukça basittir. Her bir validatör sadece bir foksiyondan ibarettir. Aþaðýdaki örnek ile rahatlýkla anlaþýlabilir.
	<?php
	function required($data) {
			 if (empty($data))
				 throw new Exception("Bu alan zorunludur.");
			 return $data;
		}
	?>
	
Özel widget yazmak
------------------
 Özel widget yazmak için widgets.php üzerindeki Widget sýnýfýndan baþka bir sýnýf türetmeniz gerekmektedir. Aþaðýdaki örnekte widget sýnýfýndan türetilmiþ bir sýnýf gözükmekte.
	<?php
	class EmailInput extends TextInput {
		 var $validators = Array("email_validator");
	}
	?>

 Aþaðýdaki kod parçasýnda ise türettiðiniz sýnýflarda kullanabileceðiniz özelikleri gösterilmekte. name, value, label özellikleri form oluþtururken kullanýlmakta. type özelliðini widget'a özel olarak belirtebilirsiniz. örneðin password olarak belirtesiniz þifre seçim giriþi elde edersiniz. choices özelliði çoklu seçimlerde kullanýlmaktadýr. attrs ile widget'a ait parametreleri belirtebilirsiniz. Örneðin textarea için rows, cols gibi. validators özelliði widget'ýn default denetleyicisini belirtmekte. Örneðin EmailInput için email_validator validator'ü default olarak gelmekte. string filters özelliði formdan gelen verinin geçeceði filtreleri belirmekte. strip_tags formdaki html kodlarýný temizler. trim ise girilen metinin baþýndaki ve sonundaki gereksiz boþluklarý temizler. Bu iki filter default olarak gelmekte. Dilerseniz kendi filter fonksiyonunuzu yazýp, burada string olarak belirtebilirsiniz.
 
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
 Demolar, dökümantasyon ve daha detaylý bilgi için;
 http://fatiherikli.com/fejax/