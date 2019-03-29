<?php
$srcurl = 'http://localhost//projects/joke_cms/admin/recentjokes/controller.php';
$docRoot = $_SERVER['DOCUMENT_ROOT'] . '/projects/joke_cms';
$tempfilename = $docRoot . '/admin/recentjokes/tempindex.html';
$targetfilename = $docRoot . '/admin/recentjokes/index.html';

if (file_exists($tempfilename)) {
    unlink($tempfilename);
}

$html = file_get_contents($srcurl);
if (!$html)
{
    $error = "Unable to load $srcurl. Static page update aborted!";
    include $docRoot . '/error.html.php';
    exit();
}

if (!file_put_contents($tempfilename, $html)) {
    $error = "Unable to write $tempfilename. Static page update aborted!";
    include $docRoot . '/error.html.php';
    exit();
}

copy($tempfilename, $targetfilename);
// unlink($tempfilename);