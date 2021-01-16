<?php

//Build wasm
exec("cargo build --release --target wasm32-unknown-unknown");
exec("wasm-bindgen target/wasm32-unknown-unknown/release/untitled.wasm --out-dir wasm --no-modules --no-typescript");


//PHP server start
$dn = pathinfo(__FILE__)["dirname"];
$dna = explode(DIRECTORY_SEPARATOR, $dn);
$projectName = $dna[count($dna) - 1];

$indexHtml = <<<HTML
<html>
<head>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type" />
</head>
<body>
<canvas id="canvas" width="640" height="480"></canvas>
<script src="./PROJECTNAME.js"></script>
<script>
    window.addEventListener("load", async () => {
        await wasm_bindgen("./PROJECTNAME_bg.wasm.php");
    });
</script>
</body>
</html>
HTML;

$indexHtml = str_replace("PROJECTNAME", $projectName, $indexHtml);

$wasmDir = __DIR__ . DIRECTORY_SEPARATOR . "wasm";
if (!is_dir($wasmDir)) {
    @mkdir($wasmDir);
}
file_put_contents($wasmDir . DIRECTORY_SEPARATOR . "index.html", $indexHtml);
file_put_contents($wasmDir . DIRECTORY_SEPARATOR . "favicon.ico", "");

$bgWasmPHP = <<<PHP
<?php

header("Content-Type: application/wasm");
\$wasmFile = str_replace(".php", "", pathinfo(__FILE__)["basename"]);
readfile(__DIR__ . DIRECTORY_SEPARATOR . \$wasmFile);
PHP;
file_put_contents($wasmDir . DIRECTORY_SEPARATOR . $projectName . "_bg.wasm.php", $bgWasmPHP);

echo "\r\nStart run `php -S 0.0.0.0:7788 -t wasm`\r\n";
exec("php -S 0.0.0.0:7788 -t wasm");



