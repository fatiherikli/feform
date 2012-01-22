<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>FEForm - Simple Form Class</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>         
<?php
	include("feform/feform.php");

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

       
</body>
</html>