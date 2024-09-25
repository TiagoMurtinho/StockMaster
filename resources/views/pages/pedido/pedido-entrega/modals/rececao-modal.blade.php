@foreach($documentos as $documento)
    @foreach($documento->linha_documento as $linha)
        <div class="modal fade" id="rececaoModal{{ $linha->id }}" tabindex="-1" aria-labelledby="rececaoModalLabel{{ $linha->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg rececao-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rececaoModalLabel{{ $linha->id }}">{{ __('Verificação de Paletes para o Pedido') }} {{ $documento->numero }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="modalForm" action="{{ route('palete.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="linha_documento_id" value="{{ $linha->id }}">
                            <input type="hidden" name="cliente_id" value="{{ $documento->cliente_id }}">

                            <div class="mb-3">
                                    <label for="descricao" class="form-label">{{ __('Observação (opcional)') }}</label>
                                <input type="text" name="observacao" id="observacao" class="form-control" placeholder="Escreva aqui as suas observações">
                            </div>
                            <div class="scrollable-palete-area">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">{{ __('Tipo de Palete') }}</th>
                                        <th scope="col">{{ __('Palete #') }}</th>
                                        <th scope="col">{{ __('Localização') }}</th>
                                        <th scope="col">{{ __('Armazém') }}</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($linha->tipo_palete as $tipoPalete)
                                        @for($i = 1; $i <= $tipoPalete->pivot->quantidade; $i++)
                                            <tr>
                                                <td>{{ $tipoPalete->tipo }}</td>
                                                <td>{{ $i }}</td>
                                                <td>
                                                    <input type="text" name="localizacao[{{ $tipoPalete->id }}][]" class="form-control" placeholder="Localização" value="">
                                                </td>
                                                <td>
                                                    <select name="armazem_id[{{ $tipoPalete->id }}][]" class="form-control armazem-select" data-tipo-palete-id="{{ $tipoPalete->id }}">
                                                        <!-- As opções serão preenchidas pelo JavaScript -->
                                                    </select>
                                                </td>
                                                <input type="hidden" name="tipo_palete_id[{{ $tipoPalete->id }}]" value="{{ $tipoPalete->id }}">
                                            </tr>
                                        @endfor
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">{{ __('Confirmar Verificação') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endforeach

<script id="armazem-options" type="application/json">
    @json($armazens)
</script>
