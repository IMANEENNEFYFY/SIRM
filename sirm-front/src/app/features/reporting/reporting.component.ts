import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ActivatedRoute } from '@angular/router';
import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatSnackBar, MatSnackBarModule } from '@angular/material/snack-bar';
import { Examen, StatutExamen } from '../../core/models/sirm.models';
import { SirmApiService } from '../../core/services/sirm-api.service';

@Component({
  selector: 'app-reporting',
  standalone: true,
  imports: [CommonModule, FormsModule, MatButtonModule, MatFormFieldModule, MatInputModule, MatSnackBarModule],
  template: `
    <section class="reporting card" *ngIf="!erreur; else blocErreur">
      <header *ngIf="examen">
        <h2>Compte-rendu radiologique</h2>
        <p>
          Patient: <strong>{{ examen.patient.prenom }} {{ examen.patient.nom }}</strong>
          | Examen: <strong>{{ examen.accessionNumber }}</strong>
        </p>
      </header>

      <div class="formulaire" *ngIf="rapport">
        <mat-form-field appearance="outline">
          <mat-label>Titre</mat-label>
          <input matInput [(ngModel)]="rapport.titre" />
        </mat-form-field>

        <mat-form-field appearance="outline">
          <mat-label>Indication</mat-label>
          <textarea matInput rows="2" [(ngModel)]="rapport.indication"></textarea>
        </mat-form-field>

        <mat-form-field appearance="outline">
          <mat-label>Technique</mat-label>
          <textarea matInput rows="3" [(ngModel)]="rapport.technique"></textarea>
        </mat-form-field>

        <mat-form-field appearance="outline">
          <mat-label>Constatations</mat-label>
          <textarea matInput rows="6" [(ngModel)]="rapport.constatations"></textarea>
        </mat-form-field>

        <mat-form-field appearance="outline">
          <mat-label>Conclusion</mat-label>
          <textarea matInput rows="3" [(ngModel)]="rapport.conclusion"></textarea>
        </mat-form-field>

        <div class="actions">
          <button mat-flat-button color="primary" (click)="validerCompteRendu()">Valider et signer le compte-rendu</button>
        </div>
      </div>
    </section>

    <ng-template #blocErreur>
      <section class="reporting card erreur">{{ erreur }}</section>
    </ng-template>
  `
})
export class ReportingComponent implements OnInit {
  examen: Examen | null = null;
  rapport = {
    titre: '',
    indication: '',
    technique: '',
    constatations: '',
    conclusion: '',
    valide: false,
    dateValidation: ''
  };
  chargement = false;
  erreur = '';

  constructor(
    private readonly route: ActivatedRoute,
    private readonly apiService: SirmApiService,
    private readonly snackBar: MatSnackBar
  ) {}

  ngOnInit(): void {
    const examenId = Number(this.route.snapshot.queryParamMap.get('examenId'));

    if (!examenId) {
      this.erreur = 'Aucun examen sélectionné pour le compte-rendu.';
      return;
    }

    this.chargement = true;

    this.apiService.getExamenById(examenId).subscribe({
      next: (examen) => {
        this.examen = examen;
        this.rapport = {
          titre: `Compte-rendu ${examen.type}`,
          indication: examen.description ?? '',
          technique: '',
          constatations: examen.compteRendu ?? '',
          conclusion: '',
          valide: examen.statut === 'RECU',
          dateValidation: ''
        };
        this.chargement = false;
      },
      error: () => {
        this.erreur = "Impossible de charger l'examen pour le compte-rendu.";
        this.chargement = false;
      }
    });
  }

  validerCompteRendu(): void {
    if (!this.examen) {
      return;
    }

    const corps = [
      `INDICATION: ${this.rapport.indication}`,
      `TECHNIQUE: ${this.rapport.technique}`,
      `CONSTATIONS: ${this.rapport.constatations}`,
      `CONCLUSION: ${this.rapport.conclusion}`
    ].join('\n\n');

    this.apiService.changerStatutExamen(this.examen.id, 'RECU' satisfies StatutExamen).subscribe({
      next: () => {
        this.snackBar.open('Compte-rendu validé. Statut mis à jour à Terminé.', 'Fermer', {
          duration: 3000
        });
        this.rapport.constatations = corps;
        this.rapport.valide = true;
        this.rapport.dateValidation = new Date().toISOString();
      },
      error: () => {
        this.snackBar.open('Impossible de valider le compte-rendu.', 'Fermer', {
          duration: 3000
        });
      }
    });
  }
}
