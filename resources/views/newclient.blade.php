@extends('app')
@section('content')
    <section class="row justify-content-center m-0">
        <div class="col-md-4">
           
            <div class="row">
                <div class="col-12 py-3">
                    <h2 class="text-center">Teste PerfectPay<br/>Cadastrar Cliente</h2>
                </div>
            </div>

            <div class="card-body">

                <form action="{{route('new_client_request')}}" method="POST">
                    @csrf
                    <div class="col-12 mb-3">
                        <label class="form-label">Nome</label>
                        <input id="name" name="name" type="text" class="form-control" required />
                        @if($errors->has('name'))
                            @foreach ($errors->get('name') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        @endif
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Documento (CPF ou CNPJ) sem pontos:</label>
                        <input id="cpfCnpj" name="cpfCnpj" type="text" class="form-control" required />
                        @if($errors->has('cpfCnpj'))
                            @foreach ($errors->get('cpfCnpj') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Enviar</button>
                </form>

            </div>
        </div>
    </section>
@endsection
