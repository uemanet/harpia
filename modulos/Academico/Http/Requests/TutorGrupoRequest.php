<?php

namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class TutorGrupoRequest extends BaseRequest
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
            'ttg_tut_id' => 'required',
            'ttg_grp_id' => 'required',
            'ttg_tipo_tutoria' => 'required',
            'ttg_data_inicio'=>'required|date_format:"d/m/Y"',
            'ttg_data_fim' => 'date_format:"d/m/Y"'
        ];


        return $rules;
    }
}
