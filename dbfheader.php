<?php 
echo "\nCopyright @2020 harylalamentik@gmail.com\n\n";

if (count($argv) <= 1) {
    die("\nharus di sertai parameter nama folder atau nama file dbf \n");
}

if (!is_dir($argv[1])) {
    if (count($argv) > 1) {
        for ($i=1; $i <= count($argv)-1; $i++) {
            $output = basename($argv[$i]);
            if (!is_dir($argv[$i]) && file_exists($argv[$i])) {
                $output .= writeHeaderDBF($argv[$i]);
            } else {
                $output .= "tidak di temukan";
            }
            echo "$output";
        }
        die("\nSelesai...!\n");
    } else {
        die("\nharus di sertai parameter nama folder atau nama file dbf \n");
    }
}

$directory = $argv[1];
echo "Semua file dbf di dalam folder " . $directory ." adalah:\n";

$it = new RecursiveDirectoryIterator($directory);
$allowed=array("dbf","DBF");

foreach (new RecursiveIteratorIterator($it) as $file) {
    if (in_array(substr($file, strrpos($file, '.') + 1), $allowed)) {
        echo writeHeaderDBF($file);
    }
}
echo "\nSelesai...!\n";



/**
 * Tulis Ulang Header DFB
 *
 * @param [type] $file
 * @return void
 */
function writeHeaderDBF($file)
{
    $text = '';
    // rewrite first byte dbf
    $fp = fopen($file, 'r+b');
    $byte = 1;
    if (file_exists($file)) {
        $text .= "$file ditemukan, ";
    }
    fseek($fp, 0); // move to the position
    $text .= "ubah [" . str_pad(strtoupper(dechex(ord(fread($fp, $byte)))), 2, '0', STR_PAD_LEFT)  . "] ";
    fseek($fp, 0); // move to the position
    fwrite($fp, chr(hexdec('03')), $byte); // Overwrite the data in this position
    fseek($fp, 0); // move to the position
    $text .= "-> [" . str_pad(strtoupper(dechex(ord(fread($fp, $byte)))), 2, '0', STR_PAD_LEFT)  . "]\n";
    fclose($fp);
    return $text;
}
