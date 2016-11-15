<?php

$file = file_get_contents('./fds_gold.txt');
mb_convert_variables('utf-8', 'sjis', $file);

$fp = fopen('replace-list.txt', 'r');


if ($fp){
    if (flock($fp, LOCK_SH)){
        while (!feof($fp)) {
            $line = trim(fgets($fp));
            $arLine = split('=', $line);
            if($arLine[1] != '' && preg_match("/{$arLine[1]}/", $file) === 1){
            	$file = preg_replace("/{$arLine[1]}/", $arLine[0] . "*" , $file);
            } else{
            	echo $arLine[1]."\n";
            }
        }

        flock($fp, LOCK_UN);
    }else{
        print('ファイルロックに失敗しました');
    }
}

fclose($fp);

mb_convert_variables('sjis', 'utf-8', $file);

file_put_contents('fds.txt', $file);