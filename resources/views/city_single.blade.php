@include("includes.header")
@include("includes.nav")
<div class="bg-light pb-5">
    <div class="mx-animated-margin px-5 pt-4">
        <h1 class="display-5 fs-2 text-center">Detail obce</h1>
        <hr>
        <div class="row">
            <div class="col-12 col-lg-7 order-1 order-lg-0">
                <ul class="list-unstyled city-data-list">
                    <li class="row mb-2">
                        <div class="col-6 fw-bold">Meno starostu:</div>
                        <div class="col-6">{{ $city->mayor_name }}</div>
                    </li>
                    <li class="row mb-2">
                        <div class="col-6 fw-bold">Adresa obecného úradu:</div>
                        <div class="col-6">{{ $city->ch_address }}</div>
                    </li>
                    <li class="row mb-2">
                        <div class="col-6 fw-bold">Telefón:</div>
                        <div class="col-6">
                            @foreach(explode(";", $city->phone_numbers) as $phone_number)
                                {{$phone_number}}<br>
                            @endforeach
                        </div>
                    </li>
                    <li class="row mb-2">
                        <div class="col-6 fw-bold">Fax:</div>
                        <div class="col-6">
                            @foreach(explode(";", $city->faxes) as $fax)
                                {{$fax}}<br>
                            @endforeach
                        </div>
                    </li>
                    <li class="row mb-2">
                        <div class="col-6 fw-bold">
                            Email:
                        </div>
                        <div class="col-6">
                            @foreach(explode(";", $city->emails) as $email)
                                {{$email}}<br>
                            @endforeach
                        </div>
                    </li>
                    <li class="row mb-2">
                        <div class="col-6 fw-bold">Web:</div>
                        <div class="col-6">
                            @foreach(explode(";", $city->websites) as $website)
                                {{$website}}<br>
                            @endforeach
                        </div>
                    </li>
                    <li class="row mb-2">
                        <div class="col-6 fw-bold">Zemepisné súradnice</div>
                        <div class="col-6">{{ $city->latitude }}, {{ $city->longitude }}</div>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-lg-5 text-center pt-4 order-0 order-lg-1 mb-4 mb-lg-0">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($city->coa_path) }}">
                <h2 class="text-primary fw-bold">{{ $city->name }}</h2>
            </div>
        </div>
    </div>
</div>
@include("includes.footer")
