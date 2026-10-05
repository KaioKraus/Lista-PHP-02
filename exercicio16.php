<?php

function contarMaiusculas(string $senha): int
{
    preg_match_all('/[A-Z]/', $senha, $resultado);
    return count($resultado[0]);
}

function contarMinusculas(string $senha): int
{
    preg_match_all('/[a-z]/', $senha, $resultado);
    return count($resultado[0]);
}

function contarNumeros(string $senha): int
{
    preg_match_all('/[0-9]/', $senha, $resultado);
    return count($resultado[0]);
}

function contarCaracteresEspeciais(string $senha): int
{
    preg_match_all('/[^A-Za-z0-9]/', $senha, $resultado);
    return count($resultado[0]);
}

function possuiTamanhoMinimo(string $senha): bool
{
    return strlen($senha) >= 8;
}

function quantidadeCategoriasAtendidas(string $senha): int
{
    $categoriasAtendidas = 0;

    if (possuiTamanhoMinimo($senha)) {
        $categoriasAtendidas++;
    }

    if (contarMaiusculas($senha) > 0) {
        $categoriasAtendidas++;
    }

    if (contarMinusculas($senha) > 0) {
        $categoriasAtendidas++;
    }

    if (contarNumeros($senha) > 0) {
        $categoriasAtendidas++;
    }

    if (contarCaracteresEspeciais($senha) > 0) {
        $categoriasAtendidas++;
    }

    return $categoriasAtendidas;
}

function classificarSenha(string $senha): string
{
    if (!possuiTamanhoMinimo($senha)) {
        return 'Fraca';
    }

    $categoriasAtendidas = quantidadeCategoriasAtendidas($senha);

    if ($categoriasAtendidas >= 5) {
        return 'Muito Forte';
    }

    if ($categoriasAtendidas >= 4) {
        return 'Forte';
    }

    if ($categoriasAtendidas >= 2) {
        return 'Média';
    }

    return 'Fraca';
}

function analisarSenha(string $senha): array
{
    return [
        'maiusculas' => contarMaiusculas($senha),
        'minusculas' => contarMinusculas($senha),
        'numeros' => contarNumeros($senha),
        'caracteresEspeciais' => contarCaracteresEspeciais($senha),
        'tamanho' => strlen($senha),
        'nivelSeguranca' => classificarSenha($senha),
    ];
}
