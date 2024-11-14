<?php
// Conexão ao banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cadastro";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Recebendo os dados do formulário
$nome = $_POST['name'];
$username = $_POST['username'];
$email = $_POST['email'];
$senha = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash da senha

// Validação de dados (exemplo simples)
if (empty($nome) || empty($username) || empty($email) || empty($_POST['password'])) {
    die("Preencha todos os campos.");
}

// Verifica se o username ou email já existe no banco de dados
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Erro: O username ou email já está em uso.";
    $stmt->close();
    $conn->close();
    exit();
}

// Usando prepared statements para inserir os dados no banco
$stmt = $conn->prepare("INSERT INTO usuarios (nome, username, email, senha) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $username, $email, $senha);

if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
    // Redireciona para a página de login após o cadastro
    header("Location: meuperfil.html");
    exit(); // Sempre use exit após header para garantir que o script pare de ser executado
} else {
    echo "Erro: " . $stmt->error;
}

// Fecha a conexão
$stmt->close();
$conn->close();
?>
