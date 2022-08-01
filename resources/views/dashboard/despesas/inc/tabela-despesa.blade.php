<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th scope="col">Nota</th>
            <th scope="col">Emissão</th>
            <th scope="col">Vencimento</th>
            <th scope="col">Valor</th>
            <th scope="col">V.Aberto</th>
            <th scope="col">Parcela</th>
            <th scope="col">Posição</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($despesasCollection[$r->nota] as $n)
            <tr>
                <td>{{ $n->nota }}</td>
                <td>{{ date('d/m/Y', strtotime($n->emissao)) }}
                </td>
                <td>{{ date('d/m/Y', strtotime($n->vencimento)) }}
                </td>
                <td>{{ $n->valor }}</td>
                <td>{{ $n->valor_aberto }}</td>
                <td>{{ $n->parcela }}</td>
                <td>{{ $n->posicao }}</td>

            </tr>
        @endforeach

    </tbody>
</table>