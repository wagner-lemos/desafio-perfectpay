<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-12 py-3">
                <h2 class="text-center">Produtos</h2>
            </div>
            <div class="col-12">
                <x-alert :type="session('result')" :content="session('content')"/>
            </div>
        </div>

        <div class="col-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col-2">Nome</th>
                        <th class="col-4">Descrição</th>
                        <th class="col-1">Valor</th>
                        <th class="col-5">Opções</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{$product['name']}}</td>
                        <td>{{$product['description']}}</td>
                        <td>{{$product['price']}}</td>
                        <td >
                            <div class="row px-2">
                            <a href="{{route('pay')}}?product={{$product['id']}}&billingType=PIX" class="btn btn-primary col-4 border border-white">Pix</a>
                            <a href="{{route('credit-card-form')}}?product={{$product['id']}}" class="btn btn-primary col-4 border border-white">Credito</a>
                            <a href="{{route('pay')}}?product={{$product['id']}}&billingType=BOLETO" class="btn btn-primary col-4 border border-white">Boleto</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
