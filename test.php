    <?php
    $file = fopen("words5.txt","r");
    $file2 = fopen("words.txt", "w");

    $iv = openssl_random_pseudo_bytes(16);

    fwrite($file2, $iv . "\n");

    while(! feof($file)) {
        $word = trim(fgets($file));
        $cipher = "aes-128-cbc";
        
        $encrypted = openssl_encrypt($word, $cipher, "Briton Westerhaus Lingo", 0, $iv);
        fwrite($file2, substr($encrypted, 0, strlen($encrypted) - 2));
    }

    fclose($file);
    fclose($file2);

    $file = fopen("words.txt","r");

    $iv = trim(fgets($file));

    while(! feof($file)) {
        $word = trim(fread($file, 22)) . "==";
        $cipher = "aes-128-cbc";
        //$iv = openssl_random_pseudo_bytes(16);
        $decrypted = openssl_decrypt($word, $cipher, "Briton Westerhaus Lingo", 0, $iv);
        echo $decrypted . "<br />";
    }

    fclose($file);
?>