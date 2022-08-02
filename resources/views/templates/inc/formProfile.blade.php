<div class="card-body">

    <div class="input-group justify-content-center">

        <div class="col-md-8">
            <div class="input-group mb-3">

                <input required type="text" value="{{ $usuario ? $usuario->name : '' }}" name="name"
                    class="form-control" placeholder="Nome">
            </div>

            <div class="input-group mb-3">
                <input required type="email" value="{{ $usuario ? $usuario->email : '' }}" name="email"
                    class="form-control" placeholder="Email">
            </div>

            <div class="row">
                <label style="color: red" class="col-md-6">{{ $errors->first('email', ':message') }}</label>
            </div>
            <div class="input-group mb-3">
                <input type="password"  {{$usuario ? '': 'required'}} name="password" autocomplete="off" class="form-control mr-1"
                    placeholder="Senha">

                <input type="password" {{$usuario ? '': 'required'}} name="password_confirmation" autocomplete="off" class="form-control"
                    placeholder="Confirmarção de senha">

            </div>
    
            <div class="row">
                <label style="color: red" class="col-md-6">{{ $errors->first('password', ':message') }}</label>
            </div>
            {{-- <div id="senhaNaoConfere" class="row d-flex flex-row-reverse bd-highlight d-none">
                <label style="color: red" class="col-md-6">Senhas não conferem</label>
            </div> --}}
      


     
            <button type="submit" class="btn btn-outline-primary">{{$usuario ? 'Atualizar' : 'Cadastrar'}}</button>
        </div>
    </div>
</div>

