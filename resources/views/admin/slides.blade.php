
@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Slides</h3>
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
                    <div class="text-tiny">Slides</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            @if (Session::has('status'))
                <div class="alert alert-success mb-4">{{ Session::get('status') }}</div>
            @endif

            <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.slides') }}" method="GET">
                        <fieldset class="name">
                            <input type="text" placeholder="Search slides..." class="" name="search"
                                value="{{ request('search') }}">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
                <a class="tf-button style-1 w208" href="{{ route('admin.slide.add') }}">
                    <i class="icon-plus"></i>Add New Slide
                </a>
            </div>

            <div class="wg-table table-all-user">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Title & Subtitle</th>
                            <th>Tagline</th>
                            <th>Price</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slides as $slide)
                        <tr>
                            <td>{{ $slide->id }}</td>
                            <td class="pname">
                                <div class="image">
                                    <img src="{{ asset('uploads/slides/' . $slide->image) }}"
                                         alt="{{ $slide->title }}"
                                         style="width: 80px; height: 45px; object-fit: cover;">
                                </div>
                            </td>
                            <td>
                                <div class="font-medium">{{ $slide->title }}</div>
                                <div class="text-tiny text-muted">{{ $slide->subtitle }}</div>
                            </td>
                            <td>{{ $slide->tagline }}</td>
                            <td>
                                @if($slide->price)
                                    €{{ number_format($slide->price, 2) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $slide->order }}</td>
                            <td>
                                @if($slide->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="list-icon-function">
                                    <a href="{{ route('admin.slide.edit', ['id' => $slide->id]) }}">
                                        <div class="item edit">
                                            <i class="icon-edit-3"></i>
                                        </div>
                                    </a>
                                    <form id="delete-slide-form-{{ $slide->id }}"
                                          action="{{ route('admin.slide.delete', ['id' => $slide->id]) }}"
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
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                No slides found. <a href="{{ route('admin.slide.add') }}">Add your first slide</a>
                            </td>
                        </tr>
                        @endforelse
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
            var row = this.closest('tr');
            var slideTitle = row.querySelector('td:nth-child(3) .font-medium').textContent.trim();

            Swal.fire({
                title: `Delete "${slideTitle}"?`,
                text: "This slide will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545',
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
