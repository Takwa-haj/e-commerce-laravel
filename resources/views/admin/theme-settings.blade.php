{{-- resources/views/admin/theme-settings.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="main-content-wrap">
    <div class="flex items-center flex-wrap justify-between gap20 mb-30">
        <h3>Theme Color Settings</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <li><a href="{{ route('admin.index') }}"><div class="text-tiny">Dashboard</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><a href="{{ route('admin.settings') }}"><div class="text-tiny">Settings</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Theme Colors</div></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-12">
            <!-- Color Palette Selection -->
            <div class="wg-box mb-30">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <h4 class="mb-20">Choose a Color Palette</h4>
                        <p class="text-tiny mb-20">Select a predefined palette or create your own custom color scheme</p>
                    </div>
                </div>

                <div class="row g-4" id="palettes-container">
                    @foreach($palettes as $key => $palette)
                    <div class="col-lg-4 col-md-6">
                        <div class="palette-card {{ $currentPalette === $key ? 'selected' : '' }}"
                             data-palette="{{ $key }}"
                             style="border: 2px solid {{ $currentPalette === $key ? $palette['colors']['primary'] : '#E5E7EB' }}; border-radius: 12px; overflow: hidden; cursor: pointer; transition: all 0.3s ease;">

                            <!-- Palette Preview Strip -->
                            <div style="display: grid; grid-template-columns: repeat(5, 1fr); height: 80px;">
                                <div style="background-color: {{ $palette['colors']['primary'] }};"></div>
                                <div style="background-color: {{ $palette['colors']['secondary'] }};"></div>
                                <div style="background-color: {{ $palette['colors']['bg_main'] }};"></div>
                                <div style="background-color: {{ $palette['colors']['accent'] }};"></div>
                                <div style="background-color: {{ $palette['colors']['text_main'] }};"></div>
                            </div>

                            <div class="p-3" style="background: white;">
                                <h5 class="mb-1">{{ $palette['name'] }}</h5>
                                <p class="text-tiny mb-2">{{ $palette['description'] }}</p>

                                <!-- Color Dots -->
                                <div class="d-flex gap-1 flex-wrap mb-2">
                                    @foreach(array_slice($palette['colors'], 0, 8) as $name => $color)
                                    <div class="color-dot"
                                         style="width: 20px; height: 20px; border-radius: 50%; background-color: {{ $color }}; border: 1px solid #E5E7EB;"
                                         title="{{ $name }}: {{ $color }}"></div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Select Button -->
                            <div class="px-3 pb-3" style="background: white;">
                                <button class="btn btn-sm w-100 select-palette-btn"
                                        data-palette="{{ $key }}"
                                        style="background: {{ $palette['colors']['primary'] }}; color: {{ $palette['colors']['text_main'] }}; border: none;">
                                    {{ $currentPalette === $key ? '✓ Current' : 'Select Palette' }}
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Custom Color Editor -->
            <div class="wg-box mb-30">
                <div class="flex items-center justify-between gap10 flex-wrap mb-20">
                    <h4>Custom Color Editor</h4>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="custom-mode-toggle"
                               {{ $settings->theme_custom_mode ? 'checked' : '' }}>
                        <label class="form-check-label" for="custom-mode-toggle">Enable Custom Colors</label>
                    </div>
                </div>

                <form action="{{ route('settings.theme.update') }}" method="POST" id="theme-form">
                    @csrf
                    <input type="hidden" name="theme_palette" id="selected-palette" value="{{ $currentPalette }}">
                    <input type="hidden" name="theme_custom_mode" id="custom-mode-input" value="{{ $settings->theme_custom_mode ? '1' : '0' }}">

                    <div class="row g-4">
                        <!-- Primary Colors -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Primary Colors</h5>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Primary</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[primary]" class="form-control form-control-color"
                                               value="{{ $customColors['primary'] ?? '#D8C3A5' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['primary'] ?? '#D8C3A5' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Primary Hover</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[primary_hover]" class="form-control form-control-color"
                                               value="{{ $customColors['primary_hover'] ?? '#CBB394' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['primary_hover'] ?? '#CBB394' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Secondary</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[secondary]" class="form-control form-control-color"
                                               value="{{ $customColors['secondary'] ?? '#E8DCCF' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['secondary'] ?? '#E8DCCF' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Accent</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[accent]" class="form-control form-control-color"
                                               value="{{ $customColors['accent'] ?? '#C6A77D' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['accent'] ?? '#C6A77D' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Accent Glow</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[accent_glow]" class="form-control form-control-color"
                                               value="{{ $customColors['accent_glow'] ?? '#E5D3B3' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['accent_glow'] ?? '#E5D3B3' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Sale Badge</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[sale_badge]" class="form-control form-control-color"
                                               value="{{ $customColors['sale_badge'] ?? '#E8B7B1' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['sale_badge'] ?? '#E8B7B1' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Background Colors -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Background Colors</h5>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Main Background</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[bg_main]" class="form-control form-control-color"
                                               value="{{ $customColors['bg_main'] ?? '#F7F3EE' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['bg_main'] ?? '#F7F3EE' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Soft Background</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[bg_soft]" class="form-control form-control-color"
                                               value="{{ $customColors['bg_soft'] ?? '#EFE6DC' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['bg_soft'] ?? '#EFE6DC' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Light Background</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[bg_light]" class="form-control form-control-color"
                                               value="{{ $customColors['bg_light'] ?? '#F2E9E4' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['bg_light'] ?? '#F2E9E4' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Surface</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[surface]" class="form-control form-control-color"
                                               value="{{ $customColors['surface'] ?? '#FFFFFF' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['surface'] ?? '#FFFFFF' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mb-3 mt-4">Text Colors</h5>
                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="form-label">Main Text</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[text_main]" class="form-control form-control-color"
                                               value="{{ $customColors['text_main'] ?? '#3E3A37' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['text_main'] ?? '#3E3A37' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Secondary</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[text_secondary]" class="form-control form-control-color"
                                               value="{{ $customColors['text_secondary'] ?? '#7A6F66' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['text_secondary'] ?? '#7A6F66' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Muted</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[text_muted]" class="form-control form-control-color"
                                               value="{{ $customColors['text_muted'] ?? '#A89F98' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['text_muted'] ?? '#A89F98' }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mb-3 mt-4">Border Colors</h5>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label">Soft Border</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[border_soft]" class="form-control form-control-color"
                                               value="{{ $customColors['border_soft'] ?? '#E6DDD4' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['border_soft'] ?? '#E6DDD4' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label">Light Border</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[border_light]" class="form-control form-control-color"
                                               value="{{ $customColors['border_light'] ?? '#EFE6DC' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['border_light'] ?? '#EFE6DC' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Colors -->
                        <div class="col-12">
                            <h5 class="mb-3">Status Colors</h5>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Success</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[success]" class="form-control form-control-color"
                                               value="{{ $customColors['success'] ?? '#A8C3A0' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['success'] ?? '#A8C3A0' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Error</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[error]" class="form-control form-control-color"
                                               value="{{ $customColors['error'] ?? '#D88C8C' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['error'] ?? '#D88C8C' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Warning</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[warning]" class="form-control form-control-color"
                                               value="{{ $customColors['warning'] ?? '#E5D3B3' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['warning'] ?? '#E5D3B3' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Info</label>
                                    <div class="input-group">
                                        <input type="color" name="custom_colors[info]" class="form-control form-control-color"
                                               value="{{ $customColors['info'] ?? '#D9CFC6' }}">
                                        <input type="text" class="form-control" value="{{ $customColors['info'] ?? '#D9CFC6' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="mt-30">
                        <h5 class="mb-15">Live Preview</h5>
                        <div id="live-preview" class="p-4" style="border-radius: 12px; transition: all 0.3s ease;">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="preview-card p-3" id="preview-card" style="border-radius: 8px;">
                                        <h6 id="preview-heading">Sample Heading</h6>
                                        <p id="preview-text">This is a sample text to preview your color choices.</p>
                                        <button class="btn btn-sm" id="preview-btn">Sample Button</button>
                                        <span class="badge ms-2" id="preview-badge">Sale</span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="preview-nav p-3 mb-3" id="preview-nav" style="border-radius: 8px;">
                                        <a href="#" class="me-3" id="preview-nav-link">Home</a>
                                        <a href="#" class="me-3" id="preview-nav-link2">Shop</a>
                                        <a href="#" class="me-3" id="preview-nav-link3">Blog</a>
                                        <a href="#" id="preview-nav-link4">Contact</a>
                                    </div>
                                    <div class="preview-content p-3" id="preview-content" style="border-radius: 8px;">
                                        <div class="d-flex gap-2">
                                            <div class="preview-success p-2" id="preview-success" style="border-radius: 4px;">Success</div>
                                            <div class="preview-error p-2" id="preview-error" style="border-radius: 4px;">Error</div>
                                            <div class="preview-warning p-2" id="preview-warning" style="border-radius: 4px;">Warning</div>
                                            <div class="preview-info p-2" id="preview-info" style="border-radius: 4px;">Info</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-30">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Save Theme Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.palette-card {
    transition: all 0.3s ease;
}
.palette-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.palette-card.selected {
    box-shadow: 0 0 0 3px rgba(37, 119, 252, 0.3);
}
.form-control-color {
    width: 45px;
    padding: 3px;
}
</style>

<script>
$(document).ready(function() {
    // Update color input text fields when color changes
    $('input[type="color"]').on('input', function() {
        $(this).next('input').val($(this).val());
        updateLivePreview();
    });

    // Palette selection
    $('.select-palette-btn').click(function() {
        var paletteKey = $(this).data('palette');
        $('#selected-palette').val(paletteKey);

        // Update card selection styles
        $('.palette-card').removeClass('selected').css('border-color', '#E5E7EB');
        $(this).closest('.palette-card').addClass('selected');

        // Fetch and apply palette colors
        $.get('{{ route("settings.theme.preview") }}', { palette: paletteKey }, function(response) {
            if (response.success) {
                applyColors(response.colors);
                $('.select-palette-btn').html('Select Palette');
                $('.palette-card.selected .select-palette-btn').html('✓ Current');
            }
        });
    });

    // Custom mode toggle
    $('#custom-mode-toggle').change(function() {
        $('#custom-mode-input').val(this.checked ? '1' : '0');
        if (this.checked) {
            updateLivePreview();
        }
    });

    // Apply colors to inputs
    function applyColors(colors) {
        $.each(colors, function(key, value) {
            var input = $('input[name="custom_colors[' + key + ']"]');
            if (input.length) {
                input.val(value);
                input.next('input').val(value);
            }
        });
        updateLivePreview();
    }

    // Live preview update
    function updateLivePreview() {
        var colors = {};
        $('input[name^="custom_colors"]').each(function() {
            var name = $(this).attr('name').match(/\[([^\]]+)\]/)[1];
            if ($(this).attr('type') === 'color') {
                colors[name] = $(this).val();
            }
        });

        // Apply to preview
        $('#live-preview').css('background-color', colors.bg_main);
        $('#preview-card').css('background-color', colors.surface);
        $('#preview-heading').css('color', colors.text_main);
        $('#preview-text').css('color', colors.text_secondary);
        $('#preview-btn').css({
            'background-color': colors.primary,
            'color': colors.text_main,
            'border-color': colors.primary
        });
        $('#preview-badge').css({
            'background-color': colors.sale_badge,
            'color': colors.text_main
        });
        $('#preview-nav').css('background-color', colors.surface);
        $('#preview-nav-link, #preview-nav-link2, #preview-nav-link3, #preview-nav-link4').css({
            'color': colors.text_main,
            'background-color': colors.bg_soft,
            'padding': '8px 15px',
            'border-radius': '6px'
        });
        $('#preview-content').css('background-color', colors.bg_soft);
        $('#preview-success').css('background-color', colors.success);
        $('#preview-error').css('background-color', colors.error);
        $('#preview-warning').css('background-color', colors.warning);
        $('#preview-info').css('background-color', colors.info);
    }

    // Initial preview
    updateLivePreview();
});
</script>
@endsection
