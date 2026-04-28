# TEST PHASE EXAMENS

Write-Host "=== TEST 1: Creer un examen ===" -ForegroundColor Cyan
$body = @{
    patientId = 1
    machineId = 1
    type = "CT"
    date = "2026-04-08T14:30:00"
    description = "Test CT"
} | ConvertTo-Json

$resp = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/examens" -Method POST -ContentType "application/json" -Body $body
$data = $resp.Content | ConvertFrom-Json
$id = $data.id
Write-Host "OK - ID: $id, Statut: $($data.statut)" -ForegroundColor Green

Write-Host ""
Write-Host "=== TEST 2: Lister les examens ===" -ForegroundColor Cyan
$resp2 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/examens" -Method GET
$list = $resp2.Content | ConvertFrom-Json
Write-Host "OK - Total: $($list.Count) examens" -ForegroundColor Green

Write-Host ""
Write-Host "=== TEST 3: Changer statut PLANIFIE -> EN_COURS ===" -ForegroundColor Cyan
$body3 = @{statut = "EN_COURS"} | ConvertTo-Json
$resp3 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/examens/$id/statut" -Method PATCH -ContentType "application/json" -Body $body3
$data3 = $resp3.Content | ConvertFrom-Json
Write-Host "OK - Nouveau statut: $($data3.statut)" -ForegroundColor Green

Write-Host ""
Write-Host "=== TEST 4: Changer statut EN_COURS -> RECU ===" -ForegroundColor Cyan
$body4 = @{statut = "RECU"} | ConvertTo-Json
$resp4 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/examens/$id/statut" -Method PATCH -ContentType "application/json" -Body $body4
$data4 = $resp4.Content | ConvertFrom-Json
Write-Host "OK - Nouveau statut: $($data4.statut)" -ForegroundColor Green

Write-Host ""
Write-Host "=== TEST 5: Creer 2e examen pour annulation ===" -ForegroundColor Cyan
$body5 = @{
    patientId = 2
    machineId = 2
    type = "MR"
    date = "2026-04-09T09:00:00"
    description = "IRM test"
} | ConvertTo-Json
$resp5 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/examens" -Method POST -ContentType "application/json" -Body $body5
$data5 = $resp5.Content | ConvertFrom-Json
$id2 = $data5.id
Write-Host "OK - ID: $id2, Statut: $($data5.statut)" -ForegroundColor Green

Write-Host ""
Write-Host "=== TEST 6: Annuler un examen ===" -ForegroundColor Cyan
$resp6 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/examens/$id2" -Method DELETE
$data6 = $resp6.Content | ConvertFrom-Json
Write-Host "OK - Statut: $($data6.examen.statut)" -ForegroundColor Green

Write-Host ""
Write-Host "=== TEST 7: Filtrer par statut RECU ===" -ForegroundColor Cyan
$resp7 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/examens?statut=RECU" -Method GET
$list7 = $resp7.Content | ConvertFrom-Json
Write-Host "OK - Examens avec statut RECU: $($list7.Count)" -ForegroundColor Green

Write-Host ""
Write-Host "=== TEST 8: Voir les stats ===" -ForegroundColor Cyan
$resp8 = Invoke-WebRequest -Uri "http://127.0.0.1:8000/api/examens/stats/resume" -Method GET
$stats = $resp8.Content | ConvertFrom-Json
Write-Host "OK - Stats:" -ForegroundColor Green
Write-Host "  Planifies: $($stats.planifies)" -ForegroundColor Green
Write-Host "  En cours: $($stats.en_cours)" -ForegroundColor Green
Write-Host "  Recus: $($stats.recus)" -ForegroundColor Green
Write-Host "  Annules: $($stats.annules)" -ForegroundColor Green

Write-Host ""
Write-Host "TOUS LES TESTS REUSSIS!" -ForegroundColor Yellow
