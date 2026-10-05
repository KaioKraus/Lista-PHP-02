<?php

function converterParaTimestamp(string $data, string $horario): int
{
    return strtotime($data . ' ' . $horario);
}

function contarConsultas(array $consultas): int
{
    return count($consultas);
}

function obterPacientesDiferentes(array $consultas): array
{
    $pacientes = [];

    foreach ($consultas as $consulta) {
        $pacientes[] = strtolower(trim($consulta['nome']));
    }

    return array_values(array_unique($pacientes));
}

function contarConsultasPorEspecialidade(array $consultas): array
{
    $contagem = [];

    foreach ($consultas as $consulta) {
        $especialidade = trim($consulta['especialidade']);
        $contagem[$especialidade] = ($contagem[$especialidade] ?? 0) + 1;
    }

    ksort($contagem);
    return $contagem;
}

function obterPrimeiroAtendimentoDoDia(array $consultas): ?array
{
    if (empty($consultas)) {
        return null;
    }

    $consultasOrdenadas = $consultas;
    usort($consultasOrdenadas, function (array $a, array $b): int {
        return converterParaTimestamp($a['data'], $a['horario']) <=> converterParaTimestamp($b['data'], $b['horario']);
    });

    return $consultasOrdenadas[0];
}

function obterUltimoAtendimentoDoDia(array $consultas): ?array
{
    if (empty($consultas)) {
        return null;
    }

    $consultasOrdenadas = $consultas;
    usort($consultasOrdenadas, function (array $a, array $b): int {
        return converterParaTimestamp($a['data'], $a['horario']) <=> converterParaTimestamp($b['data'], $b['horario']);
    });

    return $consultasOrdenadas[count($consultasOrdenadas) - 1];
}

function ordenarConsultasPorHorario(array $consultas): array
{
    $consultasOrdenadas = $consultas;

    usort($consultasOrdenadas, function (array $a, array $b): int {
        return converterParaTimestamp($a['data'], $a['horario']) <=> converterParaTimestamp($b['data'], $b['horario']);
    });

    return $consultasOrdenadas;
}

function pesquisarPaciente(array $consultas, string $nome): array
{
    $nomePesquisa = strtolower(trim($nome));
    $resultado = [];

    foreach ($consultas as $consulta) {
        if (str_contains(strtolower($consulta['nome']), $nomePesquisa)) {
            $resultado[] = $consulta;
        }
    }

    return $resultado;
}

function verificarHorariosDuplicados(array $consultas): bool
{
    $horariosVistos = [];

    foreach ($consultas as $consulta) {
        $chave = $consulta['data'] . ' ' . $consulta['horario'];

        if (isset($horariosVistos[$chave])) {
            return true;
        }

        $horariosVistos[$chave] = true;
    }

    return false;
}

function organizarAgenda(array $consultas, ?string $pacientePesquisa = null): array
{
    $resultadoPesquisa = [];

    if ($pacientePesquisa !== null && $pacientePesquisa !== '') {
        $resultadoPesquisa = pesquisarPaciente($consultas, $pacientePesquisa);
    }

    return [
        'quantidadeTotalConsultas' => contarConsultas($consultas),
        'quantidadePacientesDiferentes' => count(obterPacientesDiferentes($consultas)),
        'consultasPorEspecialidade' => contarConsultasPorEspecialidade($consultas),
        'primeiroAtendimentoDoDia' => obterPrimeiroAtendimentoDoDia($consultas),
        'ultimoAtendimentoDoDia' => obterUltimoAtendimentoDoDia($consultas),
        'listaOrdenadaPeloHorario' => ordenarConsultasPorHorario($consultas),
        'resultadoPesquisaPaciente' => $resultadoPesquisa,
        'horariosDuplicados' => verificarHorariosDuplicados($consultas),
    ];
}
