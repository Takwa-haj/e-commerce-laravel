@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Product Reviews</h3>
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
                    <div class="text-tiny">Reviews</div>
                </li>
            </ul>
        </div>

        <div class="wg-box">
            <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                <div class="wg-filter flex-grow">
                    <form class="form-search" action="{{ route('admin.reviews') }}" method="GET">
                        <fieldset class="name">
                            <input type="text" placeholder="Search reviews..." class="" name="search"
                                   value="{{ request('search') }}" tabindex="2">
                        </fieldset>
                        <div class="button-submit">
                            <button class="" type="submit"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>

                <div class="btn-group">
                    <a href="{{ route('admin.reviews', ['status' => 'all']) }}"
                       class="tf-button {{ request('status') == 'all' || !request('status') ? 'style-1' : 'style-2' }}">
                        All ({{ $totalReviews }})
                    </a>
                    <a href="{{ route('admin.reviews', ['status' => 'pending']) }}"
                       class="tf-button {{ request('status') == 'pending' ? 'style-1' : 'style-2' }}">
                        Pending ({{ $pendingReviews }})
                    </a>
                    <a href="{{ route('admin.reviews', ['status' => 'approved']) }}"
                       class="tf-button {{ request('status') == 'approved' ? 'style-1' : 'style-2' }}">
                        Approved ({{ $approvedReviews }})
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="wg-table table-all-user">
                <div class="table-responsive">
                    @if($reviews->count() > 0)
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                            <tr>
                                <td>{{ $review->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($review->product->image)
                                        <img src="{{ asset('uploads/products/thumbnails/' . $review->product->image) }}"
                                             alt="{{ $review->product->name }}"
                                             style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                                        @endif
                                        <div>
                                            <a href="{{ route('shop.product.details', ['product_slug' => $review->product->slug]) }}"
                                               target="_blank" class="body-title-2">
                                                {{ Str::limit($review->product->name, 30) }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {{ $review->user->name }}
                                    <br>
                                    <small class="text-muted">{{ $review->user->email }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="icon-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"
                                               style="font-size: 14px;"></i>
                                        @endfor
                                        <span class="ms-2">{{ $review->rating }}/5</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="review-comment">
                                        <p class="mb-0">{{ Str::limit($review->comment, 50) }}</p>
                                        @if(strlen($review->comment) > 50)
                                            <a href="javascript:void(0);"
                                               onclick="showFullComment({{ $review->id }})"
                                               class="text-primary text-tiny">Read more</a>
                                            <div id="full-comment-{{ $review->id }}" style="display: none;">
                                                {{ $review->comment }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $review->approved ? 'success' : 'warning' }}">
                                        {{ $review->approved ? 'Approved' : 'Pending' }}
                                    </span>
                                </td>
                                <td>{{ $review->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="list-icon-function">
                                        @if(!$review->approved)
                                            <form action="{{ route('admin.review.approve', $review->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="item text-success"
                                                        title="Approve Review">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <button type="button" class="item text-primary view-review-btn"
                                                data-review-id="{{ $review->id }}"
                                                title="View Details">
                                            <i class="icon-eye"></i>
                                        </button>

                                        <form action="{{ route('admin.review.delete', $review->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="item text-danger delete-review-btn"
                                                    title="Delete Review"
                                                    onclick="return confirm('Are you sure you want to delete this review?')">
                                                <i class="icon-trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="alert alert-info text-center">
                        No reviews found.
                    </div>
                    @endif
                </div>

                <div class="divider"></div>

                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    {{ $reviews->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Detail Modal -->
<div class="modal fade" id="reviewDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reviewDetailContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Show full comment
function showFullComment(reviewId) {
    const fullComment = document.getElementById(`full-comment-${reviewId}`);
    const link = event.target;

    if (fullComment.style.display === 'none') {
        fullComment.style.display = 'block';
        link.textContent = 'Show less';
    } else {
        fullComment.style.display = 'none';
        link.textContent = 'Read more';
    }
}

// View review details in modal
document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-review-btn');

    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const reviewId = this.getAttribute('data-review-id');

            fetch(`/admin/reviews/${reviewId}/details`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const review = data.review;
                        const modalContent = `
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <strong>Product:</strong>
                                        <p>${review.product.name}</p>
                                        <img src="${review.product.image_url}"
                                             alt="${review.product.name}"
                                             style="max-width: 100px; max-height: 100px;">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <strong>Customer:</strong>
                                        <p>${review.user.name}</p>
                                        <p><small>${review.user.email}</small></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <strong>Rating:</strong>
                                        <div class="d-flex">
                                            ${Array.from({length: 5}, (_, i) =>
                                                `<i class="icon-star ${i < review.rating ? 'text-warning' : 'text-muted'}"
                                                    style="font-size: 20px;"></i>`
                                            ).join('')}
                                            <span class="ms-2">${review.rating}/5</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <strong>Full Comment:</strong>
                                        <p class="p-3 bg-light rounded">${review.comment}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>Submitted:</strong>
                                        <p>${review.created_at_formatted}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <strong>Status:</strong>
                                        <span class="badge bg-${review.approved ? 'success' : 'warning'}">
                                            ${review.approved ? 'Approved' : 'Pending'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;

                        document.getElementById('reviewDetailContent').innerHTML = modalContent;
                        const modal = new bootstrap.Modal(document.getElementById('reviewDetailModal'));
                        modal.show();
                    }
                })
                .catch(error => {
                    console.error('Error loading review details:', error);
                });
        });
    });

    // Delete review confirmation
    const deleteButtons = document.querySelectorAll('.delete-review-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush
