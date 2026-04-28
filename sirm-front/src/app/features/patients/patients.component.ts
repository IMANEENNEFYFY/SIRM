import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Patient, PatientCreatePayload, PatientUpdatePayload } from '../../core/models/sirm.models';
import { SirmApiService } from '../../core/services/sirm-api.service';

@Component({
  selector: 'app-patients',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './patients.component.html',
  styleUrl: './patients.component.css'
})
export class PatientsComponent implements OnInit {
  recherche = '';
  patients: Patient[] = [];
  chargement = false;
  erreur = '';

  creation: PatientCreatePayload = {
    nom: '',
    prenom: '',
    cin: '',
    dateNaissance: '',
    sexe: 'M',
    telephone: '',
    adresse: ''
  };

  patientEnEdition: Patient | null = null;
  edition: PatientUpdatePayload = {
    nom: '',
    prenom: '',
    telephone: '',
    adresse: ''
  };

  constructor(private readonly api: SirmApiService) {}

  ngOnInit(): void {
    this.charger();
  }

  charger(): void {
    this.chargement = true;
    this.erreur = '';
    this.api.getPatients(this.recherche).subscribe({
      next: (value) => {
        this.patients = value;
        this.chargement = false;
      },
      error: () => {
        this.patients = [];
        this.chargement = false;
        this.erreur = 'Impossible de charger les patients.';
      }
    });
  }

  creerPatient(): void {
    if (!this.creation.nom || !this.creation.prenom || !this.creation.cin || !this.creation.dateNaissance) {
      this.erreur = 'Veuillez compléter les champs obligatoires du formulaire patient.';
      return;
    }

    this.api.createPatient(this.creation).subscribe({
      next: () => {
        this.erreur = '';
        this.creation = {
          nom: '',
          prenom: '',
          cin: '',
          dateNaissance: '',
          sexe: 'M',
          telephone: '',
          adresse: ''
        };
        this.charger();
      },
      error: () => {
        this.erreur = 'Erreur lors de la création du patient.';
      }
    });
  }

  activerEdition(patient: Patient): void {
    this.patientEnEdition = patient;
    this.edition = {
      nom: patient.nom,
      prenom: patient.prenom,
      telephone: patient.telephone ?? '',
      adresse: patient.adresse ?? ''
    };
  }

  annulerEdition(): void {
    this.patientEnEdition = null;
  }

  sauvegarderEdition(): void {
    if (!this.patientEnEdition) {
      return;
    }

    this.api.updatePatient(this.patientEnEdition.id, this.edition).subscribe({
      next: () => {
        this.erreur = '';
        this.patientEnEdition = null;
        this.charger();
      },
      error: () => {
        this.erreur = 'Erreur lors de la mise à jour du patient.';
      }
    });
  }
}
