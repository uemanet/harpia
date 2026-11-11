<?php

namespace Tests;

use Modulos\RH\Services\ImagemFacialService;
use PHPUnit\Framework\TestCase;

class ImagemFacialServiceTest extends TestCase
{
    public function testImagemGrandeEReduzidaParaDentroDoLimite(): void
    {
        $resultado = (new ImagemFacialService())->redimensionarBinario($this->gerarImagem(4000, 3000, 'jpeg'));

        [$largura, $altura] = getimagesizefromstring($resultado);

        $this->assertLessThanOrEqual(1920, $largura);
        $this->assertLessThanOrEqual(1080, $altura);
        $this->assertSame(1080, $altura);
        $this->assertSame(1440, $largura);
    }

    public function testImagemRetratoGrandeRespeitaAlturaMaxima(): void
    {
        $resultado = (new ImagemFacialService())->redimensionarBinario($this->gerarImagem(3024, 4032, 'jpeg'));

        [$largura, $altura] = getimagesizefromstring($resultado);

        $this->assertLessThanOrEqual(1920, $largura);
        $this->assertLessThanOrEqual(1080, $altura);
    }

    public function testJpegDentroDoLimiteNaoEAlterado(): void
    {
        $original = $this->gerarImagem(800, 600, 'jpeg');

        $this->assertSame($original, (new ImagemFacialService())->redimensionarBinario($original));
    }

    public function testPngEConvertidoParaJpeg(): void
    {
        $resultado = (new ImagemFacialService())->redimensionarBinario($this->gerarImagem(800, 600, 'png'));

        $this->assertSame('image/jpeg', getimagesizefromstring($resultado)['mime']);
    }

    public function testConteudoInvalidoLancaExcecao(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new ImagemFacialService())->redimensionarBinario('nao-e-imagem');
    }

    private function gerarImagem(int $largura, int $altura, string $tipo): string
    {
        $img = imagecreatetruecolor($largura, $altura);
        imagefill($img, 0, 0, imagecolorallocate($img, 120, 80, 40));

        ob_start();
        $tipo === 'png' ? imagepng($img) : imagejpeg($img);

        return (string) ob_get_clean();
    }
}
