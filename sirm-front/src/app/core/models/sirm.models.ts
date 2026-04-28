export type StatutExamen = 'PLANIFIE' | 'EN_COURS' | 'RECU' | 'ANNULE' | 'EN_ATTENTE';
export type StatutMachine = 'DISPONIBLE' | 'EN_COURS' | 'FAIT' | 'EN_MAINTENANCE' | 'HORS_SERVICE';

export interface Patient {
  id: number;
  patientId: string;
  nom: string;
  prenom: string;
  cin?: string;
  dateNaissance?: string;
  sexe?: string;
  telephone?: string | null;
  adresse?: string | null;
  actif?: boolean;
}

export interface Machine {
  id: number;
  nom: string;
  modalite: string;
  aeTitle: string;
  statut?: StatutMachine;
  couleur?: string;
  isDisponible?: boolean;
  dateDebut?: string;
  dateFin?: string;
  description?: string;
}

export interface Examen {
  id: number;
  accessionNumber: string;
  date: string;
  type: string;
  statut: StatutExamen;
  description?: string | null;
  compteRendu?: string | null;
  patient: {
    id: number;
    nom: string;
    prenom: string;
    patientId: string;
  };
  machine: {
    id: number;
    nom: string;
    modalite: string;
    aeTitle: string;
    statut?: StatutMachine;
    isDisponible?: boolean;
  };
  resultatDicom?: {
    id: number;
    orthancInstanceId: string;
    studyInstanceUid: string;
    modality?: string | null;
    orthancUrl?: string | null;
    receivedAt: string;
  };
}

export interface DicomNonReconcilie {
  id: number;
  orthancInstanceId: string;
  patientIdDicom?: string | null;
  patientNomDicom?: string | null;
  studyInstanceUid?: string | null;
  modality?: string | null;
  statut: string;
  receivedAt: string;
  candidats: Examen[];
}

export interface PatientCreatePayload {
  nom: string;
  prenom: string;
  cin: string;
  dateNaissance: string;
  sexe: string;
  telephone?: string;
  adresse?: string;
}

export interface PatientUpdatePayload {
  nom?: string;
  prenom?: string;
  telephone?: string;
  adresse?: string;
}

export interface ExamenCreatePayload {
  patientId: number;
  machineId: number;
  type: string;
  description?: string;
  date?: string;
}

export interface ExamenUpdatePayload {
  patientId?: number;
  machineId?: number;
  type?: string;
  description?: string;
  date?: string;
}

export interface LoginPayload {
  login: string;
  motDePasse: string;
}

export interface LoginResponse {
  token: string;
}

export interface DicomSource {
  examenId: number;
  orthancInstanceId: string;
  orthancFileUrl: string;
  source: string;
}

export interface ResumeStats {
  planifies: number;
  en_cours: number;
  recus: number;
  annules: number;
}
