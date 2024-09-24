@foreach($documentos as $documento)
<div class="modal fade" id="retiradaModal{{$documento->id}}" tabindex="-1" aria-labelledby="retiradaModalLabel{{$documento->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="retiradaModalLabel{{$documento->id}}">{{ __('retirada.detalhes') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Informação do documento -->
                <h5>{{ __('retirada.documento') }}: {{ $documento->numero }}</h5>
                <p>{{ __('retirada.cliente') }}: {{ $documento->cliente->nome }}</p>

                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('retirada.artigo') }}</th>
                        <th>{{ __('retirada.quantidade') }}</th>
                        <th>{{ __('retirada.data_entrada') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($documento->linha_documento as $linha)
                        @foreach($linha->tipo_palete as $tipoPalete)
                            <tr>
                                <td>{{ $tipoPalete->pivot->artigo_id }}</td>
                                <td>{{ $tipoPalete->pivot->quantidade }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>

                <form action="{{--{{ route('paletes.retirar') }}--}}" method="POST">
                    @csrf
                    <input type="hidden" name="documento_id" value="{{ $documento->id }}">

                    <h5>{{ __('retirada.paletes_associadas') }}</h5>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ __('retirada.selecionar') }}</th>
                            <th>{{ __('retirada.artigo') }}</th>
                            <th>{{ __('retirada.quantidade') }}</th>
                            <th>{{ __('retirada.data_entrada') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($documento->linha_documento as $linha)
                            @foreach($linha->tipo_palete as $tipoPalete)
                                @php
                                    $quantidadeNecessaria = $tipoPalete->pivot->quantidade;
                                    $paletesDisponiveis = $paletes[$documento->id]
                                        ->where('artigo_id', $tipoPalete->pivot->artigo_id)
                                        ->sortBy('data_entrada')
                                        ->take($quantidadeNecessaria);
                                @endphp

                                @foreach($paletesDisponiveis as $palete)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="paletes_selecionadas[]" value="{{ $palete->id }}">
                                        </td>
                                        <td>{{ $palete->artigo_id }}</td>
                                        <td>{{ $palete->data_entrada }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('retirada.confirmar_selecao') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
