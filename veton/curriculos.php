<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "curriculos_db";

// Criar a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Receber dados do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_completo = $_POST['nome'];
    $telefone = $_POST['telefone'];
    
    // Upload do arquivo de currículo
    $curriculo = $_FILES['curriculo'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($curriculo["name"]);

    // Verifica se a pasta "uploads" existe, se não, cria
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Move o arquivo para a pasta uploads
    if (move_uploaded_file($curriculo["tmp_name"], $target_file)) {
        // Inserir os dados no banco de dados
        $sql = "INSERT INTO curriculos (nome_completo, telefone, caminho_curriculo)
                VALUES ('$nome_completo', '$telefone', '$target_file')";
        
        if ($conn->query($sql) === TRUE) {
            // Redirecionar para home.html
            header("Location: home.html");
            exit; // Encerra o script após o redirecionamento
        } else {
            echo "Erro: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Erro ao fazer o upload do currículo.";
    }
}

$conn->close();
?>
