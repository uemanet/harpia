import { redimensionarInputArquivo } from '../../utils/redimensionarImagem';

document.addEventListener('DOMContentLoaded', function () {
    const inputFoto = document.getElementById('col_foto_facial');
    const previewWrapper = document.getElementById('preview-foto-facial-wrapper');
    const previewImagem = document.getElementById('preview-foto-facial');

    if (!inputFoto || !previewWrapper || !previewImagem) {
        return;
    }

    inputFoto.addEventListener('change', async function (event) {
        // Redimensiona para no maximo 1920x1080 antes do envio (limite do controlador facial).
        const arquivo = await redimensionarInputArquivo(event.target);

        if (!arquivo || !arquivo.type.match(/^image\//)) {
            return;
        }

        const leitor = new FileReader();

        leitor.onload = function (loadEvent) {
            previewImagem.src = loadEvent.target.result;
            previewWrapper.style.display = 'block';
        };

        leitor.readAsDataURL(arquivo);
    });
});
