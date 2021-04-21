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

Os requisitos necessários são:
 - Sistema operacional linux
 - PHP-CLI(>=5.3.3) recomendo fortemente 7.4 ou mais recente
   - extensões `pcntl` e `posix`
 - libevent é recomendado mas não é obrigatório

Confirmados os requisitos basicos, devemos configurar o .env da aplicação.
 - Copie o arquivo .env.example para um arquivo chamado .env
 - Para utilizar o sistema só é necessário trocar 2 chaves para completo funcionamento
 - `MONGO_DSN=`
   - DSN que a mlab(https://mlab.com/plans/pricing/#plan-type=sandbox) disponibiliza gratuitamente para acesso remoto(com limitações)
 - `MONGO_DATABASE=`
   - Nome do banco de dados criado ao criar sua conta no mlab.

Depois de tudo configurado, podemos conferir os testes no sistema
 - Utilizando o comando `vendor/bin/phpunit` rodamos todos os testes necessários para ver que está tudo em ordem.

Feito isso, podemos iniciar o sistema. Então para iniciá-lo em modo de desenvolvimento utilize o comando:
 - `php start.php start`
 - Para utilizar a versão de produção use o comando:
   - php start.php start -d
 - O servidor estará acessível no endereço `http://127.0.0.1:8787`

**Porque utilizar o webman(workerman)**
 - Diferente do swoole que necessita de uma instalação a parte para funcionar, o webman só necessita de algumas extensões do PHP de fácil instalação e está apto para rodar em praticamente qualquer maquina.
 - O webman já vem com alguns componentes por padrão tais como o `Eloquent/illuminate` do laravel, tornando simples e prática sua utilização, dando suporte para muitos tipos de bancos de dados e criando model's simples e práticas, já vem com exemplos de middlewares e rotas, além de contar com o blade para quem gosta de um framework FullStack.
 - O real motivo para minha utilização do webman é sua velocidade e escalabilidade, geralmente se mantendo no TOP 10 de frameworks mais rápidos no momento, batendo linguagens compiladas como Go (https://github.com/the-benchmarker/web-frameworks)

**Padrões de projeto utilizados**
 - Primeiramente, a parte mais importante no desenvolvimento de software a longo prazo tem mostrado ser o uso do `TDD`.
   - Desenvolver sem testes torna o sistema perigoso e pouco escalável, especialmente em códigos mal escritos que precisam de refatoração em seu nucleo constantemente.
 - O `DDD` aplicado neste projeto torna o `Post` como entidade "nucleo" do domínio, fazendo com que ele se expanda de dentro pra fora.
   - Um dos motivos do projeto não estender a Model do eloquent para criação da entidade é o maior controle sobre todo o código e uma melhor demonstração de como ela funciona "debaixo do capô", além de que em um sistema escalável, objetos grandes como os que estendem de ORM's geralmente são pesados demais, consumindo mais memória do que realmente necessitariam. Além de tornar as buscas mais lentas(https://www.youtube.com/watch?v=3TJfR1Ta4GU)
   - Não condeno seu uso, na verdade acho que para a maioria dos projetos ele se destaca pela redução de mão de obra e reescrita de código.
 - **Separando os objetos**
   - A parte principal de qualquer sistema é a persistencia no banco de dados, que aqui é feita pela camada `repository`, nenhuma outra camada faz acessos ao banco além dela, garantindo controle de acesso e possibilidade de administração mesmo com grande expansão do código.
   - Utilizar o padrão `builder` para gerar os novos posts e já verificar suas regras de negócio devido a ligação com os valueObjets torna o código mais limpo e de fácil entendimento.
   - Utilizar a `abstract factory` para geração de repositórios faz com que se possa utilizar qualquer banco de dados, já que ao implementar uma classe abstrata presente no domínio torna suas implementações "obrigatórias" não se fazendo necessárias modificações na entidade cada vez que trocamos de bancos de dados.

Muitas partes do projeto ainda estão incompletas e podem ser melhoradas, sinta-se livre para qualquer contribuição ;)
