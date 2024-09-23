<div class="modal fade" id="documentoModal" tabindex="-1" aria-labelledby="documentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentoModalLabel">{{ __('documento.detalhes_documento') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               {{-- @foreach($documentos as $documento)--}}

                <input type="hidden" class="modal-documento-id" />

                @foreach($documento->linha_documento as $linha)
                    <input type="hidden" class="modal-linha-id" value="{{ $linha->id }}" />
                @endforeach

                <div class="mb-3">
                    <div class="form-group">
                        <label for="modal-documento-observacao">{{__('documento.observacao')}}</label>
                        <textarea class="form-control modal-documento-observacao" rows="3"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modal-documento-numero">{{__('documento.numero')}}</label>
                            <input type="text" class="form-control modal-documento-numero" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modal-documento-data">{{__('documento.data')}}</label>
                            <input type="date" class="form-control modal-documento-data" />
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modal-documento-previsao">{{__('documento.previsao')}}</label>
                            <input type="date" class="form-control modal-documento-previsao" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modal-documento-valor">{{__('documento.taxa')}}</label>
                            <select class="form-select modal-documento-valor" id="taxaSelect">
                                <option value="">{{__('documento.taxa_select')}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                    <div class="scrollable-palete-area">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ __('documento.tipo_palete') }}</th>
                                <th>{{ __('documento.quantidade') }}</th>
                                <th>{{ __('documento.artigo') }}</th>
                            </tr>
                            </thead>
                            <tbody class="modal-linhas">
                            <tr class="modal-linha" data-id="{{ $documento->id }}">
                                <td>
                                    <select class="form-select modal-linha-tipo-palete"></select>
                                </td>
                                <td>
                                    <input type="number" class="form-control modal-linha-quantidade" />
                                </td>
                                <td>
                                    <input type="number" class="form-control modal-linha-artigo" />
                                </td>
                                <td>
                                    <input type="hidden" name="deleted[]" value="0" />
                                    @foreach($documento->linha_documento as $linha)
                                        @foreach($linha->tipo_palete as $tipoPalete)
                                            <input type="hidden" name="pivot_id[]" class="modal-linha-pivot-id" value="{{ $tipoPalete->pivot->id ?? '' }}" />
                                        @endforeach
                                    @endforeach
                                    {{--<a type="button" class="remove-palete-row">
                                        <i class="bi bi-trash"></i>
                                    </a>--}}
                                </td>
                            </tr>
                            {{--@endforeach--}}
                            </tbody>
                        </table>
                    </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-success add-palete-row">
                        {{__('documento.add_linha')}}
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('documento.fechar') }}</button>
                <button type="button" class="btn btn-primary" onclick="saveChanges()">{{ __('documento.salvar') }}</button>
            </div>
        </div>
    </div>
</div>
