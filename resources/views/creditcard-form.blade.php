@extends('app')
@section('content')
    <section class="row justify-content-center m-0">
        <div class="col-md-4">
            <div class="row">
                <div class="col-12 py-3">
                    <h2 class="text-center">Dados do Cartão</h2>
                </div>
            </div>

            <div class="card-body">

                <form action="{{route('credi-card-process')}}?product={{$product}}&billingType=CREDIT_CARD" method="POST">
                    @csrf
                    <div class="col-12 mb-3">
                        <label class="form-label">Nome impresso no cartao <span class="text-danger">*</span></label>
                        <input id="name" name="holdercard[holderName]" type="text" class="form-control" required />
                        @if($errors->has('holdercard.holderName'))
                            @foreach ($errors->get('holdercard.holderName') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        @endif
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">numero do cartao (sem espaços)<span class="text-danger">*</span></label>
                        <input id="holdercard[number]" name="holdercard[number]" type="text" class="form-control" required />
                        @if($errors->has('holdercard.number'))
                            @foreach ($errors->get('holdercard.number') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        @endif
                    </div>

                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label">mes expiracao <span class="text-danger">*</span></label>
                                <input id="holdercard[expiryMonth]" name="holdercard[expiryMonth]" type="text" class="form-control" required />
                            @if($errors->has('holdercard.expiryMonth'))
                                @foreach ($errors->get('holdercard.expiryMonth') as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            @endif
                            </div>
                            <div class="col-4">
                                <label class="form-label">ano expiracao <span class="text-danger">*</span></label>
                                <input id="holdercard[expiryYear]" name="holdercard[expiryYear]" type="text" class="form-control" required />
                            @if($errors->has('holdercard.expiryYear'))
                                @foreach ($errors->get('holdercard.expiryYear') as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            @endif
                            </div>
                            <div class="col-4">
                                <label class="form-label">CVC<span class="text-danger">*</span></label>
                                <input id="holdercard[ccv]" name="holdercard[ccv]" type="text" class="form-control" required />
                            @if($errors->has('holdercard.ccv'))
                                @foreach ($errors->get('holdercard.ccv') as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            @endif
                            </div>
                        </div>
                    </div>

                    <hr/>

                    <div class="col-12 mb-3">
                        <label class="form-label">Nome <span class="text-danger">*</span></label>
                        <input id="name" name="holderinfo[name]" type="text" class="form-control" required />
                        @if($errors->has('holderinfo.name'))
                            @foreach ($errors->get('holderinfo.name') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        @endif
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">email <span class="text-danger">*</span></label>
                        <input id="holderinfo[email]" name="holderinfo[email]" type="text" class="form-control" required />
                        @if($errors->has('holderinfo.email'))
                            @foreach ($errors->get('holderinfo.email') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Documento (CPF ou CNPJ) sem pontos<span class="text-danger">*</span></label>
                        <input id="holderinfo[cpfCnpj]" name="holderinfo[cpfCnpj]" type="text" class="form-control" required />
                        @if($errors->has('holderinfo.cpfCnpj'))
                            @foreach ($errors->get('holderinfo.cpfCnpj') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">CEP<span class="text-danger">*</span></label>
                                <input id="holderinfo[postalCode]" name="holderinfo[postalCode]" type="text" class="form-control" required />
                                @if($errors->has('holderinfo.postalCode'))
                                    @foreach ($errors->get('holderinfo.postalCode') as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-6">
                                <label class="form-label">numero do endereco <span class="text-danger">*</span></label>
                                <input id="holderinfo[addressNumber]" name="holderinfo[addressNumber]" type="text" class="form-control" required />
                                @if($errors->has('holderinfo.addressNumber'))
                                    @foreach ($errors->get('holderinfo.addressNumber') as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">complemento</label>
                        <input id="holderinfo[addressComplement]" name="holderinfo[addressComplement]" type="text" class="form-control" required />
                        @if($errors->has('holderinfo.addressComplement'))
                            @foreach ($errors->get('holderinfo.addressComplement') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        @endif
                    </div>
                    <div class="col-12 mb-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="form-label">Telefone<span class="text-danger">*</span></label>
                                <input id="holderinfo[phone]" name="holderinfo[phone]" placeholder="ex: (00) 90000-0000" type="text" class="form-control" required />
                                @if($errors->has('holderinfo.phone'))
                                    @foreach ($errors->get('holderinfo.phone') as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-6">
                                <label class="form-label">celular</label>
                                <input id="holderinfo[mobilePhone]" name="holderinfo[mobilePhone]" type="text" class="form-control" required />
                                @if($errors->has('holderinfo.mobilePhone'))
                                    @foreach ($errors->get('holderinfo.mobilePhone') as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Pagar</button>
                </form><br/><br/>

            </div>
        </div>
    </section>
@endsection
