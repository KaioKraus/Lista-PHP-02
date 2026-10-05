<?php
require_once __DIR__ . '/exercicio16.php';
require_once __DIR__ . '/exercicio17.php';
require_once __DIR__ . '/exercicio18.php';

$senha = 'AbC12!@d';
$texto = '  PHP e muito interessante. Estudar PHP ajuda na carreira profissional. PHP e muito interessante!  ';
$agenda = [
    ['nome' => 'Maria Silva', 'especialidade' => 'Cardiologia', 'data' => '2026-09-25', 'horario' => '08:30'],
    ['nome' => 'João Pereira', 'especialidade' => 'Dermatologia', 'data' => '2026-09-25', 'horario' => '09:15'],
    ['nome' => 'Maria Silva', 'especialidade' => 'Cardiologia', 'data' => '2026-09-25', 'horario' => '09:15'],
    ['nome' => 'Ana Costa', 'especialidade' => 'Ortopedia', 'data' => '2026-09-25', 'horario' => '11:00'],
];

$atividadeSelecionada = $_GET['atividade'] ?? null;

function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function rotuloResultado(string $chave): string
{
    $rotulos = [
        'maiusculas' => 'Letras maiúsculas',
        'minusculas' => 'Letras minúsculas',
        'numeros' => 'Números',
        'caracteresEspeciais' => 'Caracteres especiais',
        'tamanho' => 'Tamanho da senha',
        'nivelSeguranca' => 'Nível de segurança',
        'quantidadeCaracteres' => 'Caracteres',
        'quantidadePalavras' => 'Palavras',
        'quantidadeFrases' => 'Frases',
        'palavraMaisLonga' => 'Palavra mais longa',
        'palavraMaisCurta' => 'Palavra mais curta',
        'quantidadePalavrasRepetidas' => 'Palavras repetidas',
        'cincoPalavrasMaisFrequentes' => 'Palavras mais frequentes',
        'textoSemEspacosDuplicados' => 'Texto sem espaços duplicados',
        'textoFormatado' => 'Texto formatado',
        'quantidadeTotalConsultas' => 'Total de consultas',
        'quantidadePacientesDiferentes' => 'Pacientes diferentes',
        'consultasPorEspecialidade' => 'Consultas por especialidade',
        'primeiroAtendimentoDoDia' => 'Primeiro atendimento do dia',
        'ultimoAtendimentoDoDia' => 'Último atendimento do dia',
        'listaOrdenadaPeloHorario' => 'Agenda por horário',
        'resultadoPesquisaPaciente' => 'Consultas encontradas para Maria',
        'horariosDuplicados' => 'Há horários duplicados?',
        'palavra' => 'Palavra',
        'quantidade' => 'Ocorrências',
        'nome' => 'Paciente',
        'especialidade' => 'Especialidade',
        'data' => 'Data',
        'horario' => 'Horário',
    ];

    return $rotulos[$chave] ?? ucfirst(str_replace('_', ' ', $chave));
}

function renderizarValor(mixed $valor): string
{
    if (is_bool($valor)) {
        return '<span class="status">' . ($valor ? 'Sim' : 'Não') . '</span>';
    }

    if ($valor === null) {
        return '<span class="valor-vazio">Não encontrado</span>';
    }

    if (!is_array($valor)) {
        return '<span class="valor">' . escapar((string) $valor) . '</span>';
    }

    if ($valor === []) {
        return '<span class="valor-vazio">Nenhum registro</span>';
    }

    if (array_is_list($valor)) {
        if (is_array($valor[0])) {
            $colunas = array_keys($valor[0]);
            $html = '<div class="tabela-wrapper"><table><thead><tr>';

            foreach ($colunas as $coluna) {
                $html .= '<th>' . escapar(rotuloResultado((string) $coluna)) . '</th>';
            }

            $html .= '</tr></thead><tbody>';

            foreach ($valor as $registro) {
                $html .= '<tr>';
                foreach ($colunas as $coluna) {
                    $html .= '<td>' . renderizarValor($registro[$coluna] ?? null) . '</td>';
                }
                $html .= '</tr>';
            }

            return $html . '</tbody></table></div>';
        }

        $html = '<ul class="lista-valores">';
        foreach ($valor as $item) {
            $html .= '<li>' . renderizarValor($item) . '</li>';
        }

        return $html . '</ul>';
    }

    $html = '<dl class="detalhes">';
    foreach ($valor as $chave => $item) {
        $html .= '<div><dt>' . escapar(rotuloResultado((string) $chave)) . '</dt><dd>' . renderizarValor($item) . '</dd></div>';
    }

    return $html . '</dl>';
}

function mostrarResultado(string $titulo, array $dados): void
{
    echo '<section class="resultado" aria-labelledby="titulo-resultado">';
    echo '<h2 id="titulo-resultado">' . escapar($titulo) . '</h2>';
    echo '<div class="resultado-grid">';

    foreach ($dados as $chave => $valor) {
        echo '<article class="resultado-item">';
        echo '<h3>' . escapar(rotuloResultado((string) $chave)) . '</h3>';
        echo renderizarValor($valor);
        echo '</article>';
    }

    echo '</div></section>';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista PHP 02</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 150px 20px 40px;
        }
        .container {
            width: min(100%, 1500px);
        }
        h1 {
            text-align: center;
            margin-bottom: 24px;
            font-size: 2rem;
        }
        .botoes {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            column-gap: 4.2%;
            row-gap: 63px;
            margin-bottom: 30px;
        }
        .btn {
            display: block;
            background: #ddd;
            color: #333;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.2s ease;
        }
        .btn:hover .numero {
            background: #d3d3d3;
        }
        .numero {
            display: flex;
            height: 73px;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
        }
        .acao {
            display: flex;
            min-height: 40px;
            align-items: center;
            justify-content: center;
            background: #333;
            color: #fff;
            font-size: 16px;
        }
        .btn:focus-visible {
            outline: 3px solid #666;
            outline-offset: 4px;
        }
        @media (max-width: 700px) {
            body {
                padding-top: 48px;
            }
            .botoes {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 24px;
            }
        }
        @media (max-width: 420px) {
            .botoes {
                grid-template-columns: 1fr;
            }
        }
        .resultado {
            margin-top: 46px;
            padding-top: 24px;
            border-top: 1px solid #ccc;
        }
        .resultado h2 {
            margin-top: 0;
            color: #333;
            font-size: 24px;
        }
        .resultado-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 240px), 1fr));
            gap: 16px;
        }
        .resultado-item {
            min-width: 0;
            padding: 18px;
            background: #e5e5e5;
            border-bottom: 4px solid #333;
        }
        .resultado-item h3 {
            margin: 0 0 14px;
            color: #333;
            font-size: 16px;
        }
        .valor {
            color: #222;
            font-size: 20px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }
        .valor-vazio {
            color: #555;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            background: #333;
            color: #fff;
            font-weight: 700;
        }
        .detalhes {
            display: grid;
            gap: 10px;
            margin: 0;
        }
        .detalhes > div {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 8px;
        }
        .detalhes dt {
            color: #555;
        }
        .detalhes dd {
            margin: 0;
            text-align: right;
            font-weight: 700;
        }
        .tabela-wrapper {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th, td {
            padding: 9px 8px;
            border-bottom: 1px solid #c7c7c7;
            white-space: nowrap;
        }
        th {
            background: #333;
            color: #fff;
            font-size: 13px;
        }
        .lista-valores {
            display: grid;
            gap: 8px;
            padding-left: 20px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="botoes">
            <a class="btn" href="index.php?atividade=16" aria-label="Executar exercício 16">
                <span class="numero">16</span>
                <span class="acao">Executar</span>
            </a>
            <a class="btn" href="index.php?atividade=17" aria-label="Executar exercício 17">
                <span class="numero">17</span>
                <span class="acao">Executar</span>
            </a>
            <a class="btn" href="index.php?atividade=18" aria-label="Executar exercício 18">
                <span class="numero">18</span>
                <span class="acao">Executar</span>
            </a>
        </div>

        <?php
        if ($atividadeSelecionada === '16') {
            mostrarResultado('Exercício 16 - Analisador de Senhas', analisarSenha($senha));
        } elseif ($atividadeSelecionada === '17') {
            mostrarResultado('Exercício 17 - Processador de Texto', processarTexto($texto));
        } elseif ($atividadeSelecionada === '18') {
            mostrarResultado('Exercício 18 - Gerenciador de Agenda', organizarAgenda($agenda, 'Maria'));
        }
        ?>
    </div>
</body>
</html>
