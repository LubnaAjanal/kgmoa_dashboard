@extends('layouts.main')


<style>
    .card {
    width: 350px;
    height: 200px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
    border-radius: 8px;
    background-color: #9a4949;
    display: flex;
    flex-direction: column;
    font-family: Arial, sans-serif;
}

.card-header {
    background-color: #f8f9fa;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.card-header img {
    max-width: 60px;
    height: auto;
}

.card-header h2 {
    margin-top: 10px;
    font-size: 18px;
    font-weight: bold;
    text-align: center;
}

.card-body {
    display: flex;
    padding: 15px;
    justify-content: space-between;
    flex-grow: 1;
}

.left-details, .right-details {
    font-size: 14px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.left-details p, .right-details p {
    margin: 5px 0;
}

.qrcode {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
}

.row {
    display: flex;
    width: 100%;
    justify-content: space-between;
}

.col-md-6 {
    width: 48%;
}

</style>

@section('content')
    <div class="card">
        <div class="card-header">
            <img src="logo.png" alt="Logo">
            <h2>KGMOA</h2>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="left-details">
                    <p><strong>Name:</strong> {{ $register->fullname }}</p>
                    <p><strong>Mobile No:</strong> {{ $register->mobile }}</p>
                    <p><strong>KMC ID:</strong> {{ $register->gov_id }}</p>
                </div>

            </div>
            <div class="col-md-6">
                <div class="right-details">
                    <div class="qrcode">
                        {!! $qrCode !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
