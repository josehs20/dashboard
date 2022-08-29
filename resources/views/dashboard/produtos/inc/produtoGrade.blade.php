{{-- Verifica se é collection pois se tiver contem grade --}}
{{-- @if ($p->estoque) --}}


<tr onclick="noneCollapse('noneCollapse<?php echo $key; ?>')" data-bs-toggle="collapse"
    href="#produtoCollapse{{ $key }}" role="button" aria-expanded="false" aria-controls="collapseExample">
    <td>{{ $p->nome }}</td>
    <td>{{ $p->custo }}</td>
    <td>{{ $p->preco }}</td>

    <td><i class="ri-arrow-up-down-line"></i></td>
</tr>
<tr>
    <td colspan="4" class="collapse" id="produtoCollapse{{ $key }}">
        <div class="card card-body d-flex justify-content-center">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">{{ $p->grades->nome }}</th>
                        <th scope="col">Custo</th>
                        <th scope="col">Venda</th>
                        <th scope="col">Fator</th>
                        <th scope="col">tipo</th>
                        <th scope="col">Saldo</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($p->estoques as $e)
                        <tr>
                            <td>{{$e->iGrade->tam}}</td>
                            <td>{{ $e->produto->custo }}</td>
                            <td>{{ porcentagemFator($p->preco, $e->iGrade) }}
                            </td>
                            <td>{{ $e->iGrade->fator }}</td>
                            <td>{{ $e->iGrade->tipo }}</td>
                            <td>{{ $e->saldo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </td>
</tr>
