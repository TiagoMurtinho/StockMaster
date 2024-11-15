@foreach($documentos as $documento)
    <div class="modal fade" id="retiradaModal{{$documento->id}}" tabindex="-1" aria-labelledby="retiradaModalLabel{{$documento->id}}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="retiradaModalLabel{{$documento->id}}">{{ __('retirada.detalhes') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>{{ __('retirada.documento') }} {{ $documento->numero }}</h5>
                    <p>{{ __('retirada.cliente') }} {{ $documento->cliente->nome }}</p>
                    <p>{{ __('retirada.previsao_saida') }} {{ $documento->previsao }}</p>

                    <div class="alert alert-danger d-none error-messages-paletes" role="alert"></div>

                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ __('retirada.artigo') }}</th>
                            <th>{{ __('retirada.quantidade') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($documento->tipo_palete as $tipoPalete)
                            <tr>
                                <td>{{ $artigos[$tipoPalete->pivot->artigo_id]->nome ?? 'Artigo não disponível' }}</td>
                                <td>{{ $tipoPalete->pivot->quantidade }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <form id="documentoForm{{$documento->id}}" action="{{ route('paletes.retirar') }}" method="POST" onsubmit="return false;">
                        @csrf
                        <input type="hidden" name="documento_id" value="{{ $documento->id }}">

                        <h5>{{ __('retirada.paletes_associadas') }}</h5>
                        <div class="scrollable-palete-area">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>{{ __('retirada.selecionar') }}</th>
                                    <th>{{ __('retirada.localizacao') }}</th>
                                    <th>{{ __('retirada.artigo') }}</th>
                                    <th>{{ __('retirada.data_entrada') }}</th>
                                    <th>{{ __('retirada.tipo_palete') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($documento->tipo_palete as $tipoPalete)
                                    @php
                                        $quantidadeNecessaria = $tipoPalete->pivot->quantidade;
                                        $paletesDisponiveis = $paletesPorLinha[$documento->id][$tipoPalete->id] ?? collect();
                                        $quantidadeDisponivel = $paletesDisponiveis->where('artigo_id', $tipoPalete->pivot->artigo_id)->where('tipo_palete_id', $tipoPalete->id)->count();
                                    @endphp

                                    @if($quantidadeDisponivel < $quantidadeNecessaria)
                                        <tr>
                                            <td colspan="5" class="text-danger text-center">
                                                {{ __('A palete com artigo: ' . ($artigos[$tipoPalete->pivot->artigo_id]->nome ?? 'desconhecido') . ' e tipo de palete: ' . ($tipoPaletes[$tipoPalete->pivot->tipo_palete_id]->tipo ?? 'desconhecido') . ' não está registada no sistema.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    @foreach($paletesDisponiveis as $palete)
                                        @if($palete->artigo_id == $tipoPalete->pivot->artigo_id && $palete->tipo_palete_id == $tipoPalete->id)
                                            <tr>
                                                <td>
                                                    <label class="custom-checkbox">
                                                        <input type="checkbox" name="paletes_selecionadas[]" value="{{ $palete->id }}"
                                                               data-tipo-palete-id="{{ $palete->tipo_palete_id }}"
                                                               data-artigo-id="{{ $palete->artigo_id }}"
                                                               data-armazem-id="{{ $palete->armazem_id }}"
                                                               data-localizacao="{{ $palete->localizacao }}">
                                                        <span class="checkbox-box"></span>
                                                    </label>
                                                </td>
                                                <td>{{ $palete->localizacao }}</td>
                                                <td>{{ $palete->artigo->nome ?? 'Desconhecido' }}</td>
                                                <td>{{ $palete->data_entrada }}</td>
                                                <td>{{ $palete->tipo_palete->tipo ?? 'Desconhecido' }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="continuarGuiaTransporteBtn btn btn-primary"
                                    data-documento-id="{{ $documento->id }}"
                                    data-documento-numero="{{ $documento->numero }}"
                                    data-documento-cliente-id="{{ $documento->cliente_id }}"
                                    data-linha-observacao="{{ $documento->observacao }}"
                                    data-linha-previsao="{{ $documento->previsao }}"
                                    data-linha-taxa-id="{{ $documento->taxa_id }}"
                                    data-documento-morada="{{ $documento->morada }}">
                                {{ __('retirada.confirmar_selecao') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('pages.pedido.pedido-retirada.modals.guia-transporte-modal', ['documento' => $documento])

@endforeach


