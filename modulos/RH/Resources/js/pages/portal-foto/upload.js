// import * as faceapi from '@vladmandic/face-api';
document.addEventListener('DOMContentLoaded', () => {
    // Carrega os modelos ao iniciar a página
    Promise.all([
        window.faceapi.nets.tinyFaceDetector.loadFromUri('/models')
    ]).then(() => {
        console.log('Modelos de IA carregados.');
    });

    // Seleciona o input e adiciona o escutador de eventos
    const fotoInput = document.getElementById('fotoInput');

    if (fotoInput) {
        fotoInput.addEventListener('change', async function() {
            const preview = document.getElementById('preview');
            const btnSubmit = document.getElementById('btnSubmit');
            const status = document.getElementById('statusValidador');

            if (this.files && this.files[0]) {
                btnSubmit.disabled = true;
                status.style.display = 'block';
                status.className = 'alert alert-info py-2';
                status.innerText = 'A analisar foto... aguarde.';

                const reader = new FileReader();
                reader.onload = async function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'inline-block';

                    // Aguarda a imagem carregar no DOM para a IA analisar
                    preview.onload = async function() {

                        // Usa window.faceapi conforme definido no portal-foto.js
                        // const detections = await window.faceapi.detectAllFaces(preview, new window.faceapi.TinyFaceDetectorOptions());
                        // Configura o detector para ser mais flexível
                        const opcoesDetector = new window.faceapi.TinyFaceDetectorOptions({
                            inputSize: 512,      // Aumenta a resolução de busca na imagem (padrão era 416)
                            scoreThreshold: 0.3  // Diminui o rigor da confiança de 50% para 30%
                        });

                        const detections = await window.faceapi.detectAllFaces(preview, opcoesDetector);

                        if (detections.length === 0) {
                            status.className = 'alert alert-danger py-2';
                            status.innerText = 'Nenhum rosto detetado. A foto não será aceite pela catraca.';
                            btnSubmit.disabled = true;
                        } else if (detections.length > 1) {
                            status.className = 'alert alert-warning py-2';
                            status.innerText = 'Mais de um rosto detetado. A foto deve conter apenas o seu rosto.';
                            btnSubmit.disabled = true;
                        } else {
                            status.className = 'alert alert-success py-2';
                            status.innerText = 'Rosto validado com sucesso!';
                            btnSubmit.disabled = false;
                        }
                    }
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});