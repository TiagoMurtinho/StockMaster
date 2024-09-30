<div class="modal fade" id="modalAddDocumento" tabindex="-1" aria-labelledby="modalAddDocumentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddDocumentoModalLabel">{{ __('documento.novo_documento') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-4 text-sm text-gray-600">
                    {{ __('documento.description') }}
                </div>

                <div class="alert alert-danger d-none error-messages" role="alert"></div>

                <form id="documentoForm" action="{{ route('documento.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cliente" class="form-label">{{ __('documento.add_cliente') }}</label>
                            <select class="form-select" id="cliente" name="cliente_id" required>
                                <option selected disabled>{{ __('documento.selecione_cliente') }}</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="tipo_documento" class="form-label">{{ __('documento.add_tipo') }}</label>
                            <select class="form-select" id="tipo_documento" name="tipo_documento_id" required>
                                <option selected disabled>{{ __('documento.selecione_tipo') }}</option>
                                @foreach($tiposDocumento as $tipo)
                                    <option value="{{ $tipo->id }}" data-modal="{{ $tipo->nome }}">{{ $tipo->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6" id="previsaoOculta">
                            <label for="previsao" class="form-label">{{ __('documento.add_previsao') }}</label>
                            <input type="date" class="form-control" id="previsao" name="previsao" required>
                        </div>
                        <div class="col-md-6">
                            <label for="numero" class="form-label">{{ __('documento.add_numero') }}</label>
                            <input type="number" class="form-control" id="numero" name="numero" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6" id="taxaOculta">
                            <label for="taxa_id" class="form-label">{{ __('documento.add_taxa') }}</label>
                            <select class="form-select" id="taxa_id" name="taxa_id">
                                <option value="">{{ __('Selecione uma taxa') }}</option>
                                @foreach($taxas as $taxa)
                                    <option value="{{ $taxa->id }}">{{ $taxa->nome }} - {{ $taxa->valor }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6" id="moradaOculta">
                            <label for="morada" class="form-label">{{ __('documento.add_morada') }}</label>
                            <input type="text" class="form-control" id="morada" name="morada">
                        </div>
                    </div>

                    <div id="faturacaoOculta">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="total" class="form-label">{{ __('documento.add_total') }}</label>
                                <input type="number" min="0" step="0.01" class="form-control" id="total" name="total" required>
                            </div>
                            <div class="col-md-6">
                                <label for="extra" class="form-label">{{ __('documento.add_extra') }}</label>
                                <input type="number" min="0" step="0.01" class="form-control" id="extra" name="extra">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3" id="datasOcultas">
                        <div class="col-md-6">
                            <label for="data_inicio" class="form-label">{{ __('documento.add_data_inicio') }}</label>
                            <input type="date" class="form-control" id="data_inicio" name="data_inicio">
                        </div>
                        <div class="col-md-6">
                            <label for="data_fim" class="form-label">{{ __('documento.add_data_fim') }}</label>
                            <input type="date" class="form-control" id="data_fim" name="data_fim">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="mb-3">
                            <label for="observacao" class="form-label">{{ __('documento.add_observacao') }}</label>
                            <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('documento.cancelar') }}</button>
                        <button type="button" id="continuarModalLinhaDocumentoBtn" class="btn btn-primary">{{ __('documento.continuar') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
