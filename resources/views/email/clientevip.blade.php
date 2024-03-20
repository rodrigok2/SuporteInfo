@component('mail::message')
# Alerta de O.S. aberta para o cliente *{{ $razaoSocial }}*

Cliente: **{{ $razaoSocial }}**

CNPJ: **{{ $cnpj }}**

Contrato: **{{ $contrato }}**

para visualizar os detalhes, clique abaixo:
@component('mail::panel')
@if($listaOs != null)
@for($i = 0; $i < count($listaOs); $i++)
@component('mail::button', ['url' => route('admin.os.detalhes',["os_id" => $listaOs[$i]['os_id']])])
O.S. {{ $listaOs[$i]['os_id'] }}
@endcomponent
@endfor
@endif
@endcomponent
###### Email enviado de forma automática, favor não responder este e-mail.
@endcomponent
