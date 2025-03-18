<?php
$output = null;
$result =exec("bun src/dist/index.ts \\LaTex",$output);

echo $result;