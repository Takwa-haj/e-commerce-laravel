@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Edit Shop Slide</h3>
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
                    <a href="{{ route('admin.shop-slides') }}">
                        <div class="text-tiny">Shop Slides</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Edit Slide</div>
                </li>
            </ul>
        </div>

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="wg-box">
            <form class="form-new-product form-style-1" action="{{ route('admin.shop-slide.update') }}" method="POST" enctype="multipart/form-data" id="slideForm">
                @csrf
                @method('PUT') {{-- CRITICAL: This must be PUT for update --}}

                <input type="hidden" name="id" value="{{ $slide->id }}">

                @if($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <fieldset class="name mb-3">
                            <div class="body-title">Title <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="text" placeholder="Women's Accessories" name="title"
                                value="{{ old('title', $slide->title) }}" required>
                            @error('title')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Subtitle</div>
                            <input class="flex-grow" type="text" placeholder="Golden Collection" name="subtitle"
                                value="{{ old('subtitle', $slide->subtitle) }}">
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Tagline</div>
                            <input class="flex-grow" type="text" placeholder="Limited Time Offer" name="tagline"
                                value="{{ old('tagline', $slide->tagline) }}">
                        </fieldset>
                    </div>

                    <div class="col-md-6">
                        <fieldset class="name mb-3">
                            <div class="body-title">Button Text</div>
                            <input class="flex-grow" type="text" placeholder="Shop Now" name="button_text"
                                value="{{ old('button_text', $slide->button_text) }}">
                        </fieldset>

                        <fieldset class="name mb-3">
                            <div class="body-title">Button Link <span class="tf-color-1">*</span></div>
                            <input class="flex-grow" type="url" placeholder="https://example.com/shop"
                                name="link" value="{{ old('link', $slide->link) }}" required>
                            @error('link')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="category mb-3">
                            <div class="body-title">Text Position</div>
                            <div class="select flex-grow">
                                <select name="text_position">
                                    <option value="left" {{ (old('text_position', $slide->text_position) == 'left') ? 'selected' : '' }}>Left</option>
                                    <option value="center" {{ (old('text_position', $slide->text_position) == 'center') ? 'selected' : '' }}>Center</option>
                                    <option value="right" {{ (old('text_position', $slide->text_position) == 'right') ? 'selected' : '' }}>Right</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <fieldset class="name mb-3">
                            <div class="body-title">Background Color</div>
                            <input class="flex-grow" type="color" name="background_color"
                                value="{{ old('background_color', $slide->background_color ?? '#f5e6e0') }}">
                        </fieldset>
                    </div>

                    <div class="col-md-4">
                        <fieldset class="category mb-3">
                            <div class="body-title">Status <span class="tf-color-1">*</span></div>
                            <div class="select flex-grow">
                                <select name="status" required>
                                    <option value="1" {{ (old('status', $slide->status) == '1') ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ (old('status', $slide->status) == '0') ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            @error('status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </fieldset>
                    </div>

                    <div class="col-md-4">
                        <fieldset class="name mb-3">
                            <div class="body-title">Order</div>
                            <input class="flex-grow" type="number" placeholder="0" name="order"
                                value="{{ old('order', $slide->order) }}">
                        </fieldset>
                    </div>
                </div>

                <fieldset class="mb-4">
                    <div class="body-title">Slide Image</div>
                    <div class="upload-image flex-grow">
                        @if($slide->image)
                            <div class="item" id="currentImage">
                                <img src="{{ asset('uploads/slides/' . $slide->image) }}"
                                     class="effect8" alt="Current slide" style="max-height: 200px;">
                                <div class="mt-2 text-tiny">
                                    Current image: {{ $slide->image }}
                                </div>
                            </div>
                        @endif

                        <div class="item" id="imgpreview" style="display:none">
                            <img src="" class="effect8" alt="Preview" style="max-height: 200px;">
                            <div class="mt-2 text-tiny">New image preview</div>
                        </div>

                        <div class="item up-load mt-3">
                            <div class="body-title mb-2">Upload New Image (Optional - Leave empty to keep current)</div>
                            <label class="uploadfile" for="slideImage">
                                <span class="icon">
                                    <i class="icon-upload-cloud"></i>
                                </span>
                                <span class="body-text">Drop new image here or <span
                                        class="tf-color">click to browse</span></span>
                                <span class="text-tiny">Recommended: 1260x450 pixels</span>
                                <input type="file" id="slideImage" name="image" accept="image/*">
                            </label>
                        </div>
                    </div>
                    @error('image')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </fieldset>

                <div class="bot mt-4">
                    <a href="{{ route('admin.shop-slides') }}" class="tf-button w208"
                       style="background: #6c757d; margin-right: 10px;">
                        Cancel
                    </a>
                    <button class="tf-button w208" type="submit" id="submitBtn">Update Slide</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Shop slide edit page loaded for slide ID: {{ $slide->id }}');

    // Image preview functionality
    $('#slideImage').on('change', function(e) {
        console.log('New file selected for update');
        const file = this.files[0];
        if (file) {
            console.log('New file:', file.name);

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imgpreview img').attr('src', e.target.result);
                $('#imgpreview').show();
                // Hide current image preview
                $('#currentImage').hide();
            }
            reader.readAsDataURL(file);
        } else {
            // No new file, show current image
            $('#imgpreview').hide();
            $('#currentImage').show();
        }
    });

    // Form submission debug
    $('#slideForm').on('submit', function(e) {
        console.log('Edit form submitting...');
        console.log('Form method:', $(this).attr('method'));
        console.log('Form data:', $(this).serialize());

        // Show loading
        $('#submitBtn').prop('disabled', true).text('Updating...');
        return true;
    });
});
</script>
@endpush
