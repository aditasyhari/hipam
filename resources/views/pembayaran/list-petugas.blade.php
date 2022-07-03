@extends('layout.main')

@section('title')
Pembayaran | Hippam Kaligondo
@endsection

@section('content')
<div class="wrapper">
    <div class="page-title-box">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12">
                    <h4 class="page-title">Pembayaran</h4>
                    <p>List Pembayaran</p>
                </div>
            </div>
        </div>
    
    </div>

    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table-1">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th style="width: 10%;">Bukti</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#table-1").dataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'POST',
            url: "pembayaran/list",
        },
        columns: [
            { data: 'nama', name: 'nama' },
            { data: 'tlp', name: 'tlp' },
            { data: 'alamat', name: 'alamat' },
            { data: 'bukti', name: 'bukti' },
            { data: 'status', name: 'status' },
            { data: '', orderable: false },
        ],
        order: [[0, 'desc']],
        columnDefs: [
        {
          targets: 3,
            render: function (data, type, full, meta) {
              var path = "{{ url('/storage/images/bukti') }}";
              var bukti = path + '/' + full['bukti'];

              var output = '<a href="'+ bukti +'" target="_blank"><img src="'+ bukti +'" class="img-fluid" /></a>';

              return output;
            }
        },
        {
          targets: -1,
          orderable: false,
          render: function (data, type, full, meta) {
            var id = full['id'];
            var status = full['status'];

            if(status == 'waiting') {
              return (
                '<div class="btn-group">' +
                  '<a class="btn dropdown-toggle hide-arrow" data-toggle="dropdown">Aksi</a>' +
                  '<div class="dropdown-menu dropdown-menu-right">' +
                  '<a href="javascript:;" class="dropdown-item" onclick="valid('+ id +')">Valid</a>' +
                  '<a href="javascript:;" class="dropdown-item delete-record" onclick="tolak('+ id +')">Tolak</a>' +
                  '</div>' +
                '</div>'
              );
            } else {
              return 'Tidak ada aksi.';
            }
          }
        }
      ],
    });

    function valid(id) {

    }

    function tolak(id) {

    }

</script>
@endsection