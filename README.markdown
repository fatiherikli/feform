FEForm Nedir ?
===============
 FEForm, PHP ile internet uygulamalar� geli�tirirken en s�k�c� ve uzun i�lerden olan form i�lemlerini daha basit bir �ekilde y�netmenizi sa�layan bir php k�t�phanesidir. FEForm ile formlar�n�z� d�z html yerine PHP'de s�n�flar sayesinde olu�turmaktas�n�z. Bu �ekilde kodlar�n�za daha sonra girdi�inizde kolayca y�netebilir ve de�i�ikli�inizi yapabilirsiniz (maintenance kolayl���). Validator foksiyonlar� sayesinde formunuzdaki her hangi bir ��enin zorunlu olmas�n� ya da sizin belirledi�iniz kriterlere uygun olmas�n� sa�layabilirsiniz.

�zellikler
==========
- Haz�r widgetlar (EmailWidget, PasswordInput gibi)
- Custom Widget (�zel widget haz�rlayabilme)
- Validators (email_validator, numeric_validator gibi)
- �zel validator haz�rlayabilme
- XSS Korumas�
- String Filters (girilen verileri filtrelerden ge�irmek)

�cretli midir ?
===============
 FEForm �cretsiz ve a��k kaynak kodlu bir k�t�phanedir. Diledi�iniz gibi indirip de�i�tirebilirsiniz.
 
D�k�mantasyon
=============

Ba�lang��
---------
 FEForm'u kullanabilmek i�in dosyalar� projenizin dizinine veya her hangi bir dizine ��kar�p feform.php dosyas�n� include etmeniz yeterlidir. include('feform.php'); �eklinde include edebilirsiniz.

Form olu�turmak
----------------
 Formlar� olu�tururken Form s�n�f�ndan yeni s�n�flar t�reterek �al��aca��z. Form base class (temel s�n�f), �retti�iniz class'lar ise  delivered class'lar (t�retilmi� s�n�f) olacakt�r. �rnek olarak;
	<?php
		 class AramaFormu extends Form {
			 
		 }
	?>
	
Form field'lar�n� (alanlar�) tan�mlamak
----------------------------------------
 Form field'lar�n� t�retti�imiz s�n�fda fields ad�nda array(diziler) yard�m�yla belirtmekteyiz. Array'deki label alan�n g�z�kecek ismi, widget alan�n t�r�n�, validators ise verinin ge�erlili�ini, zorunlu olmas�n� vb. belirmektedir.
 
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
					"label" => "S�ralama",
					"choices" => Array("baslik"=>"Ba�l�k", "hit"=>"Hit", "tarih", "Tarih")
				),

			 );
		 }
	 
	?>


Belli ba�l� widget'lar
----------------------
- TextInput (d�z metin giri�i)
- PasswordInput (�ifre giri�i)
- MailInput (email adresi giri�i)
- TextArea (geni� metin giri�i)
- Select (a��labilir men� ile giri�)
- SelectMultiple (a��labilir men� ile birden fazla giri�)
- Radio (radyo butonlar� ile tek se�im)
- CheckBox (�entiklerle �oklu se�im)
 
 �imdilik bu kadar...
 
Formu render etmek (yazd�rmak)
------------------------------
 Formu bir bir dizi ile doldurabilmektesiniz. �rne�in $_POST ve $_GET size array d�nd�r�r. Bunu direk formu olu�tururken girebilirsiniz. Formun constructor (yap�c�) method'unda field'lara doldurulmaktad�r. Biz a�a��daki �rnekte GET metodundan ald���m�z dizi ile formumuzu doldurduk.
	<?php
	if ($_GET) {
		$form = new AramaFormu($_GET);
		if ($form->is_valid()) {
		  echo $form->cleaned_data["kelime"] . " aramas� yap�l�yor.";
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
 
 �rnekte g�rd���m�z gibi formdan gelen verileri formun cleaned_data �zelli�i sayesinde alabilmekteyiz.
 
�zel validator yazmak
---------------------
�zel validator yazmak olduk�a basittir. Her bir validat�r sadece bir foksiyondan ibarettir. A�a��daki �rnek ile rahatl�kla anla��labilir.
	<?php
	function required($data) {
			 if (empty($data))
				 throw new Exception("Bu alan zorunludur.");
			 return $data;
		}
	?>
	
�zel widget yazmak
------------------
 �zel widget yazmak i�in widgets.php �zerindeki Widget s�n�f�ndan ba�ka bir s�n�f t�retmeniz gerekmektedir. A�a��daki �rnekte widget s�n�f�ndan t�retilmi� bir s�n�f g�z�kmekte.
	<?php
	class EmailInput extends TextInput {
		 var $validators = Array("email_validator");
	}
	?>

 A�a��daki kod par�as�nda ise t�retti�iniz s�n�flarda kullanabilece�iniz �zelikleri g�sterilmekte. name, value, label �zellikleri form olu�tururken kullan�lmakta. type �zelli�ini widget'a �zel olarak belirtebilirsiniz. �rne�in password olarak belirtesiniz �ifre se�im giri�i elde edersiniz. choices �zelli�i �oklu se�imlerde kullan�lmaktad�r. attrs ile widget'a ait parametreleri belirtebilirsiniz. �rne�in textarea i�in rows, cols gibi. validators �zelli�i widget'�n default denetleyicisini belirtmekte. �rne�in EmailInput i�in email_validator validator'� default olarak gelmekte. string filters �zelli�i formdan gelen verinin ge�ece�i filtreleri belirmekte. strip_tags formdaki html kodlar�n� temizler. trim ise girilen metinin ba��ndaki ve sonundaki gereksiz bo�luklar� temizler. Bu iki filter default olarak gelmekte. Dilerseniz kendi filter fonksiyonunuzu yaz�p, burada string olarak belirtebilirsiniz.
 
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
 Demolar, d�k�mantasyon ve daha detayl� bilgi i�in;
 http://fatiherikli.com/fejax/