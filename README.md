**Rotas**

**Index**

http://127.0.0.1:8787/posts/index?limit=NumeroPostsPorPagina&current_page=NumeroPagina (rota GET)

 - A rota index recebe 2 parametros
 - `limit`
   - Quantidade de posts para mostrar por página(sem valor padrão, o parametro sempre deve ser passado).
 - `current_page
   - Pagina atual para ser mostrada
 - Se não houverem posts na pagina atual irá retornar falha.


**view**

http://127.0.0.1:8787/posts/view?post=slugOuId (rota GET)
  - A rota recebe 1 parametro
  - `post`
    - o parametro pode ser o slug ou o ID do post a ser visualizado, cada visualização aumenta a contagem de views em 1.

**Add**

http://127.0.0.1:8787/posts/add (rota POST)

 - A rota recebe os parametros:
 - `authorName`
   - Nome do autor, não pode estar vazia, deve ter no mínimo 3 caracteres e máximo 26, o nome não pode conter caracteres especiais
 - `slug`
   - Slug para o post, não pode estar vazio, deve ter no mínimo 8 caracteres e seguir o formato "link" sem caracteres especiais.
 - `image`
   - String simples para uma imagem, o campo é opcional, deve ser usado para adicionar link para a imagem selecionada.
 - `content`
   - Conteúdo do post, o campo é opcional e auto explicativo.
 - Os campos `image` e `content` são opcionais, mas ao menos um dos 2 deve estar preenchido, criar um post com os 2 parâmetros vazios retornará um erro.

**Edit**

http://127.0.0.1:8787/edit?post=slugOuId (rota GET)

  - A rota recebe o parametro `post` que deve ser o slug ou id do post e o retorna para visualização/edição

http://127.0.0.1:8787/posts/edit (rota POST)

  - A rota recebe os parametros:
  - `uuid`
    - O id do post(é opcional) usado para selecionar o post para edição
  - `slug`
    - Slug do post, se ele for atualizado, o `uuid` se torna obrigatório para poder encontrar o post para edição
  - `authorName`
    - Na edição ele se torna opcional, caso não altere manterá o valor já existente.
  - `image`
    - Parametro opcional na edição, caso não alterado manterá o valor original
  - `content`
    - Parametro opcional també, caso não alterado manterá o valor original.

**Delete**

http://127.0.0.1:8787/posts/delete?post=slugOuId (rota DELETE)

  - A rota recebe o parametro:
  - `post`
    - Pode ser tanto o ID quanto o slug do post a ser deletado, caso o campo esteja vazio ou incorreto receberá uma falha na requisição.

**A entidade Post(Post.php)**

A entidade que cuida dos posts de um modo geral(poderia estar muito mais otimizada com mais tempo)

Tem por padrão os valores:
 - `uuid`
   - id unico gerado automaticamente na criação de um novo post
 - `author_name`
   - nome do autor, já explicado acima, é um valueObject com suas proprias regras de validação
 - `slug`
   - slug para utilizar como link para visualização do post, também é um valueObject com as proprias regras de validação
 - `image`
   - campo string para utilização de um link de imagem na criação/edição do post
 - `content`
   - conteúdo principal do post, não tem regras de validação quanto ao tamanho ou caracteres, contanto que seja uma string
 - `views`
   - contagem de visualizações do post, a cada acesso a rota view é adicionada uma validação
 - `created`
   - data de criação do post em timestamp, é adicionada assim que o post é gerado
 - `updated`
   - data da ultima atualização do post também em timestamp, é atualizado a cada vez que o post é editado.


**Como iniciar a aplicação**

A primeira coisa a se fazer é criar uma cópia da aplicação no seu local de teste com o comando:
 - git clone https://github.com/ATCamposs/blog-posts-project

Logo depois, acessar a pasta do repositório e utilizar o composer para instalar as dependencias:
 - `composer install` 

Como foi utilizado mongoDB como banco de dados, você pode ter uma amostra do banco de dados sem a necessidade de instalação utilizando o https://mlab.com/plans/pricing/#plan-type=sandbox na versão gratuita.

A aplicação foi construida utilizando como base o microframework webman(https://github.com/walkor/webman)(derivado do workerman(https://github.com/walkor/workerman)).

Então para iniciá-lo em modo de desenvolvimento utilize o comando:
 - `php start.php start`
 - Para utilizar a versão de produção use o comando:
   - php start.php start -d
 - O servidor estará acessível no endereço `http://127.0.0.1:8787`
