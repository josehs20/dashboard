<table class="table table-striped table-hover">
    <thead>

        <tr>
            <th scope="col">Vendedor</th>
            <th scope="col">Total</th>
            <th scope="col">Custos</th>
            <th scope="col">Devolução</th>
            <th scope="col">Cancelamento</th>
            <th scope="col">Percentual de lucro</th>
        </tr>
    </thead>
    <tbody>
        @if ($venda['vendedores'])

            @foreach ($venda['vendedores'] as $vendedor => $v)
           
                
                <tr>

                   <td>{{ $vendedor == '0-NI' ? 'Não informado' : substr($vendedor, 2) }}</td>
                   <td>{{ reais($v['total']) }}</td>
                   <td>{{ reais($v['custo']) }}</td>
                   <td>{{ reais($v['TotalDiaDevolucao']) }}</td>
                   <td>{{ reais($v['TotalDiaCancelamento']) }}</td>
                   <td>{{ number_format($v['percentual'], 2, '.', ' ').  ' %' }}</td>
                    {{-- <td>{{ $v->data_cancelamento ? 'Cancelado': ($v->tipo == 'D' ? 'Devolução' : $v->tipo) }}</td>
                    <td>{{ 'R$' . reais($v->total) }}</td>  --}}
                 
                </tr>

            
                {{-- @if ($v && $v->itens)
                <tr style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalItensVenda{{ $v->id }}">
                    <td>{{ $v->alltech_id }}</td>
                    <td>{{ $v->data_cancelamento ? 'Cancelado': ($v->tipo == 'D' ? 'Devolução' : $v->tipo) }}</td>
                    <td>{{ 'R$' . reais($v->total) }}</td>
                </tr> --}}

                {{-- Modal itens da venda Muito pesado --}}
                {{-- <div class="modal fade" id="modalItensVenda{{ $v->id }}" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Nº Venda: <b>{{ $v->alltech_id }}</b>
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <style>
                            </style>
                            <div class="modal-body">

                                <ul class="list-group">
                                    @foreach ($v->itens as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $item->produto->estoque && $item->produto->estoque->i_grade_id ? $item->produto->nome . ' ' . $item->produto->estoque->iGrade->nome : $item->produto->nome }}
                                            <span class="badge bg-primary rounded-pill">14</span>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- @endif --}}
            @endforeach
        @endif
    </tbody>
</table>
