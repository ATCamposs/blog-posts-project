**Rota 1**

**Index**

http://127.0.0.1:8787/posts/index?limit=NumeroPostsPorPagina&current_page=NumeroPagina (rota GET)

 - A rota index recebe 2 parametros
 - `limit`
   - Quantidade de posts para mostrar por página(sem valor padrão, o parametro sempre deve ser passado).
 - `current_page
   - Pagina atual para ser mostrada
 - Se não houverem posts na pagina atual irá retornar falha.


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
  - `_id`
    - O id do post(é opcional) usado para selecionar o post para edição
  - `slug`
    - Slug do post, se ele for atualizado, o `_id` se torna obrigatório para poder encontrar o post para edição
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

