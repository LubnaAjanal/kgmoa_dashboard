@extends('layouts.main')

<style>
    /* Main card styling */
    .card {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
        border-radius: 8px;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4) !important;
        background-color: #ffffff;
        font-family: Arial, sans-serif;
        overflow: hidden;
    }

    .card-header {
        background-color: #4b88a2;
        padding: 20px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-header img {
        border: 2px solid #4b49ac;
        border-radius: 5px;
        max-width: 70px;
        height: auto;
    }

    .card-header h2 {
        color: #4b49ac;
        font-size: 24px;
        font-weight: bold;
        margin: 0;
        text-align: center;
        flex: 1;
    }

    .card-sub-header {
        background-color: #ffd966;
        color: #000;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        font-size: 18px;
    }

    .event-details {
        color: #c70039;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        font-size: 16px;
    }

    .event-venue {
        color: #0000ff;
        display: block;
        margin-top: 5px;
    }

    .event-date {
        display: inline-block;
        padding: 5px 10px;
        margin-top: 5px;
        border: 2px solid #4b49ac;
        border-radius: 5px;
        font-weight: bold;
    }

    .qrcode {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .qrcode img {
        width: 70px !important;
        height: 70px !important;
    }

    .details-section {
        padding: 15px !important;
        font-size: 26px !important;
        color: #4b49ac;
    }

    .details-section p {
        margin: 15px 5px;
        font-weight: bold;
    }
</style>

@section('content')
    <div class="card">
        <!-- Header with logos and title -->
        <div class="card-header">
            <img src="{{ asset('images/card-logo1.jpeg') }}" alt="Logo1">
            <h2>KGMOA</h2>
            <img src="{{ asset('images/card-logo2.jpeg') }}" alt="Logo2">
        </div>

        <!-- Sub-header with event information -->
        <div class="card-sub-header">
            Anubhaava-25
        </div>

        <div class="event-details p-3">
            31st Karnataka State Govt. Doctors Conference
            <span class="event-venue px-3 pt-2">
                Venue: Kandgal Shri Hanumantaraya Rangmandir, Station Road, Vijayapura
            </span>
            <br><span class="event-date">22 & 23 Feb 2025</span>
        </div>

        <!-- QR Code Section -->
        <div class="qrcode">
            {!! $qrCode !!}
        </div>

        <!-- Main details section below QR code -->
        <div class="details-section">
            <p>Delegate Name: {{ $register->fullname }}</p>
            <p>KMC No: {{ $register->gov_id }}</p>
            <p>Serving At: {{ $register->working_place }}</p>
            <p>Mobile No: {{ $register->mobile }}</p>
            <p>E-mail ID: {{ $register->email }}</p>
        </div>
    </div>
@endsection
