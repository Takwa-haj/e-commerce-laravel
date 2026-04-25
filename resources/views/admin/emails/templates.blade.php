@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Email Templates</h1>
                <a href="{{ route('admin.emails.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Back to Email Management
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($templates as $key => $name)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $name }}</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-envelope fa-3x text-gray-300 mb-3"></i>
                        <p class="text-muted">{{ $name }} template for email communications.</p>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.emails.templates.preview', $key) }}"
                           class="btn btn-outline-primary"
                           target="_blank">
                            <i class="fas fa-eye"></i> Preview
                        </a>
                        <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#editTemplateModal{{ $key }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Template Modal -->
        <div class="modal fade" id="editTemplateModal{{ $key }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit {{ $name }} Template</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="subject{{ $key }}">Subject</label>
                                <input type="text" class="form-control" id="subject{{ $key }}"
                                       value="{{ ucfirst($key) }} Email Template">
                            </div>
                            <div class="form-group">
                                <label for="content{{ $key }}">Content</label>
                                <textarea class="form-control" id="content{{ $key }}" rows="10">
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $name }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>{{ $name }}</h2>
        <p>Hello {{ '{name}' }},</p>
        <p>This is a {{ strtolower($name) }} email template.</p>
        <p>Best regards,<br>
        The {{ config('app.name') }} Team</p>
    </div>
</body>
</html>
                                </textarea>
                            </div>
                            <div class="form-group">
                                <label>Available Variables</label>
                                <div class="alert alert-info">
                                    <small>
                                        <strong>Available variables:</strong><br>
                                        {{ '{name}' }} - Recipient's name<br>
                                        {{ '{email}' }} - Recipient's email<br>
                                        {{ '{order_id}' }} - Order ID (for order emails)<br>
                                        {{ '{order_date}' }} - Order date (for order emails)<br>
                                        {{ '{order_total}' }} - Order total (for order emails)<br>
                                        {{ '{company_name}' }} - {{ config('app.name') }}
                                    </small>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="saveTemplate('{{ $key }}')">
                            Save Template
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Custom Template Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Create Custom Template</h6>
                </div>
                <div class="card-body">
                    <form id="customTemplateForm">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="template_name">Template Name</label>
                                <input type="text" class="form-control" id="template_name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="template_key">Template Key</label>
                                <input type="text" class="form-control" id="template_key" required>
                                <small class="form-text text-muted">Unique identifier (lowercase, no spaces)</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="custom_subject">Subject</label>
                            <input type="text" class="form-control" id="custom_subject" required>
                        </div>
                        <div class="form-group">
                            <label for="custom_content">Content (HTML)</label>
                            <textarea class="form-control" id="custom_content" rows="15" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Template
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize text editors for template content
        $('textarea[id^="content"]').each(function() {
            $(this).summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });

        $('#custom_content').summernote({
            height: 300,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Handle custom template form submission
        $('#customTemplateForm').submit(function(e) {
            e.preventDefault();

            const templateName = $('#template_name').val();
            const templateKey = $('#template_key').val();

            if (!templateName || !templateKey) {
                alert('Please fill in all fields');
                return;
            }

            // In a real application, you would send this to your server
            alert(`Template "${templateName}" created successfully with key "${templateKey}"`);

            // Reset form
            $('#customTemplateForm')[0].reset();
            $('#custom_content').summernote('reset');
        });
    });

    function saveTemplate(templateKey) {
        const subject = $('#subject' + templateKey).val();
        const content = $('#content' + templateKey).val();

        // In a real application, you would send this to your server
        console.log('Saving template:', templateKey, subject, content);
        alert('Template saved successfully!');

        // Close modal
        $('#editTemplateModal' + templateKey).modal('hide');
    }
</script>

<!-- Include Summernote CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@endsection
