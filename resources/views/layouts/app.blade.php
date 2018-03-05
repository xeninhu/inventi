<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->

    <link rel="stylesheet" type="text/css" href="{{ asset('css/semantic.min.css') }}">
    <style>
        .sidebar-container {
            width: 260px;
            overflow: hidden;
            height: 100%;
            position: fixed;
            margin: 0;
            top: 0;
            left: 0;
        }
        .pusher {
            padding-left: 260px;
        }

        .pusher {
            margin-bottom: 80px;
        }

        .sidebar::-webkit-scrollbar { width: 0 !important }
        .sidebar { -ms-overflow-style: none; }
        .sidebar { overflow: -moz-scrollbars-none; }
        .menu   {margin: 0 !important;}
    </style>

    

</head>

<body>
    <div id="app">
        <!--nav class="navbar navbar-default navbar-static-top"-->

        <div class="ui inverted menu">
            <div class="ui container">
                <div class="title item">
                    <a class="navbar-brand" href="{{ url('/') }}">
                            {{ config('app.name', 'Laravel') }}
                    </a>
                </div>            

                <!-- Right Side Of Navbar -->
                
                <!-- Authentication Links -->
                @if (!Auth::guest())
                    <div class="ui simple dropdown link item right">
                        <span class="text">{{ Auth::user()->name }}</span>
                        <i class="dropdown icon"></i>
                        <div class="menu">
                                <a class="item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                        </div>
                    </div>
                    
                @endif
            </div>
        
        
        </div>
         @if (!Auth::guest())
        <div class="ui bottom attached segment pushable">
            <div class=".sidebar-container">
                <div class="ui visible inverted left vertical sidebar menu">
                    @if (Auth::user()->admin)
                        <div class="item">
                            <div class="header">Administração</div>
                            <div class="menu">
                                <a class="item" href="{{route('indexuser')}}">
                                    <i class="users icon"></i> Colaborador
                                </a>
                                <a class="item" href="{{route('itens.index')}}">
                                    <i class="laptop icon"></i>Item de inventário
                                </a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="header">Relatórios</div>
                            <div class="menu">
                                <a class="item" href="{{route('itens-report.pagegroupeduser')}}">
                                    <i class="chart area icon"></i>Itens agrupados por usuários
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="item">
                        <div class="header">Movimentações</div>
                        <div class="menu">
                            <a class="item" href="{{route('move-requests.create')}}">
                                <i class="shipping icon"></i>Solicitar movimentação de item
                            </a>
                        </div>
                    </div>
                   
                </div>
            </div>
            <div class="pusher">
                <div class="ui container">
                    @yield('content')
                </div>
            </div>
        </div>
        @else
            @yield('content')
        @endif
        
        
    </div>
    @yield('components')
    <!--Form para função link_form_delete-->
    <form id="form-remove" action="" method="post">
        <input type="hidden" name="_method" value="delete" />
        {{ csrf_field() }}
    </form>
    
    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/semantic.min.js') }}"></script>
    <script language="javascript">
        $('.message .close')
            .on('click', function() {
                $(this)
                .closest('.message')
                .transition('fade');
            });
        /**
        *   Função para chamar uma rota enviada através do verbo DELETE.
        */
        function link_form_delete(action) {
            $('#form-remove').attr('action', action);
            if($('.ui.mini.modal').length) { //Se existir um mini modal, use isso
                $('.ui.mini.modal')
                    .modal({
                        onDeny : function() {
                            $('#form-remove').submit();
                        }
                    })
                    .modal('show');
            }
            else { //Senão, use o confirm do javascript
                if(confirm('Deseja realmente remover o registro?')) {
                     $('#form-remove').submit();
                }
            }
            return false;
        }
        
        @yield('scripts')
    </script>

</body>
</html>
