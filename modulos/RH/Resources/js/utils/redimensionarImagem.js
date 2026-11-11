export const LARGURA_MAXIMA = 1920;
export const ALTURA_MAXIMA = 1080;

/**
 * Redimensiona (se necessario) uma imagem para caber em 1920x1080, mantendo a proporcao.
 * Retorna sempre um File JPEG (o orientation EXIF e aplicado pelo navegador ao desenhar no canvas).
 * Se a imagem ja estiver dentro do limite e for JPEG, o arquivo original e retornado.
 */
export async function redimensionarImagem(arquivo, largura = LARGURA_MAXIMA, altura = ALTURA_MAXIMA, qualidade = 0.9) {
    const bitmap = await createImageBitmap(arquivo, { imageOrientation: 'from-image' });

    try {
        const escala = Math.min(1, largura / bitmap.width, altura / bitmap.height);

        if (escala === 1 && arquivo.type === 'image/jpeg') {
            return arquivo;
        }

        const canvas = document.createElement('canvas');
        canvas.width = Math.max(1, Math.floor(bitmap.width * escala));
        canvas.height = Math.max(1, Math.floor(bitmap.height * escala));

        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(bitmap, 0, 0, canvas.width, canvas.height);

        const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', qualidade));

        if (!blob) {
            return arquivo;
        }

        const nome = arquivo.name.replace(/\.[^.]+$/, '') + '.jpg';

        return new File([blob], nome, { type: 'image/jpeg', lastModified: Date.now() });
    } finally {
        bitmap.close();
    }
}

/**
 * Substitui o arquivo selecionado em um <input type="file"> pela versao redimensionada.
 * Em caso de falha, mantem o arquivo original (o servidor tambem redimensiona).
 */
export async function redimensionarInputArquivo(input) {
    const arquivo = input.files && input.files[0] ? input.files[0] : null;

    if (!arquivo || !arquivo.type.startsWith('image/')) {
        return arquivo;
    }

    try {
        const redimensionado = await redimensionarImagem(arquivo);

        if (redimensionado !== arquivo) {
            const dt = new DataTransfer();
            dt.items.add(redimensionado);
            input.files = dt.files;
        }

        return input.files[0];
    } catch (e) {
        console.warn('Não foi possível redimensionar a imagem no navegador.', e);
        return arquivo;
    }
}
