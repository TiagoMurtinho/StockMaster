<div class="modal fade" id="documentoModal" tabindex="-1" aria-labelledby="documentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentoModalLabel">{{ __('documento.detalhes_documento') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" class="modal-documento-id" />
                <input type="hidden" class="form-control modal-documento-estado" />
                <input type="hidden" class="form-control modal-documento-tipo-documento" />

                <div class="alert alert-danger d-none error-messages" role="alert"></div>

                <div class="mb-3">
                    <div class="form-group">
                        <label for="modal-documento-observacao">{{ __('documento.observacao') }}</label>
                        <textarea class="form-control modal-documento-observacao" rows="3" placeholder="{{ __('Letras de a-z, números de 0-9 vírgulas e pontos finais.') }}"></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modal-documento-numero">{{ __('documento.numero') }}</label>
                            <input type="text" class="form-control modal-documento-numero" placeholder="{{ __('Campo numérico') }}"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modal-documento-data">{{ __('documento.data') }}</label>
                            <input type="date" class="form-control modal-documento-data" />
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modal-documento-previsao">{{ __('documento.previsao') }}</label>
                            <input type="date" class="form-control modal-documento-previsao" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="modal-documento-valor">{{ __('documento.taxa') }}</label>
                            <select class="form-select modal-documento-valor" id="taxaSelect">
                                <option value="">{{ __('documento.taxa_select') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div id="rececaoData">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal-documento-data-entrada">{{ __('documento.data_entrada') }}</label>
                                <input type="datetime-local" class="form-control modal-documento-data-entrada" />
                            </div>
                        </div>
                    </div>
                </div>

                <div id="guiaTransporteData">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal-documento-matricula">{{ __('documento.matricula') }}</label>
                                <input type="text" class="form-control modal-documento-matricula" placeholder="{{ __('No formato XX-XX-XX') }}"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal-documento-morada">{{ __('documento.morada') }}</label>
                                <input type="text" class="form-control modal-documento-morada" placeholder="{{ __('Letras de a-z, numeros de 0-9 e vírgulas') }}"/>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal-documento-previsao-descarga">{{ __('documento.previsao_descarga') }}</label>
                                <input type="datetime-local" class="form-control modal-documento-previsao-descarga" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal-documento-data-saida">{{ __('documento.data_saida') }}</label>
                                <input type="datetime-local" class="form-control modal-documento-data-saida" />
                            </div>
                        </div>
                    </div>
                </div>

                <div id="faturacaoData">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal-documento-total">{{ __('documento.total') }}</label>
                                <input type="text" class="form-control modal-documento-total" placeholder="{{ __('Campo numérico') }}"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="modal-documento-extra">{{ __('documento.extra') }}</label>
                                <input type="text" class="form-control modal-documento-extra" placeholder="{{ __('Campo numérico') }}"/>
                            </div>
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
                        <input type="hidden" name="pivot_id[]" class="modal-linha-id" value="${linha.id || ''}" />
                        </tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <button type="button" class="btn btn-success add-palete-row">
                        {{ __('documento.add_linha') }}
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('documento.fechar') }}</button>
                <button type="button" class="btn btn-primary submit-btn" onclick="saveChanges()">
                    <span class="submit-btn-text">{{ __('documento.salvar') }}</span>
                    <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</div>
