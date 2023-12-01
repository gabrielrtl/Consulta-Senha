
<?php
error_reporting(0);

if(isset($_POST['termo'])){
    $termo = $_POST['termo'];
}

$url = "https://beta.snusbase.com/v2/combo/{$termo}";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

ob_start();
curl_exec($ch);
curl_close($ch);
$file_content = "";
$file_content = ob_get_contents();
ob_end_clean();

?>



<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Dados</title>

</head>
<body>
    <form action="username.php" method="post">
        <p>Insira um email ou username</p>
        <input type="text" required="requied" name="termo"><input type=submit value="Enviar"/>
    </form>

<?php



if(isset($termo)){

    if ($obj = json_decode($file_content, true)){

        
        foreach ($obj['result'] as $banco => $entradas) {
            foreach ($entradas as $entrada) {
                $senha = $entrada['password'];
                if ($senha) {
                    $senhas[] = $senha;
                    
                }
            }
        }

        echo "<p>USUARIO: {$termo}</p>";
        echo "<p>POSSIVEIS SENHAS: </p>";

        echo "<ul id='minhaLista'>";
        foreach ($senhas as $key) {
                echo "<li>{$key}</li>";
        }
        echo "</ul>";

        echo"<button id='baixarLista'>Baixar Lista</button>";

    }

}
?>

<script>
document.getElementById("baixarLista").addEventListener("click", function() {
    var listaElement = document.getElementById("minhaLista");
    var listaItens = listaElement.getElementsByTagName("li");
    var listaComQuebras = "";
    for (var i = 0; i < listaItens.length; i++) {
        listaComQuebras += listaItens[i].textContent + "\n";
    }
    var blob = new Blob([listaComQuebras], { type: "text/plain" });
    var url = URL.createObjectURL(blob);
    var a = document.createElement("a");
    a.href = url;
    a.download = "minha_lista.txt";
    a.click();
    URL.revokeObjectURL(url);
});
</script>
</body>
</html>
