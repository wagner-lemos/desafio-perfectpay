@extends('app')
@section('content')
    <section class="row justify-content-center m-0">
        <div class="col-md-3">
            <div class="row">
                <div class="col-12 py-3">
                    <h2 class="text-center">Gerar boleto</h2>
                </div>
            </div>

            <div class="text-center">
                <a href="{{session('result_payment')}}" target="_blank" class="btn btn-primary w-100">Baixar boleto</a>
            </div>
    </section>
@endsection
