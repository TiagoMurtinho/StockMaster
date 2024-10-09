<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser uma string.',
    'max' => [
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
    ],
    'min' => [
        'numeric' => 'O campo :attribute deve ser no mínimo :min.',
        'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
    ],
    'numeric' => 'O campo :attribute deve ser um número.',
    'integer' => 'O campo :attribute deve ser um número inteiro.',
    'exists' => 'O campo :attribute selecionado é inválido.',
    'date' => 'O campo :attribute deve ser uma data válida.',
    'confirmed' => 'A confirmação do campo :attribute não corresponde.',
    'array' => 'O campo :attribute deve ser um array.',

    'custom' => [
        'documento.numero' => [
            'required' => 'O número do documento é obrigatório.',
            'numeric' => 'O número do documento deve ser um valor numérico.',
        ],
        'documento.matricula' => [
            'string' => 'A matrícula deve ser um texto.',
            'max' => 'A matrícula não pode ter mais de 45 caracteres.',
        ],
        'documento.morada' => [
            'string' => 'A morada deve ser um texto.',
            'max' => 'A morada não pode ter mais de 255 caracteres.',
        ],
        'documento.total' => [
            'numeric' => 'O total deve ser um valor numérico.',
        ],
        'documento.observacao' => [
            'string' => 'A observação deve ser um texto.',
            'max' => 'A observação não pode ter mais de 255 caracteres.',
        ],
        'documento.extra' => [
            'numeric' => 'O valor extra deve ser um número.',
        ],
        'documento.tipo_documento_id' => [
            'required' => 'O tipo de documento é obrigatório.',
            'exists' => 'O tipo de documento selecionado é inválido.',
        ],
        'documento.cliente_id' => [
            'required' => 'O cliente é obrigatório.',
            'exists' => 'O cliente selecionado é inválido.',
        ],
        'documento.previsao' => [
            'required' => 'A data de previsão é obrigatória.',
            'date' => 'A data de previsão deve ser uma data válida.',
        ],
        'documento.taxa_id' => [
            'required' => 'A taxa é obrigatória.',
            'integer' => 'A taxa deve ser um número inteiro.',
            'exists' => 'A taxa selecionada é inválida.',
        ],
        'linhas' => [
            'required' => 'É necessário adicionar pelo menos uma linha.',
            'array' => 'As linhas devem ser enviadas em formato de lista (array).',
        ],
        'linhas.*.tipo_palete_id' => [
            'required' => 'O tipo de palete é obrigatório em cada linha.',
            'exists' => 'O tipo de palete selecionado não é válido.',
        ],
        'linhas.*.quantidade' => [
            'required' => 'A quantidade é obrigatória em cada linha.',
            'integer' => 'A quantidade deve ser um número inteiro.',
            'min' => 'A quantidade deve ser pelo menos 1.',
        ],
        'linhas.*.artigo_id' => [
            'required' => 'O artigo é obrigatório em cada linha.',
            'integer' => 'O artigo deve ser um número inteiro.',
            'exists' => 'O artigo selecionado não é válido.',
        ],

        'current_password' => [
            'required' => 'A senha atual é obrigatória.',
            'current_password' => 'A senha atual está incorreta.',
        ],
        'password' => [
            'required' => 'A nova senha é obrigatória.',
            'confirmed' => 'As senhas não coincidem.',
        ],
    ],
];
