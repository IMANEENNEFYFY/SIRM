import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { NavigationEnd, Router } from '@angular/router';
import { filter } from 'rxjs/operators';
import { AuthService } from '../../core/services/auth.service';

@Component({
  selector: 'app-header',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './header.component.html',
  styleUrl: './header.component.css'
})
export class HeaderComponent {
  pageTitle = 'Tableau de bord';

  constructor(
    private readonly router: Router,
    private readonly authService: AuthService
  ) {
    this.router.events.pipe(filter((event) => event instanceof NavigationEnd)).subscribe((event: unknown) => {
      const url = (event as NavigationEnd).urlAfterRedirects;
      if (url.includes('patients')) this.pageTitle = 'Gestion des patients';
      else if (url.includes('examens')) this.pageTitle = 'Gestion des examens';
      else if (url.includes('resultats')) this.pageTitle = 'Resultats des examens';
      else if (url.includes('viewer')) this.pageTitle = 'Viewer DICOM';
      else this.pageTitle = 'Tableau de bord';
    });
  }

  seDeconnecter(): void {
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}
