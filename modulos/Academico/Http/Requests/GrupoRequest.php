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
            'grp_trm_id' => 'required',
            'grp_pol_id' => 'required',
            'grp_nome' => 'required|min:3|max:45'
        ];

        return $rules;
    }
}
