<?php
// Conexão ao banco de dados
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "cadastro";

// Cria a conexão
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Recebendo os dados do formulário
$username = $_POST['username'];
$email = $_POST['email'];
$senha = $_POST['password'];

// Consulta no banco de dados
$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Usuário encontrado, agora verificar a senha
    $usuario = $result->fetch_assoc();

    if (password_verify($senha, $usuario['senha'])) {
        // Login realizado com sucesso, redirecionar para home.html
        header("Location: home.html");
        exit(); // Termina a execução do script para garantir o redirecionamento
    } else {
        echo "Senha incorreta!";
    }
} else {
    echo "Usuário ou e-mail não encontrado!";
}

$stmt->close();
$conn->close();
?>
