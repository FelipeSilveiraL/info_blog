<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Intranet Grupo Servopa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <!-- styles -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600,700" rel="stylesheet">
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
  <link href="assets/css/docs.css" rel="stylesheet">
  <link href="assets/css/prettyPhoto.css" rel="stylesheet">
  <link href="assets/js/google-code-prettify/prettify.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/color/default.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>  
  <script src="../js/seg.js" crossorigin="anonymous"></script>

  <!-- Favicons -->
  <link href="../img/favicon.ico" rel="icon">
  <link href="../img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- =======================================================
    Theme Name: Serenity
    Theme URL: https://bootstrapmade.com/serenity-bootstrap-corporate-template/
    Author: BootstrapMade.com
    Author URL: https://bootstrapmade.com
  ======================================================= -->
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar">
  <section id="maincontent">
    <div class="container">
      <div class="row">
        <div class="span8">
          <?php
          //chamando banco de dados
          include "../inc/conexao.php";

          //chamando as filiais
          $query_filial = "SELECT * FROM blog_filiais WHERE deletar = 0";
          $resultado_filial = mysqli_query($banco_blog, $query_filial);

          //limite de publicação
          $limit = 6;

          //datahoje

          $dataHoje = date('Y-m-d');

          //tipo de arquivo
          $video = "video/mp4";
          $pdf = "application/pdf";

          //pesquisa para o BD
          $busca = "SELECT 
              BP.id_postagem, 
              BP.titulo, 
              BP.tipo_arquivo,
              BP.file_img AS caminho, 
              BP.mensagem, 
              BP.data,
              BP.data_drop,
              BU.exibicao AS usuario,
              BP.carousel
            FROM
              blog_post BP
            LEFT JOIN
              blog_user BU ON BP.id_post_user = BU.id_user
            ORDER BY BP.id_postagem DESC limit " . $limit . "";
          // vamos criar as postagens
          $result_busca = mysqli_query($banco_blog, $busca);

          while ($dados = mysqli_fetch_array($result_busca)) {

            //contador de comentários
            $comentario =  "SELECT count(*) AS contagem FROM blog_comentarios WHERE id_postagem = '" . $dados['id_postagem'] . "'";
            $result = mysqli_query($banco_blog, $comentario);
            $row_comentario = mysqli_fetch_assoc($result);


              if ($dados['mensagem'] != NULL) {
                echo "<article class='blog-post'>
                <div class='post-heading'><!--titulo-->
                  <h3><a href='../blog/postagem.php?id_post=" . $dados['id_postagem'] . "' target='_blank'>" . $dados['titulo'] . "</a></h3>
                </div><!--fim titulo-->
                <div class='row'><!--corpo-->
                    <div class='span3'><!--imagem-->
                      <div class='post-image'>";
                if ($dados['tipo_arquivo'] == $video) {
                  echo "
                                <video width='295' controls>
                                    <source src='../blog/'" . $dados['caminho'] . "' type='video/mp4'>
                                    <source src='../blog/" . $dados['caminho'] . "' type='video/ogg'>
                                    Seu navegador não suporta HTML5 video.
                                </video>";
                } elseif ($dados['tipo_arquivo'] == $pdf) {
                  echo "<iframe src='../blog/" . $dados['caminho'] . "' height='400' width='290'></iframe>";
                } else {
                  echo "<a href='../blog/postagem.php?id_post=" . $dados['id_postagem'] . "' target='_blank'><img src='../blog/" . $dados['caminho'] . "' style='width: 100%;'/></a>";
                }
                echo "
                      </div>
                    </div><!--Fim imagem-->
  
                    <div class='span5'>
                    <ul class='post-meta'>
                      <li class='first'>
                        <i class='icon-calendar'></i><span>" . date('d/m/Y', strtotime($dados['data'])) . "</span>
                      </li>
                      <li>
                        <i class='icon-list-alt'></i>
                        <span>
                          <a href='../blog/postagem.php?id_post=" . $dados['id_postagem'] . "' target='_blank' title='Adicione um comentário'>" . $row_comentario['contagem'] . " comentários</a>
                        </span>
                      </li>
                      <li class= 'last'>
                        <i class='icon-tags'></i>
                        <span>
                          <a href='../blog/postagem.php?id_post=" . $dados['id_postagem'] . "' target='_blank'>" . $dados['usuario'] . "</a>
                        </span>
                      </li>
                    </ul>
                    <div class='clearfix'>
                    </div>
                    <p>
                      " . $dados['mensagem'] . "
                    </p>
                  </div>
                </div><!--fim corpo-->
              </article>";
              } else {

                echo "<article class='blog-post'>
                <div class='post-heading'>
                  <h3><a href='../blog/postagem.php?id_post=" . $dados['id_postagem'] . "' target='_blank'>" . $dados['titulo'] . "</a></h3>
                </div>";

                if ($dados['tipo_arquivo'] == $video) {
                  echo "
                      <video width='560' controls>
                          <source src='../blog/" . $dados['caminho'] . "' type='video/mp4'>
                          <source src='../blog/" . $dados['caminho'] . "' type='video/ogg'>
                          Seu navegador não suporta HTML5 video.
                      </video>";
                } elseif ($dados['tipo_arquivo'] == $pdf) {
                  echo "<iframe src='../blog/" . $dados['caminho'] . "'width='570' height='300'></iframe>";
                } elseif ($dados['carousel'] == 1) {

                  $carrorel = "SELECT file_img FROM blog_post_carousel WHERE id_postagem = " . $dados['id_postagem'] . "";

                  $reCarrosel = mysqli_query($banco_blog, $carrorel);

                  echo "<div class='container'>
                  <div id='myCarousel' class='carousel slide' data-ride='carousel'>
                    <!-- Wrapper for slides -->
                    <div class='carousel-inner'>";

                  $conte = 0;

                  while ($row_carrosel = mysqli_fetch_assoc($reCarrosel)) {

                    switch ($conte) {
                      case '0':
                        echo "<div class='item active'><img src='../blog/" . $row_carrosel['file_img'] . "'></div>";
                        break;

                      default:
                        echo "<div class='item'><img src='../blog/" . $row_carrosel['file_img'] . "'></div>";
                    }
                    $conte++;
                  }

                  echo "
                    </div>
                
                    <!-- Left and right controls -->
                    <a class='left carousel-control' href='#myCarousel' data-slide='prev'><
                    </a>
                    <a class='right carousel-control' href='#myCarousel' data-slide='next'>>
                    </a>
                  </div>
                </div>";
                } else {
                  echo "<a href='../blog/postagem.php?id_post=" . $dados['id_postagem'] . "' target='_blank'><img src='../blog/" . $dados['caminho'] . "' style='width: 100%;'/></a>";
                }
                echo "
                <ul class='post-meta'>
                  <li class='first'><i class='icon-calendar'></i><span>" .date('d/m/Y', strtotime($dados['data'])) . "</span></li>
                  <li><i class='icon-list-alt'></i><span><a href='../blog/postagem.php?id_post=" . $dados['id_postagem'] . "' target='_blank' title='Adicione um comentário'>" . $row_comentario['contagem'] . " comentários</a></span></li>
                  <li class= 'last'><i class='icon-tags'></i><span><a href='../blog/postagem.php?id_post=" . $dados['id_postagem'] . "' target='_blank'>" . $dados['usuario'] . "</a></span></li>
                </ul>
              </article>";
              } //Fim IF postagem

          } //Fim While postagem
          ?>

        </div>
        <div class="span4">
          <aside>

            <div class="widget">
              <h4>Diversos</h4>
              <ul class="cat">
                <li><a href="../cardapio/" target="_blank">Cardápio</li>
                <li><a href="http://10.100.1.217/lista/index.html" target="_blank">Lista de Ramais</a></li>
                <li><a href="http://10.100.1.217/glpi/index.php?noAUTO=1" target="_blank">Abertura de chamados</a></li>
              </ul>
            </div>


            <div class=" widget">
              <h4>Grupo Servopa</h4>
              <div style="margin-left: 15px;">
                <ul>
                  <?php

                  while ($linha_filial = mysqli_fetch_assoc($resultado_filial)) {
                    echo "<li>
                                            <div class='col-lg-4 col-md-6 portfolio-item filter-app'>
                                                <div class='portfolio-wrap'>
                                                    <div class='portfolio-info'>
                                                          <a href='" . $linha_filial['link'] . "' target='_blank' title=" . $linha_filial['titulo'] . ">
                                                            <img src='" . $linha_filial['imagem'] . "' class='img-fluid imagem5'>
                                                          </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>";
                  }
                  ?>
                </ul>
              </div>
            </div>
            <div class="widget">
              <h4>Departamentos</h4>
              <ul class="cat">
                <li><a href="https://sites.google.com/a/servopa.com.br/rh/home" target="_blank">Recursos Humanos</a></li>
                <li><a href="https://sites.google.com/a/servopa.com.br/auditoria/" target="_blank">Auditoria</a></li>
                <li><a href="https://sites.google.com/servopa.com.br/gestaocompartilhada/gest%C3%A3o-compartilhada" target="_blank">Gestão Compartilhada</a></li>
                <li><a href="http://10.100.1.217/unico/index.php" target="_blank">T.I</a></li>
                <li><a href="https://sites.google.com/servopa.com.br/cadastro/home" target="_blank">Cadastro</a></li>
                <li><a href="http://10.100.1.217/unico/index.php" target="_blank">Peças</a></li>
              </ul>
            </div>
            <div class="widget">
              <h4>Postagens Recentes</h4>
              <ul class="recent-posts">
                <?php
                $post_recent = "SELECT 
                   id_postagem,
                   titulo,
                   data 
                 FROM  
                   blog_post BP 
                 WHERE 
                   BP.deletar = 0 ORDER BY id_postagem DESC Limit 10";
                $result_recent = mysqli_query($banco_blog, $post_recent);

                while ($linha = mysqli_fetch_assoc($result_recent)) {

                  //contador de comentários
                  $comentario_recent =  "SELECT count(*) AS contagem FROM blog_comentarios WHERE id_postagem = '" . $linha['id_postagem'] . "'";
                  $result_recente = mysqli_query($banco_blog, $comentario_recent);
                  $linha_comentario = mysqli_fetch_assoc($result_recente);

                  echo "                  
                          <li><a href='../blog/postagem.php?id_post=" . $linha['id_postagem'] . "' target='_blank'>" . $linha['titulo'] . "</a>
                          <div class='clear'>
                          </div>
                          <span class='date'><i class='icon-calendar'></i>" . date('d/m/Y', strtotime($linha['data'] )). "</span>
                          <span class='comment'><i class='icon-comment'></i> " . $linha_comentario['contagem'] . " Comentários</span>
                        </li>
                    ";
                }
                ?>
              </ul>
            </div>
          </aside>
        </div>
      </div>
    </div>
  </section>
</body>

</html>