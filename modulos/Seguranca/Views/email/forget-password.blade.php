@extends('layouts.email')

@section('content')
    <tbody>
    <tr>
        <td style="font-size: 18px; color: #fff; font-weight: 500; padding: 20px; text-align: center; border-radius: 3px 3px 0 0; background: #265876;">
            Solicitação Redefinição de senha
        </td>
    </tr>
    <tr>
        <td style="padding: 20px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="padding: 0 0 20px;">
                        <p style = "line-height:21px;font-size:20px;margin-top:20px;margin-bottom:0px">
                            Você pode resetar sua senha clicando no link abaixo:
                        </p>
                        <a href="{{ route('auth.reset-password')}}/{{$token}}">Resetar Senha</a><br><br>

                        <b>Obs: Esse é apenas um e-mail informativo. Não responda este e-mail. <br><br> Este link é válido durante 30 minutos</br><br>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
@stop
