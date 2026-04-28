import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink, RouterLinkActive } from '@angular/router';

@Component({
  selector: 'app-sidebar',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive],
  templateUrl: './sidebar.component.html',
  styleUrl: './sidebar.component.css'
})
export class SidebarComponent {
  readonly menu = [
    { label: 'Tableau de bord', route: '/tableau-de-bord', icon: 'DB' },
    { label: 'Patients', route: '/patients', icon: 'PT' },
    { label: 'Examens', route: '/examens', icon: 'EX' },
    { label: 'Resultats', route: '/resultats', icon: 'RS' },
    { label: 'Viewer', route: '/viewer', icon: 'VW' }
  ];
}
