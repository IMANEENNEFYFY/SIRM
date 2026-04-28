import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import {
  Examen,
  ExamenCreatePayload,
  ExamenUpdatePayload,
  Machine,
  Patient,
  StatutExamen
} from '../../core/models/sirm.models';
import { SirmApiService } from '../../core/services/sirm-api.service';

@Component({
  selector: 'app-examens',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './examens.component.html',
  styleUrl: './examens.component.css'
})
export class ExamensComponent implements OnInit {
  examens: Examen[] = [];
  patients: Patient[] = [];
  machines: Machine[] = [];
  erreur = '';

  creation: ExamenCreatePayload = {
    patientId: 0,
    machineId: 0,
    type: '',
    description: '',
    date: ''
  };

  examenEnEdition: Examen | null = null;
  edition: ExamenUpdatePayload = {
    patientId: 0,
    machineId: 0,
    type: '',
    description: '',
    date: ''
  };

  constructor(
    private readonly api: SirmApiService,
    private readonly router: Router
  ) {}

  ngOnInit(): void {
    this.chargerReferentiels();
    this.charger();
  }

  get machineSelectionnee(): Machine | undefined {
    return this.machines.find((machine) => machine.id === this.creation.machineId);
  }

  chargerReferentiels(): void {
    this.api.getPatients().subscribe({
      next: (value) => {
        this.patients = value;
        if (!this.creation.patientId && value.length) {
          this.creation.patientId = value[0].id;
        }
      }
    });

    this.api.getMachines().subscribe({
      next: (value) => {
        this.machines = value;
        if (!this.creation.machineId && value.length) {
          this.creation.machineId = value[0].id;
        }
      }
    });
  }

  charger(): void {
    this.erreur = '';
    this.api.getExamens().subscribe({
      next: (value) => (this.examens = value),
      error: () => {
        this.examens = [];
        this.erreur = 'Impossible de charger les examens.';
      }
    });
  }

  creerExamen(): void {
    if (!this.creation.patientId || !this.creation.machineId || !this.creation.type) {
      this.erreur = 'Veuillez compléter les champs obligatoires du formulaire examen.';
      return;
    }

    // Vérifier que la machine est disponible
    const machineSelectionnee = this.machines.find(m => m.id === this.creation.machineId);
    if (machineSelectionnee && !machineSelectionnee.isDisponible) {
      this.erreur = `Machine non disponible. Statut actuel: ${machineSelectionnee.statut}`;
      return;
    }

    this.api.createExamen(this.creation).subscribe({
      next: () => {
        this.erreur = '';
        this.creation = {
          patientId: this.creation.patientId,
          machineId: this.creation.machineId,
          type: '',
          description: '',
          date: ''
        };
        this.charger();
        this.chargerReferentiels();
      },
      error: (err) => {
        this.erreur = err.error?.error || 'Erreur lors de la création de l\'examen.';
      }
    });
  }

  activerEdition(examen: Examen): void {
    this.examenEnEdition = examen;
    this.edition = {
      patientId: examen.patient.id,
      machineId: examen.machine.id,
      type: examen.type,
      description: examen.description ?? '',
      date: examen.date
    };
  }

  annulerEdition(): void {
    this.examenEnEdition = null;
  }

  sauvegarderEdition(): void {
    if (!this.examenEnEdition) {
      return;
    }

    this.api.updateExamen(this.examenEnEdition.id, this.edition).subscribe({
      next: () => {
        this.erreur = '';
        this.examenEnEdition = null;
        this.charger();
      },
      error: () => {
        this.erreur = 'Erreur lors de la modification de l\'examen.';
      }
    });
  }

  changerStatut(examen: Examen, statut: StatutExamen): void {
    this.api.changerStatutExamen(examen.id, statut).subscribe({
      next: () => {
        this.erreur = '';
        this.charger();
        this.chargerReferentiels();
      }
    });
  }

  envoyerWorklist(examen: Examen): void {
    this.api.genererWorklist(examen.id).subscribe({
      next: () => {
        this.erreur = '';
        this.charger();
      },
      error: (err) => {
        this.erreur = err.error?.error || 'Erreur lors du demarrage de l examen.';
      }
    });
  }

  recreerExamen(examen: Examen): void {
    this.api.recreerExamen(examen.id).subscribe({
      next: () => {
        this.erreur = '';
        this.charger();
        this.chargerReferentiels();
      },
      error: (err) => {
        this.erreur = err.error?.error || 'Erreur lors de la recreation de l examen.';
      }
    });
  }

  ouvrirViewer(examen: Examen): void {
    this.router.navigate(['/viewer'], {
      queryParams: {
        examenId: examen.id
      }
    });
  }

  getStatutMachineBadgeClass(statut?: string): string {
    switch (statut) {
      case 'DISPONIBLE':
        return 'pill-success';
      case 'EN_COURS':
        return 'pill-warning';
      case 'FAIT':
        return 'pill-danger';
      case 'EN_MAINTENANCE':
        return 'pill-info';
      case 'HORS_SERVICE':
        return 'pill-neutral';
      default:
        return 'pill-neutral';
    }
  }

  isMachineDisponible(machine: Machine): boolean {
    return machine.statut === 'DISPONIBLE' || !machine.statut;
  }

  getExamenStatusLabel(statut: StatutExamen): string {
    switch (statut) {
      case 'PLANIFIE':
        return 'Planifie';
      case 'EN_COURS':
        return 'En cours';
      case 'RECU':
        return 'Recu';
      case 'ANNULE':
        return 'Annule';
      default:
        return statut;
    }
  }

  getExamenStatusClass(statut: StatutExamen): string {
    switch (statut) {
      case 'PLANIFIE':
        return 'pill-neutral';
      case 'EN_COURS':
        return 'pill-warning';
      case 'RECU':
        return 'pill-success';
      case 'ANNULE':
        return 'pill-danger';
      default:
        return 'pill-info';
    }
  }

  getMachineStatusLabel(statut?: string): string {
    switch (statut) {
      case 'DISPONIBLE':
        return 'Disponible';
      case 'EN_COURS':
        return 'En cours';
      case 'FAIT':
        return 'Occupee';
      case 'EN_MAINTENANCE':
        return 'Maintenance';
      case 'HORS_SERVICE':
        return 'Hors service';
      default:
        return 'Non renseigne';
    }
  }

  peutModifier(examen: Examen): boolean {
    return examen.statut === 'EN_COURS';
  }

  peutCommencer(examen: Examen): boolean {
    return examen.statut === 'PLANIFIE';
  }

  peutVoirViewer(examen: Examen): boolean {
    return examen.statut === 'RECU';
  }

  peutRecreer(examen: Examen): boolean {
    return examen.statut === 'ANNULE';
  }
}
