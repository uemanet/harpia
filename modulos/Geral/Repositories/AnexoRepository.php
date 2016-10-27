<?php

namespace Modulos\Geral\Repositories;

use League\Flysystem\FileExistsException;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Models\Anexo;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
        $dirs = $this->hashDirectories($hash);

        $caminhoArquivo = $this->basePath . $dirs[0] . DIRECTORY_SEPARATOR . $dirs[1];

        if (file_exists($caminhoArquivo . DIRECTORY_SEPARATOR . $hash)) {
            if (config('app.debug')) {
                throw new FileExistsException($caminhoArquivo . DIRECTORY_SEPARATOR . $hash);
            }
            flash()->error('Arquivo já existe !');
            return redirect()->back();
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
            flash()->error('Ocorreu um problema ao salvar o arquivo!');
        }
    }

    /**
     * TODO Implementar funcionalidade de recuperacao de arquivo
     * @param $anexoId
     * @return null
     */
    public function recuperarAnexo($anexoId)
    {
        return null;
    }

    /**
     * TODO Refatorar o metodo e corrigir os problemas encontrados ( GitLab issue #19)
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
            return "Anexo não existe.";
        }

        $hash = sha1_file($uploadedFile);

        $pDir = substr($hash, 0, 2); // first Directory
        $sDir = substr($hash, 2, 2); // second Directory

        $caminhoArquivo = $this->basePath . $pDir . DIRECTORY_SEPARATOR . $sDir;

        if (file_exists($caminhoArquivo . DIRECTORY_SEPARATOR . $hash)) {
            if (config('app.debug')) {
                throw new FileExistsException($caminhoArquivo . DIRECTORY_SEPARATOR . $hash);
            }
            flash()->error('Arquivo já existe !');
            return redirect()->back();
        }

        try {

            // Exclui antigo arquivo e pasta correspondente
            array_map('unlink', glob($this->basePath . DIRECTORY_SEPARATOR . $anexo->anx_localizacao . DIRECTORY_SEPARATOR . '*'));
            array_map('rmdir', glob($anexo->anx_localizacao . DIRECTORY_SEPARATOR));

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
        } catch (FileException $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Ocorreu um problema ao atualizar o arquivo!');
        } catch (\Exception $e) {

            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Ocorreu um problema ao atualizar o arquivo!');
        }
    }

    /**
     * TODO Refatorar o metodo e corrigir os problemas encontrados ( GitLab issue #19)
     * Deleta um anexo do servidor e seu registro no banco
     * @param $anexoId
     * @return int|string
     * @throws \Exception
     */
    public function deletarAnexo($anexoId)
    {
        $anexo = $this->find($anexoId);

        if (!$anexo) {
            return "Anexo não existe.";
        }

        try {
            // Exclui arquivo e pasta corresṕondente
            array_map('unlink', glob($anexo->anx_localizacao . DIRECTORY_SEPARATOR . '*'));
            array_map('rmdir', glob($anexo->anx_localizacao . DIRECTORY_SEPARATOR));

            return $this->delete($anexoId);
        } catch (FileException $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Ocorreu um problema ao excluir o arquivo!');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Ocorreu um problema ao excluir o arquivo!');
        }
    }
}
