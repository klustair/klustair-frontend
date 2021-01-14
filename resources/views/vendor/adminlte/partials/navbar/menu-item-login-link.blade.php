@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
@endif

<li class="nav-item">
    <a class="nav-link" href="{{ $login_url }}">
        <i class="fa fa-fw fa-sign-in-alt"></i>
        {{ __('adminlte::adminlte.sign_in') }}
    </a>
</li>