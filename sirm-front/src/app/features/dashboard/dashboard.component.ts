import { Component, OnInit } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';
import { SirmApiService } from '../../core/services/sirm-api.service';
import { Examen, ResumeStats } from '../../core/models/sirm.models';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, DatePipe],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.css'
})
export class DashboardComponent implements OnInit {
  stats: ResumeStats = { planifies: 0, en_cours: 0, recus: 0, annules: 0 };
  derniersExamens: Examen[] = [];

  constructor(private readonly api: SirmApiService) {}

  ngOnInit(): void {
    this.api.getResumeStats().subscribe({
      next: (value) => (this.stats = value)
    });

    this.api.getExamens({ limit: 6 }).subscribe({
      next: (value) => (this.derniersExamens = value)
    });
  }

  trackByExamenId(_index: number, examen: Examen): number {
    return examen.id;
  }

  getStatutLabel(statut: Examen['statut']): string {
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

  getStatutClass(statut: Examen['statut']): string {
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
}
