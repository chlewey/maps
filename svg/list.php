<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Maps test</title>
<link rel="stylesheet" href="/bs/css/bootstrap.min.css" type="text/css">
<link href="my.css" rel="stylesheet" type="text/css">
<style></style>
</head>
<body>
	<div class="container">
		<table>
<?php

$d = dir('.');
while (false !== ($entry = $d->read())) {
	if ( preg_match('{colombia.[-_\w=]*.svg$}',$entry) ) {
		$q = explode('.',$entry);
		$t = date('M j (Y) H:i:s',base64_decode($q[1]));
		$a1 = "<a href=\"$entry\">SVG normal</a>";
		$a2 = "<a href=\"{$entry}z\">SVG comprimido</a>";
		echo "\t\t\t<tr><td style='padding:2px'>$t</td><td style='padding:2px'>$a1</td><td style='padding:2px'>$a2</td></tr>\n";
	}
}
$d->close();

?>

		</table>
	</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="/bs/js/bootstrap.min.js"></script>
</body>
</html>
