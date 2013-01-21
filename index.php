<?php

define('ROOT', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'docs');

if (isset($_REQUEST['doc']) && !empty($_REQUEST['doc'])) {

	$doc = ROOT . '/' . $_REQUEST['doc'];

	if (($fullpath = realpath($doc)) && file_exists($fullpath) && stripos($fullpath, ROOT, 0) === 0) {

		echo '<link rel="stylesheet" href="assets/css/styles.css">';
		echo '<div class="box box-text documentation">';
		$output = file_get_contents($fullpath);
		$output = preg_replace('#src="\.\.\/\.\.\/\.\.\/#', 'src="', $output);
		echo $output;
		echo '</div>';

	}

} else {

	?>

	<h1>Documentation</h1>

	<ul class="sections">

		<?php 

		function ls($path, &$html = array()) {

			foreach (new FilesystemIterator($path, FilesystemIterator::SKIP_DOTS) as $file) {

				if ($file->getFilename() == '.DS_Store') {
					continue;
				} elseif ($file->isDir()) {
					$html[] = "\n<li>";
					$html[] = $file->getBaseName();
					$html[] = '<ul>';
					$html[] = ls($file->getPathname());
					$html[] = '</ul>';
					$html[] = '</li>';
				} else {
					$link = ltrim(preg_replace('/^'.preg_quote(str_replace(DIRECTORY_SEPARATOR, '/', ROOT), '/').'/i', '', str_replace(DIRECTORY_SEPARATOR, '/', $file)), '/');
					$html[] = "\n<li>";
					$html[] = '<a href="index.php?doc='.urlencode($link).'">'.$file->getBaseName().'</a>';
					$html[] = '</li>';
				}

			}

			return implode("\n", $html);
		}

		echo ls(ROOT);

		?>

	</ul>

<?php

}