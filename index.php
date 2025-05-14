<?php
session_start();
if(!isset($_SESSION['email'])){
    header('Location: ../Login/index.html');
}
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

// Verifica se o ID da vacina foi passado na URL
if (isset($_GET['id'])) {
    $idVacina = $_GET['id'];
    
    // Consulta SQL para buscar os dados da vacina
  $sql = "
  SELECT Vacina.id, Vacina.nome, Vacina.idade_recomendada, Vacina.descricao ,Vacina.url_imagem, 
        Vacina.data_aplicacao, Posto.nome AS posto_nome, Posto.id AS posto_id
  FROM Vacina
  JOIN Posto ON Vacina.id_posto = Posto.id
  WHERE Vacina.id = ?
  ";

  // Preparar a consulta
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $idVacina);
  $stmt->execute();
  $result = $stmt->get_result();

  // Verifica se encontrou a vacina
  if ($result->num_rows > 0) {
    $vacina = $result->fetch_assoc();

    // Formatar a data_aplicacao
    $dataAplicacao = new DateTime($vacina['data_aplicacao']);
    $dataFormatada = $dataAplicacao->format('d/m/Y');  // Data no formato dd/mm/yyyy
    $horaFormatada = $dataAplicacao->format('H:i');    // Hora no formato hh:mm
  } else {
    die("Vacina não encontrada.");
  }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./vars.css">
  <link rel="stylesheet" href="./style.css">
  <link rel="shortcut icon" type="imagex/png" href="plus-svgrepo-com1.svg">
  <title>VacinaJá</title>
  
  <style>
   a,
   button,
   input,
   select,
   h1,
   h2,
   h3,
   h4,
   h5,
   * {
       box-sizing: border-box;
       margin: 0;
       padding: 0;
       border: none;
       text-decoration: none;
       background: none;
   
       -webkit-font-smoothing: antialiased;
   }
   
   menu, ol, ul {
       list-style-type: none;
       margin: 0;
       padding: 0;
   }
   </style>
  <title>Document</title>
</head>
<body>
  <div class="frame-36">
    <div class="frame-11">
      <div class="frame-112"> 
        <div class="water-drop-svgrepo-com-4">
          <img class="group-4" src="Group 4.svg" />
        </div>
      </div>
    </div>
    <form class="frame-57" method="post" action="vacina_processo.php">
      <div class="arq">
        <label for="arquivo">
          <input type="file" id="arquivo" name="arquivo">
        <div class="frame-15">
        <img src="<?php echo $vacina['url_imagem']; ?>" id="imagem" />
        </div>
      </label>
      </div>
      <input type="hidden" name="id" value="<?php echo $vacina['id']; ?>" required>
      <div class="frame-20">
        <div class="frame-56">
          <div class="frame-43">
            <div class="frame-45">
              <div class="tipo-de-vacina">Nome da vacina:</div>
              <label for="tip-vacina">
              <input type="text" id="tip-vacina"  name="nome" value="<?php echo htmlspecialchars($vacina['nome']); ?>" required style="color: white; width: 625px; margin-top: 5px;">
              </label>
              <div class="rectangle-2"></div>
            </div>
          </div>
          <div class="frame-44">
            <div class="frame-45">
              <div class="idade-recomendada">Idade recomendada:</div>
              <label for="idd">
              <input type="text" required id="idade" name="idade" value="<?php echo htmlspecialchars($vacina['idade_recomendada']); ?>" style="color: white; width: 625px; margin-top: 5px;">
              </label>
              <div class="rectangle-2"></div>
            </div>
          </div>
          
          <div class="frame-452">
            <div class="alterar-hor-rio-dispon-vel">
              Alterar horário disponível:
            </div>
            <label for="hora">
            <input type="time" required id="hora" name="hora" value="<?php echo htmlspecialchars($horaFormatada); ?>" style="color: white; width: 625px; margin-top: 5px;">            </label>
            <div class="rectangle-2"></div>
          </div>
          
          
          <div class="frame-49">
            <div class="data-00-00-0000">Data</div>
            <label for="data">
              <input type="date" required id="data" name="data" value="<?php echo $dataFormatada; ?>" style="color: white; margin-top: 5px;">
            </label>
            <div class="rectangle-2"></div>
          </div>
          <div class="frame-51">
            <div class="atualizar-descri-o-de-vacina">
              Atualizar descrição de vacina:
            </div>
            <textarea id="descricao" name="descricao" rows="7" required
            cols="55" style="background-color: white; opacity: 0.6; border-radius: 8px; width: 605px;"><?php echo htmlspecialchars($vacina['descricao']); ?>

            </textarea>
          </div>
          <button type="submit">
            <div class="frame-50">
              <div class="concluir">Concluir</div>
            </div>
          </button>
          
        </div>
      </div>
    </form>
  </div>

  <script>
    // Seleciona o input de arquivo e as tags de imagem
 const inputArquivo = document.getElementById('arquivo');
 const imagem = document.getElementById('imagem');
 
 // Adiciona o evento de mudança no input de arquivo
 inputArquivo.addEventListener('change', function(event) {
   const file = event.target.files[0];
 
   if (file) {
     // Cria uma URL de objeto para o arquivo de imagem
     const reader = new FileReader(); 
 
     reader.onload = function(e) {
       // Altera o src da imagem para o arquivo escolhido
       imagem.src = e.target.result;
 
       // Torna a imagem visível
       imagem.style.visibility = "visible";
 
       // Remove as dimensões fixas se você usar o estilo acima no CSS
       imagem.style.width = "100%";  // Agora a largura é 100% da div
       imagem.style.height = "100%"; // Agora a altura é 100% da div
       imagem.style.objectFit = "cover"; // Faz com que a imagem cubra a div sem distorcer
     };
 
     // Lê o arquivo de imagem
     reader.readAsDataURL(file);
   } else {
     console.log("Nenhum arquivo selecionado.");
   }
 });
 
 
   </script>
  
</body>
</html>