<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>@yield('title', 'Mini ERP')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container flex-grow-1">
        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="bg-dark text-white mt-5">
        <div class="container">
            <div class="row py-4">
                <div class="col-md-6">
                    <h5 class="mb-3">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Mini ERP
                    </h5>
                    <p class="mb-2">
                        <small>Sistema completo de gestão de produtos e pedidos</small>
                    </p>
                    <p class="mb-0">
                        <small class="text-light">
                            <i class="fas fa-envelope me-1"></i>
                            contato@mini-erp.com
                        </small>
                    </p>
                </div>
                <div class="col-md-3">
                    <h6 class="mb-3">Funcionalidades</h6>
                    <ul class="list-unstyled">
                        <li><small><i class="fas fa-check text-success me-2"></i>Gestão de Produtos</small></li>
                        <li><small><i class="fas fa-check text-success me-2"></i>Carrinho de Compras</small></li>
                        <li><small><i class="fas fa-check text-success me-2"></i>Busca de CEP</small></li>
                        <li><small><i class="fas fa-check text-success me-2"></i>Envio de E-mails</small></li>
                        <li><small><i class="fas fa-check text-success me-2"></i>Histórico de Pedidos</small></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6 class="mb-3">Tecnologias</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary">
                            <i class="fab fa-laravel me-1"></i>Laravel
                        </span>
                        <span class="badge bg-info">
                            <i class="fab fa-bootstrap me-1"></i>Bootstrap 5
                        </span>
                        <span class="badge bg-warning text-dark">
                            <i class="fab fa-js me-1"></i>JavaScript
                        </span>
                        <span class="badge bg-success">
                            <i class="fas fa-database me-1"></i>MySQL
                        </span>
                    </div>
                </div>
            </div>
            <hr class="my-3 border-secondary">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <small class="text-light">
                        © {{ date('Y') }} Mini ERP. Desenvolvido com 
                        <i class="fas fa-heart text-danger"></i> 
                        usando Laravel + Bootstrap
                    </small>
                </div>
                <div class="col-md-4 text-end">
                    <small class="text-light">
                        <i class="fas fa-code me-1"></i>
                        Versão 1.0.0
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
