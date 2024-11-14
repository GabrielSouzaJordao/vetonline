<?php
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

// Query para buscar agendamentos a partir da data e hora atuais
$sql = "SELECT nome_cliente, telefone_cliente, veterinario, data_consulta, hora_consulta 
        FROM agendamentos 
        WHERE data_consulta > CURDATE() 
        OR (data_consulta = CURDATE() AND hora_consulta > CURRENT_TIME()) 
        ORDER BY data_consulta, hora_consulta";
$resultado = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="src/imagens/VET ON.png" type="image/x-icon">
    <title>Lista de Agendamentos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #074c49;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agendamentos Veterinários</h1>

        <?php
        if ($resultado->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Nome do Cliente</th><th>Telefone</th><th>Veterinário</th><th>Data</th><th>Hora</th></tr>";
            
            // Exibir cada agendamento em uma linha da tabela
            while ($row = $resultado->fetch_assoc()) {
                $telefone_formatado = preg_replace('/[^0-9]/', '', $row['telefone_cliente']); // Remover caracteres não numéricos
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nome_cliente']) . "</td>";
                // Exibe o telefone como um link para o WhatsApp
                echo "<td><a href='https://wa.me/55" . $telefone_formatado . "' target='_blank'>" . htmlspecialchars($row['telefone_cliente']) . "</a></td>";
                echo "<td>" . htmlspecialchars($row['veterinario']) . "</td>";
                echo "<td>" . htmlspecialchars($row['data_consulta']) . "</td>";
                echo "<td>" . htmlspecialchars($row['hora_consulta']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhum agendamento encontrado.</p>";
        }

        // Fechar a conexão
        $conn->close();
        ?>
        <h2>Clique no número de telefone e entre em contato com o Paciente</h2>
    </div>
</body>
</html>
