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
        The {{ config('app.name') }} Team</p
