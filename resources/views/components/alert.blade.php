@props(['type','content'])
@if($type == 'success')
    <div class="text-success mb-3">Registro efetuado com sucesso!</div>
@endif
@if($type == 'error')
    <div class="mb-3">
        <div class="text-danger">Ocorreu o seguinte erro:</div>
        <div class="mt-2 text-danger">
            {{$content}}
        </div>
    </div>

@endif
