  <div class="card-body">

      <div class="input-group justify-content-center">


          <label style="color: blue" for="basic-url" class="form-label">Nome Empresa</label>
          <div class="input-group mb-3 d-flex justify-content-center">
              <input required type="text" class="form-control col-md-4" name="nome" value="{{$empresa ? $empresa->nome : ''}}" placeholder="Nome" aria-label="Username"
                  aria-describedby="basic-addon1">
          </div>
          <label style="color: blue" for="basic-url" class="form-label">Empresas FTP</label>
          <div class="input-group mb-3 justify-content-center">

              <div class="input-group mb-3 col-md-4">
                  <select name="pasta" class="form-select">
                      @if (count($ftp))
                          @foreach ($ftp as $f)
                              <option value="{{ $f }}">{{ $f }}</option>
                          @endforeach
                      @else
                      <option value="">Nenhuma empresa encontrada no ftp</option>
                      @endif


                  </select>
              </div>
          </div>
          <div class="input-group d-flex justify-content-center">
            <div class="form-check form-switch col-md-6 d-flex justify-content-end">
                <input class="form-check-input mr-5" value="2" {{$empresa && $empresa->sincronizar == '2' ? 'checked' : ''}} name="sincronizar" type="checkbox"
                    id="flexSwitchCheckDefault">
                <label class="form-check-label mt-4" for="flexSwitchCheckDefault">{{$empresa && $empresa->sincronizar == '2' ? 'Desativar sincronização' : 'Ativar sincronização'}}</label>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-outline-primary mt-3 mr-5">{{$empresa ? 'Atualizar' : 'Cadastrar'}}</button>
            </div>
        </div>
      </div>


  </div>
