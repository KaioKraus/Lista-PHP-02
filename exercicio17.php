<?php

function limparEspacos(string $texto): string
{
    return preg_replace('/\s+/', ' ', trim($texto));
}

function removerPontuacao(string $texto): string
{
    return preg_replace('/[^\p{L}\p{N}]+/u', '', $texto);
}

function contarCaracteres(string $texto): int
{
    $textoLimpo = limparEspacos($texto);
    return mb_strlen($textoLimpo, 'UTF-8');
}

function extrairPalavras(string $texto): array
{
    $textoLimpo = limparEspacos($texto);

    if ($textoLimpo === '') {
        return [];
    }

    $palavras = preg_split('/\s+/', $textoLimpo, -1, PREG_SPLIT_NO_EMPTY);
    $palavrasNormalizadas = [];

    foreach ($palavras as $palavra) {
        $palavraNormalizada = removerPontuacao($palavra);

        if ($palavraNormalizada !== '') {
            $palavrasNormalizadas[] = $palavraNormalizada;
        }
    }

    return $palavrasNormalizadas;
}

function contarPalavras(array $palavras): int
{
    return count($palavras);
}

function contarFrases(string $texto): int
{
    $textoLimpo = limparEspacos($texto);

    if ($textoLimpo === '') {
        return 0;
    }

    $frases = preg_split('/[.!?]+/', $textoLimpo, -1, PREG_SPLIT_NO_EMPTY);
    return count(array_filter($frases, fn ($frase) => trim($frase) !== ''));
}

function encontrarPalavraMaisLonga(array $palavras): string
{
    if (empty($palavras)) {
        return '';
    }

    $palavraMaisLonga = $palavras[0];

    foreach ($palavras as $palavra) {
        if (mb_strlen($palavra, 'UTF-8') > mb_strlen($palavraMaisLonga, 'UTF-8')) {
            $palavraMaisLonga = $palavra;
        }
    }

    return $palavraMaisLonga;
}

function encontrarPalavraMaisCurta(array $palavras): string
{
    if (empty($palavras)) {
        return '';
    }

    $palavraMaisCurta = $palavras[0];

    foreach ($palavras as $palavra) {
        if (mb_strlen($palavra, 'UTF-8') < mb_strlen($palavraMaisCurta, 'UTF-8')) {
            $palavraMaisCurta = $palavra;
        }
    }

    return $palavraMaisCurta;
}

function contarPalavrasRepetidas(array $palavras): int
{
    if (empty($palavras)) {
        return 0;
    }

    $frequencias = array_count_values(array_map('mb_strtolower', $palavras));
    $totalRepetidas = 0;

    foreach ($frequencias as $quantidade) {
        if ($quantidade > 1) {
            $totalRepetidas += $quantidade - 1;
        }
    }

    return $totalRepetidas;
}

function obterCincoPalavrasMaisFrequentes(array $palavras): array
{
    if (empty($palavras)) {
        return [];
    }

    $frequencias = array_count_values(array_map('mb_strtolower', $palavras));
    arsort($frequencias);

    $lista = [];
    $contador = 0;

    foreach ($frequencias as $palavra => $quantidade) {
        if ($contador >= 5) {
            break;
        }

        $lista[] = [
            'palavra' => $palavra,
            'quantidade' => $quantidade,
        ];

        $contador++;
    }

    return $lista;
}

function formatarTexto(string $texto): string
{
    $textoLimpo = limparEspacos($texto);
    return ucwords(mb_strtolower($textoLimpo, 'UTF-8'));
}

function processarTexto(string $texto): array
{
    $palavras = extrairPalavras($texto);

    return [
        'quantidadeCaracteres' => contarCaracteres($texto),
        'quantidadePalavras' => contarPalavras($palavras),
        'quantidadeFrases' => contarFrases($texto),
        'palavraMaisLonga' => encontrarPalavraMaisLonga($palavras),
        'palavraMaisCurta' => encontrarPalavraMaisCurta($palavras),
        'quantidadePalavrasRepetidas' => contarPalavrasRepetidas($palavras),
        'cincoPalavrasMaisFrequentes' => obterCincoPalavrasMaisFrequentes($palavras),
        'textoSemEspacosDuplicados' => limparEspacos($texto),
        'textoFormatado' => formatarTexto($texto),
    ];
}
