<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atma Kitchen</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    {{-- https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css
https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap4.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap4.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- datatable --}}
    {{-- https://cdn.datatables.net/2.0.7/js/dataTables.js
https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap4.js --}}
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap4.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .navbar {
            display: flex;
            justify-content: space-around;
            background-color: #f8f8f8;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .navbar a {
            color: #333;
            text-decoration: none;
            padding: 10px;
            transition: color 0.3s;
        }

        .navbar a:hover {
            color: #ff5722;
        }

        .navbar a.active {
            color: #ff5722;
            border-bottom: 2px solid #ff5722;
        }

        .cart {
            width: 100%;
        }

        .cart-header,
        .cart-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .cart-header {
            background-color: #ff9d2e;
            font-weight: bold;
        }

        .cart-item,
        .cart-price,
        .cart-quantity,
        .cart-total,
        .cart-actions {
            flex: 1;
            text-align: center;
        }

        .cart-item {
            display: flex;
            align-items: center;
        }

        .cart-item img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .cart-quantity form {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-quantity input[type="number"] {
            width: 60px;
            padding: 5px;
            margin-right: 10px;
        }

        .cart-actions form {
            display: inline;
        }

        .cart-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 50px 0px 50px;
            border-top: 1px solid #e0e0e0;
            margin-top: 20px;
        }

        .cart-summary {
            font-size: 1.2em;
            font-weight: bold;
        }

        .checkout-button {
            padding: 10px 20px;
            background-color: #ff9d2e;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 1em;
        }

        .checkout-button:hover {
            background-color: #ff9d2e;
        }

        .transaksi {
            padding-top: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .transaction-date {
            float: right;
            font-size: 14px;
            color: #888;
        }

        .custom-img-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            flex-wrap: wrap;
        }

        .custom-img-item {
            text-align: center;
            margin-left: 10px;
        }

        .custom-img-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .custom-img-item div {
            font-size: 0.9em;
            color: #555;
            margin-top: 5px;
        }

        .extra-images {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #e0e0e0;
            width: 100px;
            height: 100px;
            margin-left: 10px;
            font-size: 1.2em;
            color: #333;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- div saldo --}}
        <div class="container-total">
            {{-- saldo between penarikan tombol --}}
            <div class="d-flex justify-content-between">
                <div>
                    <h3>Saldo Anda</h3>
                    <h1>Rp{{ number_format($total_saldo, 2, ',', '.') }}</h1>
                </div>
                <div>
                    {{-- button group --}}
                    <div class="btn-group">
                        @if ($total_saldo > 0)
                            <a href="{{ route('customer.tarik_saldo') }}" class="btn btn-primary">Tarik Saldo</a>
                        @else
                            <a href="#" class="btn btn-secondary" disabled>Tarik Saldo</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- card riwayat saldo --}}
        <div class="card shadow">
            <div class="card-header">
                <h3>Riwayat Saldo</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="tx">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($riwayat_saldo as $saldo)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $saldo->created_at }}</td>
                                <td>{{ $saldo->jenis }}</td>
                                <td>Rp{{ number_format($saldo->jumlah, 2, ',', '.') }}</td>
                                <td>{{ $saldo->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script>
    $(document).ready(function() {
        $('#tx').DataTable([{
            @if ($riwayat_saldo->count() < 1)
                //gabungkan semua column dan berikan pesan
                "language": {
                    "zeroRecords": "Tidak ada data"
                }
            @endif
        }]);

    });
</script>

</html>
