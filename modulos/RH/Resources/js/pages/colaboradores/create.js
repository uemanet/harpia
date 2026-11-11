import { redimensionarInputArquivo } from '../../utils/redimensionarImagem';

document.addEventListener('DOMContentLoaded', function () {
    const inputFoto = document.getElementById('col_foto_facial');

    if (!inputFoto) {
        return;
    }

    inputFoto.addEventListener('change', function () {
        redimensionarInputArquivo(inputFoto);
    });
});
