<?php

return [
    //ValueObject/AuthorName.php
    'The author name cannot be empty.' => 'O nome do autor não pode estar vazio.',
    'The author name must be at least 3 characters.' => 'O nome do autor deve ter pelo menos 3 caracteres.',
    'The author name must be less than 26 characters.' => 'O nome do autor deve ter menos de 26 caracteres.',
    'The author name cannot have special characters.' => 'O nome do autor não pode ter caracteres especiais.',

    //ValueObject/Slug.php
    'The slug cannot be empty.' => 'O slug não pode estar vazio.',
    'The slug must be at least 8 characters.' => 'O slug deve ter pelo menos 8 caracteres.',
    'The slug is not in the correct format.' => 'O slug está no formato incorreto.',

    //Domain/Post.php
    'Author name updated successfully.' => 'Nome do autor atualizado com sucesso.',
    'Error, there is already a post with this slug.' => 'Erro, já existe um post com este slug.',
    'Post saved successfully.' => 'Post salvo com sucesso.',
    'Post updated successfully.' => 'Post atualizado com sucesso.',
    'You need to modify at least 1 field to be able to update the post.' =>
        'Você precisa modificar ao menos 1 campo para poder atualizar o post.',
    'The post could not be found.' => 'A postagem não pode ser encontrada.',
    'You cannot leave the image and content empty at the same time.' =>
        'Você não pode deixar a imagem e o conteúdo vazios ao mesmo tempo.',

    //Generic Error
    'Error, please try again.' => 'Erro, por favor tente novamente.'
];
