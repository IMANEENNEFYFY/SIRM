-- Script Lua pour Orthanc - Gestion des worklists et notifications webhook
-- Ce script intercepte les événements DICOM et notifie le système SIRM

-- Configuration
local SIRM_WEBHOOK_URL = 'http://127.0.0.1:8000/api/dicom/webhook'
local ORTHANC_URL = 'http://localhost:8042'

-- Fonction utilitaire pour logger
function LogInfo(message)
  print('[SIRM] ' .. message)
end

function LogError(message)
  print('[SIRM ERROR] ' .. message)
end

-- Fonction pour envoyer une notification webhook
function SendWebhook(payload)
  local jsonPayload = JsonToString(payload)

  -- Utilisation de l'API HTTP d'Orthanc pour envoyer le webhook
  local response = HttpPost(SIRM_WEBHOOK_URL, 'application/json', jsonPayload)

  if response.Status == 200 then
    LogInfo('Webhook envoyé avec succès')
  else
    LogError('Erreur webhook: ' .. response.Status .. ' - ' .. response.Body)
  end
end

-- Hook appelé quand une instance DICOM est stockée
function OnStoredInstance(instanceId, tags, metadata)
  LogInfo('Nouvelle instance reçue: ' .. instanceId)

  -- Récupérer les informations de l'instance
  local studyInstanceUID = tags['StudyInstanceUID'] or ''
  local patientId = tags['PatientID'] or ''
  local patientName = tags['PatientName'] or ''
  local modality = tags['Modality'] or ''
  local accessionNumber = tags['AccessionNumber'] or ''

  -- Préparer le payload pour le webhook
  local payload = {
    event = 'instance_stored',
    instanceId = instanceId,
    studyInstanceUID = studyInstanceUID,
    patientId = patientId,
    patientName = patientName,
    modality = modality,
    accessionNumber = accessionNumber,
    timestamp = os.time()
  }

  -- Envoyer le webhook
  SendWebhook(payload)

  -- Marquer l'examen comme reçu dans SIRM
  if accessionNumber ~= '' then
    LogInfo('Examen ' .. accessionNumber .. ' marqué comme reçu')
  end
end

-- Hook appelé quand une worklist est créée/modifiée
function OnWorklistCreated(accessionNumber, worklistData)
  LogInfo('Worklist créée pour accession: ' .. accessionNumber)

  local payload = {
    event = 'worklist_created',
    accessionNumber = accessionNumber,
    worklistData = worklistData,
    timestamp = os.time()
  }

  SendWebhook(payload)
end

-- Hook appelé au démarrage d'Orthanc
function Initialize()
  LogInfo('Plugin SIRM initialisé')

  -- Enregistrer les hooks
  RegisterOnStoredInstance(OnStoredInstance)

  -- Hook personnalisé pour les worklists (si disponible)
  if RegisterOnWorklistCreated then
    RegisterOnWorklistCreated(OnWorklistCreated)
  end
end

-- Initialisation
Initialize()