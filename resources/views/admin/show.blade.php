@extends('layouts.partials.app')

@section('content')
<div class="container">

    {{-- 1. TAMPILAN DAFTAR LOKASI (Jika variabel $locations ada) --}}
    @if(isset($locations))
        <div class="card">
            <div class="card-header">Daftar Barcode Lokasi</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Lokasi</th>
                            <th>Kode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $loc)
                        <tr>
                            <td>{{ $loc->name }}</td>
                            <td><code>{{ $loc->loc_code }}</code></td>
                            <td>
                                <a href="{{ route('office-locations.show', $loc->id) }}" class="btn btn-sm btn-primary">Buka QR</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    {{-- 2. TAMPILAN DETAIL QR CODE (Jika variabel $location ada) --}}
    @elseif(isset($location))
        <div class="text-center">
            <div class="card p-5" style="max-width: 500px; margin: 0 auto;">
                <h1>{{ $location->name }}</h1>
                <hr>
                <div class="d-flex justify-content-center my-4">
                    <div id="qrcode"></div>
                </div>
                <h4 class="text-secondary">{{ $location->loc_code }}</h4>

                <div class="mt-4 no-print">
                    <button onclick="window.print()" class="btn btn-success">Cetak Barcode</button>
                    <a href="{{ route('office-locations.index') }}" class="btn btn-link">Kembali ke Daftar</a>
                </div>
            </div>
        </div>

        {{-- Script QR Code JS --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script type="text/javascript">
            new QRCode(document.getElementById("qrcode"), {
                text: "{{ $location->loc_code }}",
                width: 250,
                height: 250
            });
        </script>
    @endif

</div>

<style>
    @media print { .no-print { display: none; } }
</style>
@endsection
