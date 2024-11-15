<div class="modal fade" id="modalGuiaTransporte" tabindex="-1" aria-labelledby="modalGuiaTransporteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGuiaTransporteModalLabel">{{ __('retirada.guia_transporte') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="documentoForm" action="{{ route('pedido-retirada.store') }}" method="POST">
                @csrf

                <input type="hidden" name="numero" id="numero">
                <input type="hidden" name="cliente_id" id="cliente_id">
                <input type="hidden" name="observacao" id="observacao">
                <input type="hidden" name="previsao" id="previsao">
                <input type="hidden" name="taxa_id" id="taxa_id">
                <input type="hidden" name="paletes_dados" id="paletes_dados">
                <input type="hidden" name="documento_id" value="{{ $documento->id }}">

                <div class="modal-body modal-documento">

                    <div class="alert alert-danger d-none error-messages" role="alert"></div>

                    <div class="mb-3">
                        <label for="matricula" class="form-label">{{ __('documento.add_matricula') }}</label>
                        <input type="text" class="form-control" id="matricula" name="matricula" required>
                    </div>
                    <div class="mb-3">
                        <label for="morada" class="form-label">{{ __('documento.add_morada') }}</label>
                        <input type="text" class="form-control" id="morada" name="morada">
                    </div>
                    <div class="mb-3">
                        <label for="previsao_descarga" class="form-label">{{ __('documento.add_descarga') }}</label>
                        <input type="datetime-local" class="form-control" id="previsao_descarga" name="previsao_descarga">
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="me-auto">
                        <button type="button" class="btn btn-secondary" id="voltarAoPedidoRetiradaModal"  data-documento-id="{{ $documento->id }}">{{__('retirada.voltar')}}</button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('documento.cancelar') }}</button>
                        <button type="button" class="btn btn-primary submit-btn" id="confirmarEnvio">
                            <span class="submit-btn-text">{{ __('retirada.confirmar') }}</span>
                            <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
