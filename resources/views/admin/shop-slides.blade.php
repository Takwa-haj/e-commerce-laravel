@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Shop Slides</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Shop Slides</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="wg-filter flex-grow">
                    <form class="form-search">
                        <fieldset class="name">
                            <input type="text" placeholder="Search here..." class="" name="name"
                                tabindex="2" value="" aria-required="true">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.shop-slide.add') }}">
                    <i class="icon-plus"></i>Add New Slide
                </a>
            </div>

            <div class="wg-table table-all-user">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Position</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($slides as $slide)
                        <tr>
                            <td>{{ $slide->id }}</td>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{ asset('uploads/shop-slides') }}/{{ $slide->image }}"
                                         alt="{{ $slide->title }}"
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                            </td>
                            <td>{{ $slide->title }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst($slide->text_position) }}
                                </span>
                            </td>
                            <td>{{ $slide->order }}</td>
                            <td>
                                <span class="badge bg-{{ $slide->is_active ? 'success' : 'danger' }}">
                                    {{ $slide->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="list-icon-function">
                                    <a href="{{ route('admin.shop-slide.edit', ['id' => $slide->id]) }}">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>
                                    <form id="delete-slide-form-{{ $slide->id }}"
                                          action="{{ route('admin.shop-slide.delete', ['id' => $slide->id]) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="item text-danger delete-slide-btn"
                                                style="background: none; border: none; padding: 0; cursor: pointer;">
                                            <i class="icon-trash-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divider"></div>
            <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                {{ $slides->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-slide-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            var form = this.closest('form');
            var slideId = form.id.split('-')[3];

            Swal.fire({
                title: 'Delete Slide?',
                text: "This slide will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
