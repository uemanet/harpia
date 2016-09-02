<?php
namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class GrupoRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'crs_id' => 'required',
            'ofc_id' => 'required',
            'grp_trm_id' => 'required',
            'grp_pol_id' => 'required',
            'grp_nome' => 'required|min:3|max:45'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'crs_id.required' => 'O campo Curso é obrigatório',
            'ofc_id.required' => 'O campo Oferta de Curso é obrigatório',
            'grp_trm_id.required' => 'O campo Turma é obrigatório',
            'grp_pol_id.required' => 'O campo Polo é obrigatório',
            'grp_nome.required' => 'O campo Nome é obrigatório',
            'grp_nome.min' => 'O nome deve ter mais de 3 letras',
            'grp_nome.max' => 'O nome deve ter menos de 45 letras'
        ];
    }
}
