<?php
session_start();
// Dados de conexão com o banco de dados
$host = 'localhost';
$user = 'gustavo';
$password = 'Gustavo7327';
$dbname = 'vacineja'; 

// Cria a conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $hora = $_POST['hora'];
    $data = $_POST['data'];
    $descricao = $_POST['descricao'];
    $idVacina = $_POST['id']; // ID da vacina a ser atualizada

    // Buscar o caminho da imagem atual (se existir)
    $sql = "SELECT url_imagem FROM Vacina WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idVacina);
    $stmt->execute();
    $stmt->bind_result($urlImagemAtual);
    $stmt->fetch();
    $stmt->close();

    // Verificar se o arquivo foi enviado
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == UPLOAD_ERR_OK) {
        // Se uma nova imagem foi enviada, fazer o upload
        $arquivo = $_FILES['arquivo'];
        $caminhoImagem = '../uploads/' . $_FILES['arquivo']['name'];

        // Mover a imagem para a pasta 'uploads'
        if (move_uploaded_file($arquivo['tmp_name'], $caminhoImagem)) {
            // Se uma nova imagem foi carregada com sucesso, excluir a imagem anterior
            if ($urlImagemAtual && file_exists($urlImagemAtual)) {
                unlink($urlImagemAtual); // Excluir a imagem antiga do servidor
            }
        } else {
            exit();
        }
    } else {
        // Se não houver novo arquivo, manter o caminho da imagem atual
        $caminhoImagem = $urlImagemAtual;
    }

    // Atualizar os dados da vacina no banco de dados
    $sql = "UPDATE Vacina SET nome = ?, idade_recomendada = ?, descricao = ?, data_aplicacao = ?, url_imagem = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $nome, $idade, $descricao, $data, $caminhoImagem, $idVacina);
    $stmt->execute();
    $stmt->close();

    // Redirecionar para a página Catálogo de vacinas - ADM/index.php
    header("Location: ../Catálogo de vacinas - ADM/index.php");
    exit(); // Importante para garantir que o script seja interrompido após o redirecionamento
}
?>
