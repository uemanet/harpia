<?php

namespace Modulos\RH\Services;

use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

/**
 * Garante que as fotos faciais respeitem os limites aceitos pelo controlador
 * facial (iDFace: no maximo 1920x1080) antes de serem armazenadas/enviadas.
 */
class ImagemFacialService
{
    public const LARGURA_MAXIMA = 1920;

    public const ALTURA_MAXIMA = 1080;

    public const QUALIDADE_JPEG = 90;

    /**
     * Redimensiona (se necessario) o conteudo binario de uma imagem para caber em
     * LARGURA_MAXIMA x ALTURA_MAXIMA mantendo a proporcao. O resultado e sempre JPEG.
     * Imagens que ja estao no limite e em JPEG sao retornadas sem alteracao.
     */
    public function redimensionarBinario(string $conteudo): string
    {
        $info = @getimagesizefromstring($conteudo);

        if ($info === false) {
            throw new InvalidArgumentException('O arquivo informado não é uma imagem válida.');
        }

        [$largura, $altura] = $info;
        $mime = $info['mime'] ?? '';
        $orientacao = $this->lerOrientacaoExif($conteudo, $mime);

        // Orientacoes 5-8 giram a imagem 90 graus, trocando largura e altura.
        $rotacionaLados = $orientacao >= 5 && $orientacao <= 8;
        $larguraFinalOrigem = $rotacionaLados ? $altura : $largura;
        $alturaFinalOrigem = $rotacionaLados ? $largura : $altura;

        $dentroDoLimite = $larguraFinalOrigem <= self::LARGURA_MAXIMA && $alturaFinalOrigem <= self::ALTURA_MAXIMA;

        if ($dentroDoLimite && $mime === 'image/jpeg' && $orientacao <= 1) {
            return $conteudo;
        }

        $origem = @imagecreatefromstring($conteudo);

        if ($origem === false) {
            throw new InvalidArgumentException('Não foi possível processar a imagem enviada.');
        }

        $origem = $this->aplicarOrientacao($origem, $orientacao);

        $larguraOrigem = imagesx($origem);
        $alturaOrigem = imagesy($origem);

        $escala = min(1, self::LARGURA_MAXIMA / $larguraOrigem, self::ALTURA_MAXIMA / $alturaOrigem);
        $novaLargura = max(1, (int) floor($larguraOrigem * $escala));
        $novaAltura = max(1, (int) floor($alturaOrigem * $escala));

        $destino = imagecreatetruecolor($novaLargura, $novaAltura);
        // Fundo branco para imagens com transparência (PNG), já que JPEG não suporta alpha.
        imagefill($destino, 0, 0, imagecolorallocate($destino, 255, 255, 255));
        imagecopyresampled($destino, $origem, 0, 0, 0, 0, $novaLargura, $novaAltura, $larguraOrigem, $alturaOrigem);

        ob_start();
        imagejpeg($destino, null, self::QUALIDADE_JPEG);
        $resultado = (string) ob_get_clean();

        unset($origem, $destino);

        if ($resultado === '') {
            throw new InvalidArgumentException('Não foi possível redimensionar a imagem enviada.');
        }

        return $resultado;
    }

    /**
     * Redimensiona o arquivo enviado (upload) e devolve um novo UploadedFile pronto
     * para ser persistido, ou o próprio arquivo quando não há necessidade de alteração.
     */
    public function redimensionarUpload(UploadedFile $arquivo): UploadedFile
    {
        $caminho = $arquivo->getRealPath();
        $conteudo = $caminho ? @file_get_contents($caminho) : false;

        if ($conteudo === false) {
            throw new InvalidArgumentException('Não foi possível ler a imagem enviada.');
        }

        $redimensionado = $this->redimensionarBinario($conteudo);

        if ($redimensionado === $conteudo) {
            return $arquivo;
        }

        $temporario = tempnam(sys_get_temp_dir(), 'foto_facial_');
        file_put_contents($temporario, $redimensionado);

        $nome = pathinfo($arquivo->getClientOriginalName(), PATHINFO_FILENAME) . '.jpg';

        return new UploadedFile($temporario, $nome, 'image/jpeg', null, true);
    }

    private function lerOrientacaoExif(string $conteudo, string $mime): int
    {
        if ($mime !== 'image/jpeg' || !function_exists('exif_read_data')) {
            return 1;
        }

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $conteudo);
        rewind($stream);

        $exif = @exif_read_data($stream);
        fclose($stream);

        return (int) ($exif['Orientation'] ?? 1);
    }

    private function aplicarOrientacao(\GdImage $imagem, int $orientacao): \GdImage
    {
        // GD gira no sentido anti-horario para angulos positivos.
        $angulo = match ($orientacao) {
            3, 4 => 180,
            5, 6 => -90,
            7, 8 => 90,
            default => 0,
        };

        if ($angulo !== 0) {
            $rotacionada = imagerotate($imagem, $angulo, 0);

            if ($rotacionada !== false) {
                $imagem = $rotacionada;
            }
        }

        // Espelhamento horizontal apos a rotacao (4 = giro de 180 + espelho horizontal = espelho vertical).
        if (in_array($orientacao, [2, 4, 5, 7], true)) {
            imageflip($imagem, IMG_FLIP_HORIZONTAL);
        }

        return $imagem;
    }
}
