import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../core/services/auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css'
})
export class LoginComponent {
  login = '';
  motDePasse = '';
  erreur = '';
  chargement = false;

  constructor(
    private readonly authService: AuthService,
    private readonly router: Router
  ) {}

  seConnecter(): void {
    this.erreur = '';
    this.chargement = true;

    this.authService
      .login({ login: this.login.trim(), motDePasse: this.motDePasse })
      .subscribe({
        next: () => {
          this.chargement = false;
          this.router.navigate(['/tableau-de-bord']);
        },
        error: (error: { status?: number; error?: { error?: string } }) => {
          this.chargement = false;

          if (error?.status === 0) {
            this.erreur = 'Backend indisponible. Vérifiez Symfony sur http://127.0.0.1:8000.';
            return;
          }

          this.erreur = error?.error?.error || 'Identifiants invalides.';
        }
      });
  }
}
