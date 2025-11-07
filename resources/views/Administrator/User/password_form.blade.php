@extends('Administrator.admin')

@section('title_content')
    Alterar Password - {{ $user->name }}
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-red">
                    <h2>
                        <i class="material-icons">lock</i>
                        Alterar Password
                    </h2>
                    <small>A password deve ter no mínimo 8 caracteres com pelo menos: 1 letra maiúscula, 1 letra minúscula, 1 número e 1 símbolo especial</small>
                </div>

                <div class="body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-info">
                                <strong>Utilizador:</strong> {{ $user->name }} ({{ $user->email }})
                            </div>
                    </div>
                </div>

                    {!! Form::open(array('route' => array('User.password.update', $user->id), 'method' => 'POST', 'id' => 'passwordForm')) !!}
                    
                    <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                    
                    <password-strength @validity-changed="updateFormValidity"></password-strength>
                    
                    @if ($errors->has('current_password'))
                        <div class="alert alert-danger">
                            <strong>{{ $errors->first('current_password') }}</strong>
                        </div>
                    @endif
                    
                    @if ($errors->has('new_password'))
                        <div class="alert alert-danger">
                            <strong>{{ $errors->first('new_password') }}</strong>
                        </div>
                    @endif

                    <div class="form-group m-t-20">
                        <button type="submit" class="btn btn-success btn-lg" :disabled="!isFormValid">
                            <i class="material-icons">save</i> Guardar Nova Password
                        </button>
                        <a href="{{ route('User.edit', $user->id) }}" class="btn btn-default btn-lg">
                            <i class="material-icons">cancel</i> Cancelar
                        </a>
                    </div>
                    
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script_bottom')
<!-- Load Vue.js App for password strength component -->
<script src="{{ asset('js/app.js') }}"></script>

<script>
// Handle form submission validation
(function() {
    // Wait for Vue initialization
    var checkVue = setInterval(function() {
        if (window.app && window.app.isFormValid !== undefined) {
            clearInterval(checkVue);
            
            var form = document.getElementById('passwordForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!window.app.isFormValid) {
                        e.preventDefault();
                        alert('Por favor, certifique-se de que a password cumpre todos os requisitos e que as passwords coincidem.');
                    }
                });
            }
        }
    }, 100);
    
    // Timeout after 5 seconds
    setTimeout(function() {
        clearInterval(checkVue);
    }, 5000);
})();
</script>

<style>
.required:after {
    content: " *";
    color: red;
}
</style>
@endsection
