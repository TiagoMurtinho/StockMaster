public function store(Request $request)
{
DB::beginTransaction();

try {
$validatedData = $request->validate([
'linha_documento_id' => 'required|exists:linha_documento,id',
'localizacao' => 'nullable|array',
'tipo_palete_id' => 'required|array',
'data_entrada' => 'nullable|array',
'armazem_id' => 'required|array',
'observacao' => 'nullable|string',
]);

$linhaDocumentoId = $validatedData['linha_documento_id'];
$userId = auth()->id();

$linhaDocumento = LinhaDocumento::with('documento')->findOrFail($linhaDocumentoId);
$documentoOriginal = $linhaDocumento->documento;

Palete::create([
'linha_documento_id' => $linhaDocumentoId,
'localizacao' => $localizacao,
'data_entrada' => now(),
'tipo_palete_id' => $tipoPalete,
'artigo_id' => $artigoId,
'armazem_id' => $armazemId,
'user_id' => $userId,
]);

$novoDocumento = Documento::create([
'numero' => $documentoOriginal->numero,
'data' => now(),
'estado' => 'terminado',
'tipo_documento_id' => 2,
'cliente_id' => $documentoOriginal->cliente_id,
'user_id' => $userId,
]);

$documentoOriginal->update(['estado' => 'terminado']);

$quantidades = [];

// Agrupando e somando quantidades
foreach ($validatedData['localizacao'] as $tipoPaleteId => $localizacoes) {
$tipoPalete = $validatedData['tipo_palete_id'][$tipoPaleteId];
$armazemIds = $validatedData['armazem_id'][$tipoPaleteId];
$observacao = $validatedData['observacao'];
$artigoId = $linhaDocumento->tipo_palete->pluck('pivot.artigo_id')->first();

foreach ($localizacoes as $index => $localizacao) {
$armazemId = $armazemIds[$index] ?? null;

if (!$localizacao || !$armazemId || !$tipoPalete) {
continue;
}

$key = "{$artigoId}_{$tipoPalete}";

if (!isset($quantidades[$key])) {
$quantidades[$key] = [
'artigo_id' => $artigoId,
'tipo_palete_id' => $tipoPalete,
'quantidade' => 0,
];
}

$quantidades[$key]['quantidade']++;
}
}

// Criando registros de LinhaDocumentoTipoPalete
foreach ($quantidades as $quantidadeData) {
LinhaDocumentoTipoPalete::create([
'linha_documento_id' => $linhaDocumentoId,
'artigo_id' => $quantidadeData['artigo_id'],
'tipo_palete_id' => $quantidadeData['tipo_palete_id'],
'quantidade' => $quantidadeData['quantidade'],
]);
}

DB::commit();

return response()->json([
'success' => true,
'documento_id' => $novoDocumento->id,
]);

} catch (\Exception $e) {
DB::rollback();

Log::error('Erro ao salvar paletes e criar novo documento: ' . $e->getMessage());

return response()->json([
'success' => false,
'message' => 'Erro ao salvar as paletes e criar o novo documento: ' . $e->getMessage(),
], 500);
}
}
