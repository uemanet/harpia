<?php

namespace Modulos\Geral\Repositories;

use Modulos\Geral\Models\Anexo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Response;
use Modulos\Core\Repository\BaseRepository;

class AnexoRepository extends BaseRepository
{
    protected $basePath;

    public function __construct(Anexo $anexo)
    {
        parent::__construct($anexo);

        // Flysystem 3 remove getAdapter(); para disco local, usa-se a raiz configurada.
        $defaultDisk = config('filesystems.default');
        $diskRoot = config('filesystems.disks.' . $defaultDisk . '.root', storage_path('app'));
        $this->basePath = rtrim($diskRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
    }

    /**
     * Array com o nome dos diretorios baseados no hash passado
     * @param $hash
     * @return array
     */
    private function hashDirectories($hash)
    {
        return array(substr($hash, 0, 2), substr($hash, 2, 2));
    }

    private function montarCaminhoArquivo(Anexo $anexo)
    {
        list($firstDir, $secondDir) = $this->hashDirectories($anexo->anx_localizacao);

        return $this->basePath . $firstDir . DIRECTORY_SEPARATOR . $secondDir . DIRECTORY_SEPARATOR . $anexo->anx_localizacao;
    }

    private function montarDadosAnexo(UploadedFile $uploadedFile, string $hash)
    {
        return [
            'anx_nome' => $uploadedFile->getClientOriginalName(),
            'anx_mime' => $uploadedFile->getClientMimeType(),
            'anx_extensao' => $uploadedFile->getClientOriginalExtension(),
            'anx_localizacao' => $hash
        ];
    }

    /**
     * Trata uploads guardando o arquivo no servidor e registrando na
     * base de dados
     * @param UploadedFile $uploadedFile
     * @return \Illuminate\Http\RedirectResponse|static
        * @throws \RuntimeException
     * @throws \Exception
     */
    public function salvarAnexo(UploadedFile $uploadedFile)
    {
        $hash = sha1_file($uploadedFile);
        list($firstDir, $secondDir) = $this->hashDirectories($hash);

        $caminhoArquivo = $this->basePath . $firstDir . DIRECTORY_SEPARATOR . $secondDir;

        if (file_exists($caminhoArquivo . DIRECTORY_SEPARATOR . $hash)) {
            return array(
                'type' => 'error_exists',
                'message' => 'Arquivo enviado já existe'
            );
        }

        try {
            $anexo = $this->montarDadosAnexo($uploadedFile, $hash);

            $uploadedFile->move($caminhoArquivo, $hash);
            return $this->create($anexo);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        }
    }

    /**
     * @param $anexoId
     * @return null
     */
    public function recuperarAnexo($anexoId)
    {
        $anexo = $this->find($anexoId);

        if (!$anexo) {
            $anexo = 'error_non_existent';
            return $anexo;
        }

        $caminhoArquivo = $this->montarCaminhoArquivo($anexo);

        $headers = array('Content-Type: ' . $anexo->anx_mime);
        return Response::download($caminhoArquivo, $anexo->anx_nome, $headers);
    }

    public function lerConteudoAnexo($anexoId)
    {
        $anexo = $this->find($anexoId);

        if (!$anexo) {
            return 'error_non_existent';
        }

        $caminhoArquivo = $this->montarCaminhoArquivo($anexo);
        $conteudo = @file_get_contents($caminhoArquivo);

        if ($conteudo === false) {
            throw new \RuntimeException('Nao foi possivel ler o arquivo do anexo informado.');
        }

        return $conteudo;
    }

    /**
     * @param $anexoId
     * @return null
     */
    public function recoveryProfilePictureLocation($anexoId)
    {
        $anexo = $this->find($anexoId);

        if (!$anexo) {
            $anexo = 'error_non_existent';
            return $anexo;
        }

        list($firstDir, $secondDir) = $this->hashDirectories($anexo->anx_localizacao);

        $caminhoArquivo = $this->basePath . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . $anexo->anx_localizacao. ".".$anexo->anx_extensao;
        return $caminhoArquivo;
    }

    /**
     * Atualiza o registro de um anexo
     * @param $anexoId
     * @param UploadedFile $uploadedFile
     * @return \Illuminate\Http\RedirectResponse|string
        * @throws \RuntimeException
     * @throws \Exception
     */
    public function atualizarAnexo($anexoId, UploadedFile $uploadedFile)
    {
        $anexo = $this->find($anexoId);

        if (!$anexo) {
            return array(
                'type' => 'error_non_existent',
                'message' => 'Arquivo não existe!'
            );
        }

        $hash = sha1_file($uploadedFile);
        list($firstDir, $secondDir) = $this->hashDirectories($hash);

        $caminhoArquivo = $this->basePath . $firstDir . DIRECTORY_SEPARATOR . $secondDir;
        $dados = $this->montarDadosAnexo($uploadedFile, $hash);

        if (file_exists($caminhoArquivo . DIRECTORY_SEPARATOR . $hash)) {
            if ($anexo->anx_localizacao === $hash) {
                return $this->update($dados, $anexoId, 'anx_id');
            }

            return array(
                'type' => 'error_exists',
                'message' => 'Arquivo enviado já existe'
            );
        }

        try {
            list($firstOldDir, $secondOldDir) = $this->hashDirectories($anexo->anx_localizacao);
            // Exclui antigo arquivo
            array_map('unlink', glob($this->basePath . $firstOldDir . DIRECTORY_SEPARATOR . $secondOldDir . DIRECTORY_SEPARATOR . $anexo->anx_localizacao));

            $uploadedFile->move($caminhoArquivo, $hash);
            return $this->update($dados, $anexoId, 'anx_id');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        }
    }
    

    /**
     * Deleta um anexo do servidor e seu registro no banco
     * @param $anexoId
     * @return int|string|array
     * @throws \Exception
     */
    public function deletarAnexo($anexoId)
    {
        $anexo = $this->find($anexoId);

        if (!$anexo) {
            return array(
                'type' => 'error_non_existent',
                'message' => 'Arquivo não existe!'
            );
        }

        try {
            list($firstOldDir, $secondOldDir) = $this->hashDirectories($anexo->anx_localizacao);
            // Exclui antigo arquivo
            array_map('unlink', glob($this->basePath . $firstOldDir . DIRECTORY_SEPARATOR . $secondOldDir . DIRECTORY_SEPARATOR . $anexo->anx_localizacao));
            return $this->delete($anexoId);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        }
    }
}
