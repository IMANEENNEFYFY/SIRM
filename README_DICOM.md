# SIRM - Système RIS avec DICOM

Système de gestion d'examens radiologiques intégrant Orthanc pour la communication DICOM.

## Architecture

- **Backend**: Symfony 7 (PHP)
- **Frontend**: Angular 18 (TypeScript)
- **DICOM Server**: Orthanc
- **Base de données**: MySQL 8

## Fonctionnalités DICOM

### 1. Worklist DICOM

La worklist permet aux modalités DICOM (scanners, IRM, etc.) de récupérer la liste des examens planifiés.

Important: pour que le bouton Worklist fonctionne réellement, Orthanc doit charger le vrai plugin Worklists. Le backend SIRM envoie alors la worklist sur l'endpoint REST du plugin, généralement `/worklists`.

#### Génération depuis Symfony

```php
// Dans ExamenController
#[Route('/{id}/worklist', name: 'examen_worklist', methods: ['POST'])]
public function genererWorklist(int $id): JsonResponse
{
    $examen = $this->examenRepository->find($id);
    $success = $this->orthancService->genererWorklist($examen);
    // ...
}
```

#### Format de worklist envoyé à Orthanc

```json
[
  {
    "PatientName": "DUPONT^JEAN",
    "PatientID": "12345",
    "PatientBirthDate": "19800101",
    "PatientSex": "M",
    "AccessionNumber": "ACC000001",
    "StudyInstanceUID": "1.2.826.0.1.3680043.2.1000.123456789",
    "StudyDescription": "Thorax",
    "Modality": "CR",
    "ScheduledStationAETitle": "SCANNER01",
    "ScheduledProcedureStepStartDate": "20240101",
    "ScheduledProcedureStepStartTime": "100000",
    "ScheduledPerformingPhysicianName": "Dr. MARTIN",
    "RequestedProcedureDescription": "Thorax",
    "ProcedureCodeSequence": [
      {
        "CodeValue": "THORAX",
        "CodingSchemeDesignator": "LOCAL",
        "CodeMeaning": "Thorax"
      }
    ]
  }
]
```

#### Configuration Orthanc

Exemple de configuration minimale dans `orthanc.json`:

```json
{
  "HttpServerEnabled": true,
  "HttpPort": 8042,
  "RemoteAccessAllowed": true,
  "LuaScripts": ["c:/path/to/orthanc_sirm_plugin.lua"],
  "Plugins": ["c:/path/to/OrthancWorklists.dll"]
}
```

Variables utiles côté SIRM backend:

- `ORTHANC_URL=http://localhost:8042`
- `ORTHANC_WORKLISTS_PATH=/worklists`

### 2. Réception d'images (C-STORE)

Quand une modalité envoie des images DICOM :

1. **Orthanc reçoit** les images via C-STORE
2. **Script Lua s'exécute** et détecte l'examen via l'AccessionNumber
3. **Webhook envoyé** à Symfony pour mettre à jour le statut
4. **Statut passe** de `EN_COURS` à `RECU`

#### Script Lua pour Orthanc

Voir le fichier `orthanc_sirm_plugin.lua` pour un exemple complet.

### 3. Monitoring en temps réel

Le composant Angular `MonitoringComponent` affiche :

- Statistiques des examens par statut
- Liste des examens récents
- Bouton pour générer des worklists
- Mise à jour automatique toutes les 30 secondes

## Installation et Configuration

### 1. Orthanc

1. Installer Orthanc
2. Copier `orthanc_sirm_plugin.lua` dans le répertoire des scripts Lua
3. Configurer dans `orthanc.json` :
```json
{
  "LuaScripts": ["/path/to/orthanc_sirm_plugin.lua"],
  "HttpProxy": "http://127.0.0.1:8000/api/dicom/webhook"
}
```

### 2. Symfony (Backend)

```bash
cd sirm-back
composer install
php bin/console doctrine:migrations:migrate
php bin/console cache:clear
```

### 3. Angular (Frontend)

```bash
cd sirm-front
npm install
npm run build
npm run serve
```

## API Endpoints

### Examens
- `GET /api/examens` - Liste des examens
- `POST /api/examens` - Créer un examen
- `POST /api/examens/{id}/worklist` - Générer worklist DICOM
- `PATCH /api/examens/{id}/statut` - Modifier statut
- `GET /api/examens/stats/resume` - Statistiques

### DICOM Webhook
- `POST /api/dicom/webhook` - Réception des notifications Orthanc

## Flux de travail typique

1. **Planification**: Créer un examen dans Angular → Statut `PLANIFIE`
2. **Worklist**: Clic "Générer worklist" → Orthanc reçoit la worklist → Statut `EN_COURS`
3. **Acquisition**: Scanner consulte la worklist et envoie les images
4. **Réception**: Orthanc reçoit les images → Webhook → Statut `RECU`
5. **Validation**: Médecin consulte les images dans l'interface

## Dépannage

### Worklist non visible dans la modalité
- Vérifier que Orthanc est accessible sur le port 104
- Vérifier le format des dates (YYYYMMDD)
- Vérifier l'AETitle de la modalité

### Webhook non reçu
- Vérifier la connectivité réseau
- Vérifier les logs Orthanc
- Vérifier les logs Symfony

### Images non réconciliées
- Vérifier l'AccessionNumber dans les métadonnées DICOM
- Vérifier que l'examen existe en base avec le bon AccessionNumber
