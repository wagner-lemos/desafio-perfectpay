@extends('app')
@section('content')
    <section class="row justify-content-center m-0">
        <div class="col-md-12">
            <div class="row">
                <div class="col-12 py-3">
                    <h2 class="text-center">QRCode PIX</h2>
                </div>
            </div>

            <div class="card-body text-center">
                <img width="200" src="data:image/jpeg;base64,{{session('image')}}"/><br>
                <p>Codigo copia e cola</p>
                <p>{{session('copy')}}</p>
            </div>

    </section>
@endsection
