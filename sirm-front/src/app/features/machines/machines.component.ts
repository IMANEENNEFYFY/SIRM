import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Machine, StatutMachine } from '../../core/models/sirm.models';
import { SirmApiService } from '../../core/services/sirm-api.service';

@Component({
  selector: 'app-machines',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './machines.component.html',
  styleUrl: './machines.component.css'
})
export class MachinesComponent implements OnInit {
  machines: Machine[] = [];
  statutSelection: Record<number, StatutMachine> = {};
  chargement = false;
  erreur = '';

  readonly statutOptions: Array<{ value: StatutMachine; label: string }> = [
    { value: 'DISPONIBLE', label: 'Disponible' },
    { value: 'EN_COURS', label: 'En cours' },
    { value: 'FAIT', label: 'Fait' },
    { value: 'EN_MAINTENANCE', label: 'En maintenance' },
    { value: 'HORS_SERVICE', label: 'Hors service' }
  ];

  constructor(private readonly api: SirmApiService) {}

  ngOnInit(): void {
    this.charger();
  }

  charger(): void {
    this.chargement = true;
    this.erreur = '';
    this.api.getMachines().subscribe({
      next: (value) => {
        this.machines = value;
        this.statutSelection = value.reduce<Record<number, StatutMachine>>((acc, machine) => {
          if (machine.statut) {
            acc[machine.id] = machine.statut;
          }
          return acc;
        }, {});
        this.chargement = false;
      },
      error: () => {
        this.machines = [];
        this.chargement = false;
        this.erreur = 'Impossible de charger les machines.';
      }
    });
  }

  sauvegarderStatut(machine: Machine): void {
    const statut = this.statutSelection[machine.id];
    if (!statut) {
      return;
    }

    this.api.updateMachineStatut(machine.id, { statut }).subscribe({
      next: (response) => {
        machine.statut = response.statut;
        machine.isDisponible = response.statut === 'DISPONIBLE';
        this.statutSelection[machine.id] = response.statut;
        this.erreur = '';
      },
      error: () => {
        this.erreur = 'Erreur lors de la mise a jour de la machine.';
      }
    });
  }

  getStatutLabel(statut?: StatutMachine): string {
    const option = this.statutOptions.find((item) => item.value === statut);
    return option?.label ?? '-';
  }

  getStatutBadgeClass(statut?: StatutMachine): string {
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

  countByStatut(statut: StatutMachine): number {
    return this.machines.filter((machine) => machine.statut === statut).length;
  }

  trackByMachineId(_index: number, machine: Machine): number {
    return machine.id;
  }
}
