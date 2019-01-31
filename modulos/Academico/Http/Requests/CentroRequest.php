<?php
namespace Modulos\Academico\Http\Requests;

use Modulos\Core\Http\Request\BaseRequest;

class CentroRequest extends BaseRequest
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
            'cen_prf_diretor' => 'required',
            'cen_nome' => 'required|min:3|max:90',
            'cen_sigla' => 'nullable|min:2'
        ];

        return $rules;
    }
}
