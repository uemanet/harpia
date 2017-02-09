<?php

namespace Modulos\Geral\Repositories;

use League\Flysystem\FileExistsException;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Anexo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Response;

class AnexoRepository extends BaseRepository
{
    protected $basePath;

    public function __construct(Anexo $anexo)
    {
        $this->model = $anexo;
        $this->basePath = storage_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
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

    /**
     * Trata uploads guardando o arquivo no servidor e registrando na
     * base de dados
     * @param UploadedFile $uploadedFile
     * @param int $tipoAnexo
     * @return \Illuminate\Http\RedirectResponse|static
     * @throws FileExistsException
     * @throws \Exception
     */
    public function salvarAnexo(UploadedFile $uploadedFile, $tipoAnexo = 1)
    {
        $hash = sha1_file($uploadedFile);
        list($firstDir, $secondDir) = $this->hashDirectories($hash);

        $caminhoArquivo = $this->basePath . $firstDir . DIRECTORY_SEPARATOR . $secondDir;

        if (file_exists($caminhoArquivo . DIRECTORY_SEPARATOR . $hash)) {
            if (config('app.debug')) {
                throw new FileExistsException($caminhoArquivo . DIRECTORY_SEPARATOR . $hash);
            }

            return array(
                'type' => 'error_exists',
                'message' => 'Arquivo enviado já existe'
            );
        }

        try {
            $anexo = [
                'anx_tax_id' => $tipoAnexo,
                'anx_nome' => $uploadedFile->getClientOriginalName(),
                'anx_mime' => $uploadedFile->getClientMimeType(),
                'anx_extensao' => $uploadedFile->getClientOriginalExtension(),
                'anx_localizacao' => $hash
            ];

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

        list($firstDir, $secondDir) = $this->hashDirectories($anexo->anx_localizacao);

        $caminhoArquivo = $this->basePath . $firstDir . DIRECTORY_SEPARATOR . $secondDir . DIRECTORY_SEPARATOR. $anexo->anx_localizacao;

        $headers = array('Content-Type: ' . $anexo->anx_mime);
        return Response::download($caminhoArquivo, $anexo->anx_nome, $headers);
    }

    /**
     * Atualiza o registro de um anexo
     * @param $anexoId
     * @param UploadedFile $uploadedFile
     * @param $tipoAnexo
     * @return \Illuminate\Http\RedirectResponse|string
     * @throws FileExistsException
     * @throws \Exception
     */
    public function atualizarAnexo($anexoId, UploadedFile $uploadedFile, $tipoAnexo = 1)
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

        if (file_exists($caminhoArquivo . DIRECTORY_SEPARATOR . $hash)) {
            if (config('app.debug')) {
                throw new FileExistsException($caminhoArquivo . DIRECTORY_SEPARATOR . $hash);
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

            // Atualiza registro com o novo arquivo
            $data = [
                'anx_tax_id' => $tipoAnexo,
                'anx_nome' => $uploadedFile->getClientOriginalName(),
                'anx_mime' => $uploadedFile->getClientMimeType(),
                'anx_extensao' => $uploadedFile->getClientOriginalExtension(),
                'anx_localizacao' => $hash
            ];

            $uploadedFile->move($caminhoArquivo, $hash);
            return $this->update($data, $anexoId, 'anx_id');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        }
    }

    /**
     * Deleta um anexo do servidor e seu registro no banco
     * @param $anexoId
     * @return int|string
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
