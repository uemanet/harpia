document.addEventListener('DOMContentLoaded', function () {
    const inputFoto = document.getElementById('col_foto_facial');
    const previewWrapper = document.getElementById('preview-foto-facial-wrapper');
    const previewImagem = document.getElementById('preview-foto-facial');

    if (!inputFoto || !previewWrapper || !previewImagem) {
        return;
    }

    inputFoto.addEventListener('change', function (event) {
        const arquivo = event.target.files && event.target.files[0] ? event.target.files[0] : null;

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
