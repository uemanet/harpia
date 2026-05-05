import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(function () {
            document.getElementById('btnSubmit').onclick = function (event) {
                event.preventDefault();

                var btn = this;
                btn.disabled = true;

                // Corrigido: buscando pelos IDs corretos do HTML
                var data = {
                    mdc_id: "{{ $disciplina->mdc_id }}",
                    mdc_tipo_disciplina: $('#mdc_tipo_disciplina').val(),
                    mdc_pre_requisitos: $('#mdc_pre_requisitos').val(),
                    _token: "{{ csrf_token() }}",
                    _method: "PUT"
                };

                // Corrigido: removido as 3 chaves do Blade (que era usado no Laravel 4)
                var url = "{{ route('academico.async.modulosdisciplinas.editardisciplina') }}";

                $.harpia.showloading();

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function (response) {
                        $.harpia.hideloading();
                        document.location.href = "{{ route('academico.cursos.matrizescurriculares.modulosmatrizes.gerenciardisciplinas', ['id' => $modulo->mdo_id]) }}";
                    },
                    error: function (err) {
                        $.harpia.hideloading();

                        var message = err.responseJSON ? err.responseJSON.message : "Erro ao atualizar a disciplina.";
                        toastr.error(message, null, {progressBar: true});

                        // Reabilita o botao
                        btn.disabled = false;
                    }
                });
            };
        });