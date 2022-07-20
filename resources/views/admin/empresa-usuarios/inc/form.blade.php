<div class="card-body">

    <div class="input-group justify-content-center">

        <div class="col-md-8">
            <div class="input-group mb-3">

                <input required type="text" class="form-control" placeholder="Nome">
            </div>

            <div class="input-group mb-3">
                <input required type="email" class="form-control" placeholder="Email">
            </div>

            <div class="input-group mb-3">
                <input required type="password" class="form-control mr-1" placeholder="Senha" aria-label="Username">

                <input required type="password" class="form-control" placeholder="Confirmarção de senha"
                    aria-label="Server">
            </div>
            <label style="color: blue" for="basic-url" class="form-label">Loja padrão no aplicativo</label>
            <div class="input-group mb-3">
                <select required name="pasta" class="form-control mr-1">

                    <option value="">s</option>
                </select>
            </div>
            <label style="color: blue" for="basic-url" class="form-label">Lojas para ver relatório</label>
            <div class="input-group mb-3">
                <div class="form-control mr-1 overflow-scroll h-50">
                    @foreach ($collection as $item)
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Default switch checkbox
                                input</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn btn-outline-primary">Cadastrar</button>

        </div>
    </div>


</div>
