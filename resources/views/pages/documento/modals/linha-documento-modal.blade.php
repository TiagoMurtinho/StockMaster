<div class="modal fade" id="modalLinhaDocumento" tabindex="-1" aria-labelledby="modalLinhaDocumentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLinhaDocumentoLabel">Linhas do Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="linhaDocumentoForm">

                    <div class="mb-3" id="quantidadeField">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" step="1" min="0" class="form-control" id="quantidade" name="numero" required>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao">
                    </div>

                    <div class="mb-3">
                        <label for="valor" class="form-label">Taxa</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="valor" name="valor" required>
                    </div>

                    <div class="mb-3" id="novaMoradaField">
                        <label for="morada" class="form-label">Nova Morada</label>
                        <input type="text" class="form-control" id="morada" name="morada">
                    </div>

                    <div class="mb-3" id="dataEntregaField">
                        <label for="data_entrega" class="form-label">Data de entrega</label>
                        <input type="datetime-local" class="form-control" id="data_entrega" name="data_entrega" required>
                    </div>

                    <div class="mb-3" id="dataRecolhaField">
                        <label for="data_recolha" class="form-label">Data de recolha</label>
                        <input type="datetime-local" class="form-control" id="data_recolha" name="data_recolha">
                    </div>

                    <div class="mb-3" id="extraField">
                        <label for="extra" class="form-label">extra</label>
                        <input type="text" class="form-control" id="extra" name="extra" required>
                    </div>

                    <div class="mb-3">
                        <label for="tipo_palete_id" class="form-label">Tipo Palete</label>
                        <select name="tipo_palete_id" id="tipo_palete_id" class="form-select form-select-sm">
                            @foreach($tipoPaletes as $tipoPalete)
                                <option value="{{ $tipoPalete->id }}">{{ $tipoPalete->tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="artigoField">
                        <label for="artigo_id" class="form-label">Artigo</label>
                        <input type="text" class="form-control" id="artigo_id" name="artigo_id" required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="criarDocumentoBtn" class="btn btn-primary">Continuar</button>
            </div>
        </div>
    </div>
</div>
