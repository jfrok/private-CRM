@extends('layouts.app')

@section('content')
    <h5>
        &nbsp;&nbsp;<i class="bi bi-view-list"></i> &nbsp;&nbsp;Factureer lijst
        <small class="right">&nbsp;&nbsp;&nbsp;</small>
        <a class="btn-floating btn waves-effect waves-light blue right modal-trigger" href="#filterModal">
            <b><i class="material-icons">filter_alt</i></b>
        </a>
    </h5>

    <br>

    <div class="card">
        <div class="card-content">
            <div id="factuurlijst">
                @include('factureerlijst.ajax.factuurtable')
            </div>
        </div>
    </div>

    <div id="filterModal" class="modal">
        <div class="modal-content">
            <div class="col s1 m4">
                Maand
                <select id="maand">
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maart</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Augustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function () {
            $("#maand").val({{ $month }});
            $('#maand').on('change', function () {
                $.ajax({
                    method: 'GET',
                    url: '{{ url('/filter-factureerlijst') }}/' + this.value,
                    success: function (data) {
                        $('#factuurlijst').empty();
                        $('#factuurlijst').append(data);
                    }
                });
            });
        })
    </script>

    <script>
        function reload() {
            $.ajax({
                method: 'GET',
                url: '{{ url('/reload-factureerlijst') }}',
                success: function (data) {
                    $('#factuurlijst').empty();
                    $('#factuurlijst').append(data);
                }
            })
        }
        function checkProject(id, price) {
            Swal.fire({
                title: 'Selecteer uw product',
                icon: 'question',
                input: 'select',
                inputOptions: {
                    '015': 'Programmeerwerk',
                    @foreach($producten['products'] as $product)
                    '{{ $product['ProductCode'] }}': '{{ $product['ProductName'] }}',
                    @endforeach
                },
                showCancelButton: true,
                confirmButtonColor: '#260089',
                cancelButtonColor: '#f44336',
                confirmButtonText: 'Factureer!',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'GET',
                        url: '{{ url('/check-factuur') }}/' + id + '/' + price + '/' + result.value,
                        success: function () {
                            reload();
                        }
                    })
                    Swal.fire({
                        title: 'Gelukt!',
                        icon: 'success',
                    })
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Je kunt dit ook alleen doorstrepen.',
                        text: "Wil je dit doen?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#260089',
                        cancelButtonColor: '#f44336',
                        confirmButtonText: 'Doorstrepen!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                method: 'GET',
                                url: '{{ url('/check-factuur') }}/' + id + '/' + price + '/' + 0,
                                success: function () {
                                    reload();
                                }
                            })
                            Swal.fire({
                                title: 'Gelukt!',
                                icon: 'success',
                            })
                        }
                    })
                }
            })
        }
        function uncheckProject(id) {
            $.ajax({
                method: 'GET',
                url: '{{ url('/uncheck-factuur') }}/' + id,
                success: function () {
                    reload()
                }
            })
        }
    </script>
@endsection
