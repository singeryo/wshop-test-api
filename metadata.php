<?php
/**
 * @desc Metadata/help page.
 * This will automatically list each controller, along with a table containing the URI, Verb and Description
 * of each method based on the @uri, @verb and @desc comments within the controller file
 * @author Paul Doelle
 */

$controllers = [];

foreach (glob("controllers/*.php") as $filename) {
    if ($filename != "controllers/ApiController.php") {
		$controller = new stdClass();
		$controller->file = $filename;
		$controller->name = substr($filename, 12, strpos($filename, 'Controller') - 12);
		$controller->methods = [];

		$controllers[] = $controller;
		unset($controller);
    }
}

for ($ctr = 0; $ctr < count($controllers); $ctr++) {
	$source = file_get_contents($controllers[$ctr]->file);
	$comment_tokens = [];
	
	$tokens = token_get_all($source);

	foreach ($tokens as $token) {
        // PHP 5.4 needs 370
        // PHP > 7 needs 380
        if ($token[0] == 380) {
            $comment_tokens[] = $token;
        }
	}
	
	foreach ($comment_tokens as $token) {

		if (preg_match("/@uri.*@verb.*@desc/is", $token[1])) {
			preg_match("/@uri\s*(.*)\s/i", $token[1], $uri);
			preg_match("/@verb\s*(.*)\s/i", $token[1], $verb);
			preg_match("/@desc\s*(.*)\s/i", $token[1], $desc);
			$method = new StdClass();
			$method->uri = $uri[1];
			$method->verb = $verb[1];
			$method->desc = $desc[1];
			$controllers[$ctr]->methods[] = $method;
			unset($method);
		}
	}
}

?>
<html lang="fr">
    <head>
        <title>API Metadata</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <script type='text/javascript' src='http://code.jquery.com/jquery-2.0.3.js'></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    </head>
<body>
<div class="container">
	<h1>API Metadata Page</h1>
	<br/>
	<?php foreach ($controllers as $ctr): ?>
	<div class="col-sm-6">
		<table class="table table-hover table-bordered">
			<caption><?php echo $ctr->name; ?> Controller</caption>
            <thead>
				<tr>
					<th>URI</th>
					<th>Verb</th>
					<th>Description</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($ctr->methods as $method): ?>
				<tr>
					<td><?php echo $method->uri; ?></td>
					<td><?php echo $method->verb; ?></td>
					<td><?php echo $method->desc; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php endforeach; ?>
</div>

</body>
</html>














