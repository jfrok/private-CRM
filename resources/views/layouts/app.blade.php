<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700&display=swap"
          rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
            href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600&display=swap"
            rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="{{ asset('css/selectize.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('packages/jkanban-master/dist/jkanban.css') }}" rel="stylesheet">
    <link href="{{ asset('fullcalendar/main.css') }}" rel="stylesheet">
    <link href="{{ asset('fullcalendar/main.js') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.0.1/chart.min.js"
            integrity="sha512-tQYZBKe34uzoeOjY9jr3MX7R/mo7n25vnqbnrkskGr4D6YOoPYSpyafUAzQVjV6xAozAqUFIEFsCO4z8mnVBXA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    @php($chosenUser = (\Illuminate\Support\Facades\Cookie::get('chosenUser') ? App\Models\User::find(\Illuminate\Support\Facades\Cookie::get('chosenUser')) : App\Models\User::find(auth()->id())))
    @php($users = App\Models\User::all())
</head>
<body>

@auth

    <div class="sideNav nav-dark fixed" onmouseenter="createNavText()" onmouseleave="deleteNavText()">
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/home') }}">&nbsp;&nbsp;<i class="bi bi-house"></i></a><a
                    href="{{ url('/home') }}" class="navText text-decoration-none">OnlineBouwers CRM</a>
        </div>
        <div><p></p></div>
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/klanten') }}">&nbsp;&nbsp;<i class="bi bi-person-check"></i></a><a
                    href="{{ url('/klanten') }}" class="navText text-decoration-none">Klanten</a>
        </div>
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/klanten') }}">&nbsp;&nbsp;<i class="bi bi-key"></i></a><a
                    href="{{ url('/keysoftware') }}" class="navText text-decoration-none">Keysoftware</a>
        </div>
        <div class="menuHolder">
            <a class="text-decoration-none"
               href="https://onlinebouwers.customerr.nl/loginWithToken/Prrz6qKyJRy2l7X5J7PTl63tBB8oLtIvFjTOTQtzV1SQvQrKp0GEVNDrY4mpCWtFpF2Mli">&nbsp;&nbsp;<i
                        class="bi bi-piggy-bank"></i><a
                        href="https://onlinebouwers.customerr.nl/loginWithToken/Prrz6qKyJRy2l7X5J7PTl63tBB8oLtIvFjTOTQtzV1SQvQrKp0GEVNDrY4mpCWtFpF2Mli"
                        class="navText text-decoration-none">OB Auto. Incasso</a></a>
        </div>
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/projecten') }}">&nbsp;&nbsp;<i
                        class="bi bi-list-task"></i></a><a
                    href="{{ url('/projecten') }}" class="navText text-decoration-none">Projecten</a>
        </div>
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/factureerlijst') }}">&nbsp;&nbsp;<i class="bi bi-view-list"></i><a
                        href="{{ url('/factureerlijst') }}" class="navText text-decoration-none">Factureer lijst</a></a>
        </div>
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/kalender') }}">&nbsp;&nbsp;<i
                        class="bi bi-calendar-check"></i><a href="{{ url('/kalender') }}"
                                                            class="navText text-decoration-none">Kalender & To-do's</a></a>
        </div>
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/gebruikers') }}">&nbsp;&nbsp;<i class="bi bi-person"></i><a
                        href="{{ url('/gebruikers') }}" class="navText text-decoration-none">Gebruikers</a></a>
        </div>
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/wiki') }}">&nbsp;&nbsp;<i class="bi bi-info-circle"></i><a
                        href="{{ url('/wiki') }}" class="navText">Wiki</a></a>
        </div>
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/schoonmaak-rooster') }}">&nbsp;&nbsp;<i
                        class="bi bi-card-checklist"></i><a href="{{ url('/schoonmaak-rooster') }}"
                                                            class="navText text-decoration-none">Schoonmaak rooster</a></a>
        </div>
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/wefact') }}">&nbsp;&nbsp;<i
                        class="bi bi-credit-card-2-front"></i><a href="{{ url('/wefact') }}"
                                                                 class="navText text-decoration-none">WeFact</a></a>
        </div>
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/boodschappenlijst') }}">&nbsp;&nbsp;<i
                        class="bi bi-basket"></i><a href="{{ url('/boodschappenlijst') }}"
                                                    class="navText text-decoration-none">Boodschappen
                    @if(substr_count(App\Models\Boodschappen::findOrFail(1)->body, '<li>') == 1)
                        <span data-badge-caption="Item" class="new badge white black-text">
                            {{ substr_count(App\Models\Boodschappen::findOrFail(1)->body, '<li>') }}
                        </span>
                    @elseif(substr_count(App\Models\Boodschappen::findOrFail(1)->body, '<li>') > 1)
                        <span data-badge-caption="Items" class="new badge white black-text">
                            {{ substr_count(App\Models\Boodschappen::findOrFail(1)->body, '<li>') }}
                        </span>
                    @endif
                </a></a>

        </div>
        <div class="menuHolder">
            <a class="text-decoration-none"
               href="https://onlinebouwers.nl/loginId/30928409283409oehewrkjn42u3jrjn3kjb4jh2b35h23orjehr98hwef">&nbsp;&nbsp;<i
                        class="bi bi-link-45deg"></i><a
                        href="https://onlinebouwers.nl/loginId/30928409283409oehewrkjn42u3jrjn3kjb4jh2b35h23orjehr98hwef"
                        class="navText text-decoration-none">OnlineBouwers website</a></a>
        </div>
        <div class="menuHolder" id="logoutButton">
            <a class="text-decoration-none" href="{{ url('/logout') }}">&nbsp;&nbsp;<i
                        class="bi bi-box-arrow-right"></i><a href="{{ url('/logout') }}" class="navText">Uitloggen</a></a>
        </div>
        <div class="menuHolder">
            <a class="text-decoration-none" href="{{ url('/site-projecten') }}">&nbsp;&nbsp;<i class="bi bi-person"></i><a href="{{ url('/site-projecten') }}" class="navText text-decoration-none">Klant cases</a></a>
        </div>
    </div>

    <nav class="white drop-sh">
        <span onclick="loadMobileMenu()" class="material-icons" id="hamburgerMenu">lunch_dining</span>
        <a onclick="openNotificationsModal()"><i class="material-icons right txt-dark" id="alertIcon">circle_notifications</i></a>
        <a href="#searchModal" class="modal-trigger"><i class="material-icons right txt-dark" id="searchIcon">search</i></a>
        @if($chosenUser->id != Auth::id())
            <a onclick="changeChosenUser()" data-target="userSelect"><i class="material-icons right tooltipped txt-dark"
                                                                        data-tooltip="{{ $chosenUser->name }}"
                                                                        data-position="left" id="userIcon">supervised_user_circle</i></a>
        @else
            <a onclick="changeChosenUser()" data-target="userSelect"><i class="material-icons right tooltipped txt-dark"
                                                                        data-tooltip="{{ Auth::user()->name }}"
                                                                        data-position="left" id="userIcon">account_circle</i></a>
        @endif
    </nav>

    <div class="col s6 center-align preloader displayNone">
        <div class="preloader-wrapper active">
            <div class="spinner-layer spinner-red-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>

@endauth
<main>
    <div><p></p></div>
    @yield('content')
</main>
@auth
    <script src="{{ asset('js/enable-push.js') }}" defer></script>
@endauth

<div id="searchModal" class="modal roundedModal modal-fixed-footer">
    <div class="modal-content">
        <div class="row">
            <div class="col s12">
                <h3 class="heading">Project zoeken</h3>
            </div>

            <div class="col s12 mt-20">
                <select name="searchProject" id="searchProject" class="browser-default"
                        onchange="searchProject($(this).val())">
                    <option value="" selected disabled>Zoek een project...</option>
                    @foreach($searchProjects as $sp)
                        <option
                                value="{{ $sp->id }}">{{ ($sp->customer ? $sp->customer->company_name . " | " : "") . $sp->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn-flat waves-effect modal-close white-text">Sluiten</button>
    </div>
</div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="https://cdn.ckeditor.com/4.16.1/basic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('packages/jkanban-master/dist/jkanban.js') }}"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js"></script>
@yield('scripts')
<script>
    $(document).ready(function () {
        M.AutoInit();

        $('#searchProject').select2({
            dropdownParent: $('#searchModal'),
        });
    });

    if (!/Android|webOS|iPhone|iPad|Mac|Macintosh|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        @auth
        $('main').removeClass('mainClassMobile');
        $('main').addClass('mainClassDesktop');

        @endauth

        function createNavText() {
            $('.navText').delay(50).fadeIn(0);
        }

        function deleteNavText() {
            $('.navText').hide();
        }

    } else {
        @auth
        $('main').removeClass('mainClassDesktop');
        $('main').addClass('mainClassMobile');
        @endauth

        $('#hamburgerMenu').show();

        function loadMobileMenu() {
            $('.sideNav').animate({width: 'toggle'}, 200);
        }
    }

    @auth
    function searchProject(id) {
        window.location.href = "/projecten/bekijken/" + id;
    }

    function changeChosenUser() {
        Swal.fire({
            title: 'Van gebruiker veranderen',
            icon: 'info',
            input: 'select',
            inputOptions: {
                @foreach($users as $user)
                '{{ $user->id }}': '{{ $user->name }}',
                @endforeach
            },
            inputPlaceholder: '{{ $chosenUser->name }}',
            showCancelButton: true,
            iconColor: '#FA4D09',
            confirmButtonText: 'Veranderen',
            cancelButtonText: 'Annuleren',
            confirmButtonColor: '#FA4D09',
            cancelButtonColor: '#260089',

            inputValidator: (value) => {
                return new Promise((resolve) => {
                    if (value != '{{ $chosenUser->id }}') {
                        if (value == '') {
                            resolve('Geen andere gebruiker geselecteerd!')
                        } else {
                            $.get('{{ url('/gebruikers/selecteren/') }}/' + value, function () {
                                window.location.reload();
                            });
                        }
                    } else {
                        if (value == null) {
                            resolve('Geen gebruiker geselecteerd!')
                        } else {
                            resolve('Deze gebruiker is al geselecteerd!')
                        }
                    }
                })
            }
        });
    }

    function openNotificationsModal() {
        $.get('{{ url('/notificaties/ophalen') }}', function () {

        });
    }

    @endauth

    @if(\Illuminate\Support\Facades\Session::has('success'))
    Swal.fire({
        icon: "success",
        title: "Gelukt!",
        html: "{{ \Illuminate\Support\Facades\Session::get('success') }}",
        confirmButtonText: "Oké",
        confirmButtonColor: "#FA4D09",
    });
    @endif

    @if(\Illuminate\Support\Facades\Session::has('error'))
    Swal.fire({
        icon: "error",
        title: "Oeps...",
        html: "{{ \Illuminate\Support\Facades\Session::get('error') }}",
        confirmButtonText: "Oké",
        confirmButtonColor: "#FA4D09",
    });

    @endif

    function successMessage(title = "Gelukt!", text) {
        Swal.fire({
            icon: "success",
            title: "Gelukt!",
            html: text,
            confirmButtonText: "Oké",
            confirmButtonColor: "#FA4D09",
        });
    }

    function errorMessage(title = "Oeps..", text) {
        Swal.fire({
            icon: "error",
            title: "Oeps...",
            html: text,
            confirmButtonText: "Oké",
            confirmButtonColor: "#FA4D09",
        });
    }

    function showPreloader() {
        $(".preloader").fadeIn(100);
    }

    function hidePreloader() {
        $(".preloader").fadeOut(100);
    }

</script>

</html>
