import { Component, OnInit } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { forkJoin } from 'rxjs';
import { DicomNonReconcilie, Examen } from '../../core/models/sirm.models';
import { SirmApiService } from '../../core/services/sirm-api.service';

@Component({
  selector: 'app-resultats',
  standalone: true,
  imports: [CommonModule, DatePipe, FormsModule],
  templateUrl: './resultats.component.html',
  styleUrl: './resultats.component.css'
})
export class ResultatsComponent implements OnInit {
  resultats: Examen[] = [];
  nonReconcilies: DicomNonReconcilie[] = [];
  examensSelection: Examen[] = [];
  selectedExamens: Record<number, number | null> = {};
  validationEnCours: Record<number, boolean> = {};
  erreur = '';

  constructor(
    private readonly api: SirmApiService,
    private readonly router: Router
  ) {}

  ngOnInit(): void {
    this.charger();
  }

  charger(): void {
    this.erreur = '';

    forkJoin({
      examens: this.api.getExamens(),
      nonReconcilies: this.api.getNonReconcilies()
    }).subscribe({
      next: ({ examens, nonReconcilies }) => {
        this.resultats = examens.filter((examen) => examen.statut === 'RECU' || !!examen.resultatDicom);
        this.examensSelection = examens.filter((examen) => this.peutEtreSelectionne(examen));
        this.nonReconcilies = nonReconcilies;
        this.selectedExamens = {};
        this.validationEnCours = {};

        for (const item of nonReconcilies) {
          const candidats = this.examensPourItem(item);
          this.selectedExamens[item.id] = candidats[0]?.id ?? null;
        }
      },
      error: () => {
        this.resultats = [];
        this.examensSelection = [];
        this.nonReconcilies = [];
        this.erreur = 'Impossible de charger les resultats DICOM.';
      }
    });
  }

  valider(item: DicomNonReconcilie): void {
    const examenId = this.selectedExamens[item.id];
    if (!examenId) {
      this.erreur = 'Selectionne un examen avant de valider le resultat non reconcilie.';
      return;
    }

    this.validationEnCours[item.id] = true;

    this.api.validerResultatNonReconcilie(item.id, examenId).subscribe({
      next: () => {
        this.erreur = '';
        this.validationEnCours[item.id] = false;
        this.charger();
      },
      error: (err) => {
        this.validationEnCours[item.id] = false;
        this.erreur = err.error?.error || 'Erreur lors de la validation du resultat.';
      }
    });
  }

  examensPourItem(item: DicomNonReconcilie): Examen[] {
    if (item.candidats.length > 0) {
      return item.candidats.filter((examen) => this.peutEtreSelectionne(examen));
    }

    if (item.patientIdDicom) {
      return this.examensSelection.filter((examen) => examen.patient.patientId === item.patientIdDicom);
    }

    return this.examensSelection;
  }

  hasExamensPourItem(item: DicomNonReconcilie): boolean {
    return this.examensPourItem(item).length > 0;
  }

  private peutEtreSelectionne(examen: Examen): boolean {
    return examen.statut !== 'ANNULE' && examen.statut !== 'RECU' && !examen.resultatDicom;
  }

  ouvrirViewer(examen: Examen): void {
    this.router.navigate(['/viewer'], {
      queryParams: {
        examenId: examen.id
      }
    });
  }

  getStatutClass(statut: Examen['statut']): string {
    switch (statut) {
      case 'RECU':
        return 'pill-success';
      case 'EN_COURS':
        return 'pill-warning';
      case 'PLANIFIE':
        return 'pill-neutral';
      case 'ANNULE':
        return 'pill-danger';
      default:
        return 'pill-info';
    }
  }
}
