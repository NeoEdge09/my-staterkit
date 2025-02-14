@extends('layout.master')
@section('title', $page_settings['title'])

@section('main-content')
    <div class="container-fluid">
        <div class="col-12 ">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <div>
                    <h4 class="main-title">{{ $page_settings['title'] }}</h4>
                    <p class="text-secondary">{{ $page_settings['subtitle'] }}</p>
                </div>

                <button type="button" class="btn btn-primary btn-sm " data-bs-toggle="modal"
                    data-bs-target="{{ $page_settings['action']['create']['bs-target'] }}">
                    <i class="ti ti-plus "></i> {{ $page_settings['action']['create']['label'] }}
                </button>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="app-scroll table-responsive app-datatable-default">
                        <table id="{{ $table_settings['id'] }}" class="display app-data-table default-data-table "> </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start: Create Modal -->
    @include('Pages.Admin.RouteAccess.Create')
    <!-- End: Create Modal -->

    <!-- Start: Edit Modal -->
    @include('Pages.Admin.RouteAccess.Edit')
    <!-- End: Edit Modal -->


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#{{ $table_settings['id'] }}').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ $table_settings['tableAjax'] }}",
                columns: @json($table_settings['columns'])
            });

            $('body').on('click', '.delete-item', function(event) {
                event.preventDefault();
                const id = $(this).data('id');
                const url = "{{ $page_settings['action']['delete']['delete_route'] }}".replace(':id', id);
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan dapat mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            dataType: "JSON",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(data) {
                                $('#{{ $table_settings['id'] }}').DataTable().ajax
                                    .reload();
                                Swal.fire(
                                    'Terhapus!',
                                    data.message,
                                    'success'
                                );
                            }
                        });

                    }
                })
            });
        });
    </script>
@endpush
