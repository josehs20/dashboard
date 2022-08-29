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
                <input type="password" {{ $usuario ? '' : 'required' }} name="password" autocomplete="off"
                    class="form-control mr-1" placeholder="Senha">

                <input type="password" {{ $usuario ? '' : 'required' }} name="password_confirmation" autocomplete="off"
                    class="form-control" placeholder="Confirmarção de senha">

            </div>

            <div class="row">
                <label style="color: red" class="col-md-6">{{ $errors->first('password', ':message') }}</label>
            </div>
            {{-- <div id="senhaNaoConfere" class="row d-flex flex-row-reverse bd-highlight d-none">
                <label style="color: red" class="col-md-6">Senhas não conferem</label>
            </div> --}}
            <label style="color: blue" for="basic-url" class="form-label">Loja padrão no aplicativo</label>
            <div class="input-group mb-3">
                <select required name="lojaPadrao" class="form-control mr-1">
                    @foreach ($lojas as $l)
                        <option {{ $usuario && $usuario->loja_id == $l->id ? 'selected' : '' }}
                            value="{{ $l->id }}">{{ $l->nome }}</option>
                    @endforeach
                </select>
            </div>


            <label style="color: blue" for="basic-url" class="form-label">Tipo de usuário</label>
            <div class="input-group mb-3">
                <select required name="tipo_admin" class="form-control mr-1">

                    <option {{ $usuario && $usuario->perfil == 'vendedor' ? 'selected' : '' }} value="vendedor"><b>
                            <h4>Usuario para venda externa</h4>
                    </option>
                    <option {{ $usuario && $usuario->perfil == 'consulta' ? 'selected' : '' }} value="consulta"><b>
                            <h4> Ususario para consulta</h4>
                    </option>

                </select>
            </div>
            <button type="submit" class="btn btn-outline-primary">{{ $usuario ? 'Atualizar' : 'Cadastrar' }}</button>
        </div>
    </div>
</div>
