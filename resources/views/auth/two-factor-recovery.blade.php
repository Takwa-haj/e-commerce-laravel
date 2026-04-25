@extends('layouts.app')

@section('title', 'Codes de Récupération 2FA')

@section('content')
<main class="recovery-codes-page" style="padding-top: 100px; padding-bottom: 100px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card recovery-card shadow border-0">
                    <div class="card-body p-5">
                        <!-- Back Button -->
                        <div class="mb-4">
                            <a href="{{ route('user.account.details') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>

                        <!-- Title -->
                        <div class="text-center mb-5">
                            <div class="recovery-icon mb-3">
                                <i class="fas fa-key"></i>
                            </div>
                            <h2 class="h3 fw-bold">Codes de récupération</h2>
                            <p class="text-muted">
                                Conservez ces codes en lieu sûr pour récupérer l'accès à votre compte
                            </p>
                        </div>

                        <!-- Warning -->
                        <div class="alert alert-warning mb-5">
                            <div class="d-flex">
                                <i class="fas fa-exclamation-triangle fa-2x me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-semibold">Important !</h6>
                                    <p class="mb-0">
                                        Ces codes vous permettent d'accéder à votre compte si vous perdez
                                        l'accès à votre application d'authentification. Chaque code ne peut
                                        être utilisé qu'une seule fois.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Recovery Codes -->
                        <div class="recovery-codes-container mb-5">
                            <div class="row g-3">
                                @foreach($recoveryCodes as $index => $code)
                                    <div class="col-md-6">
                                        <div class="recovery-code">
                                            <div class="code-number">{{ $index + 1 }}</div>
                                            <code class="code-value">{{ $code }}</code>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-grid gap-3">
                            <!-- Download -->
                            <button type="button"
                                    onclick="downloadRecoveryCodes()"
                                    class="btn btn-outline-primary btn-lg py-3">
                                <i class="fas fa-download me-2"></i>Télécharger les codes
                            </button>

                            <!-- Print -->
                            <button type="button"
                                    onclick="printRecoveryCodes()"
                                    class="btn btn-outline-secondary btn-lg py-3">
                                <i class="fas fa-print me-2"></i>Imprimer les codes
                            </button>

                            <!-- Regenerate -->
                            <form method="POST" action="{{ route('two-factor.recovery.generate') }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-warning btn-lg py-3"
                                        onclick="return confirm('Êtes-vous sûr ? Les anciens codes seront invalidés.')">
                                    <i class="fas fa-sync-alt me-2"></i>Générer de nouveaux codes
                                </button>
                            </form>

                            <!-- Done -->
                            <a href="{{ route('user.account.details') }}"
                               class="btn btn-primary btn-lg py-3">
                                <i class="fas fa-check me-2"></i>J'ai sauvegardé mes codes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Hidden content for printing/downloading -->
<div id="printableCodes" class="d-none">
    <div style="padding: 2cm; font-family: Arial, sans-serif;">
        <h1 style="text-align: center; margin-bottom: 2cm;">Codes de récupération 2FA</h1>
        <p style="text-align: center; margin-bottom: 1cm;">
            {{ config('app.name') }} - {{ auth()->user()->email }}
        </p>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1cm;">
            @foreach($recoveryCodes as $index => $code)
                <div style="border: 1px solid #ccc; padding: 1cm; text-align: center; page-break-inside: avoid;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 0.5cm;">Code {{ $index + 1 }}</div>
                    <div style="font-family: monospace; font-size: 24px; font-weight: bold; letter-spacing: 2px;">
                        {{ $code }}
                    </div>
                </div>
            @endforeach
        </div>
        <div style="margin-top: 2cm; padding-top: 1cm; border-top: 2px solid #ccc; font-size: 12px; color: #666;">
            <p><strong>Instructions :</strong></p>
            <ul>
                <li>Conservez ce document en lieu sûr</li>
                <li>Chaque code ne peut être utilisé qu'une seule fois</li>
                <li>Utilisez un code si vous n'avez pas accès à votre application d'authentification</li>
                <li>Générez de nouveaux codes si vous avez utilisé tous les codes ou si vous pensez qu'ils ont été compromis</li>
            </ul>
            <p style="margin-top: 1cm; text-align: right;">
                Généré le : {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
</div>

<style>
    .recovery-codes-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .recovery-card {
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .recovery-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background: linear-gradient(135deg, #6e6e6e, #8b8f70);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 36px;
        box-shadow: 0 8px 16px rgba(106, 110, 81, 0.2);
    }

    .recovery-codes-container {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 2rem;
        border: 2px dashed #dee2e6;
    }

    .recovery-code {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        border: 1px solid #e9ecef;
        transition: transform 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .recovery-code:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .code-number {
        position: absolute;
        top: 0.5rem;
        left: 0.5rem;
        background: #6e6e6e;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .code-value {
        font-family: 'Courier New', monospace;
        font-size: 1.2rem;
        font-weight: bold;
        letter-spacing: 2px;
        color: #333;
        word-break: break-all;
    }

    .btn-primary {
        background-color: #6e6e6e;
        border-color: #6e6e6e;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #5a5e41;
        border-color: #5a5e41;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(106, 110, 81, 0.3);
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #000;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        transform: translateY(-2px);
    }

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeaa7;
        color: #856404;
        border-radius: 10px;
    }

    @media (max-width: 768px) {
        .recovery-codes-page {
            padding-top: 60px;
            padding-bottom: 60px;
        }

        .recovery-card {
            padding: 1.5rem !important;
        }

        .recovery-codes-container {
            padding: 1.5rem;
        }

        .recovery-code {
            margin-bottom: 1rem;
        }

        .code-value {
            font-size: 1rem;
        }

        .btn-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
    }

    @media print {
        .recovery-codes-page {
            background: white !important;
            padding: 0 !important;
        }

        .recovery-card {
            box-shadow: none !important;
            border: none !important;
        }

        .d-print-none {
            display: none !important;
        }
    }
</style>

<script>
    // Download recovery codes as text file
    function downloadRecoveryCodes() {
        const codes = @json($recoveryCodes);
        const userEmail = "{{ auth()->user()->email }}";
        const date = new Date().toLocaleDateString('fr-FR');

        let content = `Codes de récupération 2FA\n`;
        content += `=======================\n\n`;
        content += `Compte : ${userEmail}\n`;
        content += `Généré le : ${date}\n\n`;
        content += `Instructions :\n`;
        content += `- Conservez ce fichier en lieu sûr\n`;
        content += `- Chaque code ne peut être utilisé qu'une seule fois\n`;
        content += `- Utilisez un code si vous perdez l'accès à votre application d'authentification\n`;
        content += `- Générez de nouveaux codes si nécessaire\n\n`;
        content += `Codes :\n`;

        codes.forEach((code, index) => {
            content += `${index + 1}. ${code}\n`;
        });

        const blob = new Blob([content], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');

        a.href = url;
        a.download = `codes-recuperation-2fa-${userEmail}-${date.replace(/\//g, '-')}.txt`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);

        // Show success message
        alert('Les codes de récupération ont été téléchargés.');
    }

    // Print recovery codes
    function printRecoveryCodes() {
        const printContent = document.getElementById('printableCodes').innerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;

        // Reload the page to restore functionality
        window.location.reload();
    }

    // Auto-select code on click
    document.querySelectorAll('.code-value').forEach(code => {
        code.addEventListener('click', function() {
            const range = document.createRange();
            range.selectNodeContents(this);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        });
    });

    // Prevent context menu on codes
    document.querySelectorAll('.code-value').forEach(code => {
        code.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            alert('Pour copier le code, utilisez Ctrl+C ou faites un clic droit puis "Copier".');
        });
    });
</script>
@endsection
