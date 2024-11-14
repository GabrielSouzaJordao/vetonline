<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar ao banco de dados
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "clinica_veterinaria";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Capturar os dados do formulário
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $veterinario = $_POST['veterinario'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];

    // Consultar o banco para verificar se o horário já está ocupado
    $sql_verifica = "SELECT * FROM agendamentos 
                     WHERE data_consulta = '$data' 
                     AND hora_consulta = '$hora' 
                     AND veterinario = '$veterinario'";
    
    $resultado = $conn->query($sql_verifica);

    if ($resultado->num_rows > 0) {
        // Se já existir um agendamento para esse horário, redireciona para "indisponivel.html"
        header("Location: indisponivel.html");
        exit();
    } else {
        // Inserir os dados no banco se o horário estiver disponível
        $sql_insert = "INSERT INTO agendamentos (nome_cliente, telefone_cliente, veterinario, data_consulta, hora_consulta)
                       VALUES ('$nome', '$telefone', '$veterinario', '$data', '$hora')";

        if ($conn->query($sql_insert) === TRUE) {
            // Se o agendamento for bem-sucedido, redireciona para "parabéns.html"
            header("Location: parabens.html");
            exit();
        } else {
            echo "Erro: " . $sql_insert . "<br>" . $conn->error;
        }
    }

    // Fechar a conexão
    $conn->close();
}
?>
