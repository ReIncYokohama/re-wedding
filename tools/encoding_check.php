<?php
echo "内部のencoding<br>";
echo mb_internal_encoding();

echo "<br>HTTP 入力文字エンコーディング<br>";
echo mb_http_input();

echo "<br>HTTP 出力文字エンコーディング<br>";
echo mb_http_output();
